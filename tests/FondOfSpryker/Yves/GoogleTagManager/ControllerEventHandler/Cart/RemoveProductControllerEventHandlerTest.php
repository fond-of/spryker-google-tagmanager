<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;

class RemoveProductControllerEventHandlerTest extends Unit
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $sessionHandlerMock;

    /**
     * @var \Symfony\Component\HttpFoundation\Request|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var \Generated\Shared\Transfer\ItemTransfer[]|\PHPUnit\Framework\MockObject\MockObject[]
     */
    protected $itemTransferListMock;

    /**
     * @var \Generated\Shared\Transfer\ItemTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $itemTransferMock1;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $quoteTransferMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $cartClientMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart\RemoveProductControllerEventHandler
     */
    protected $eventHandler;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sessionHandlerMock = $this->getMockBuilder(EnhancedEcommerceSessionHandlerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $itemTransferMock1 = $this->getMockBuilder(ItemTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $itemTransferMock2 = $this->getMockBuilder(ItemTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $itemTransferMock1
            ->method('getSku')
            ->willReturn('SKU-111');

        $itemTransferMock1
            ->method('getQuantity')
            ->willReturn(3);

        $itemTransferMock2
            ->method('getSku')
            ->willReturn('SKU-222');

        $itemTransferMock2
            ->method('getQuantity')
            ->willReturn(3);

        $this->itemTransferListMock = [
            $itemTransferMock1,
            $itemTransferMock2,
        ];

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cartClientMock = $this->getMockBuilder(GoogleTagManagerToCartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->eventHandler = new RemoveProductControllerEventHandler(
            $this->sessionHandlerMock,
            $this->cartClientMock
        );
    }

    /**
     * @return void
     */
    public function testHandleSuccess(): void
    {
        $this->requestMock->expects($this->once())
            ->method('get')
            ->willReturn('SKU-111');

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn($this->itemTransferListMock);

        $methodGetProductFromQuote = static::getMethod('getProductFromQuote');
        $itemTransferMock = $methodGetProductFromQuote->invokeArgs($this->eventHandler, ['SKU-111']);
        $this->assertEquals($this->itemTransferListMock[0], $itemTransferMock);

        $itemTransferMock->expects($this->once())
            ->method('getIdProductAbstract');

        $itemTransferMock->expects($this->once())
            ->method('getUnitPrice');

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getQuantity')
            ->willReturn($this->itemTransferListMock[0]->getQuantity());

        $this->sessionHandlerMock->expects($this->once())
            ->method('removeProduct');

        $this->eventHandler->handle($this->requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureSkuMissing(): void
    {
        $this->requestMock->expects($this->atLeastOnce())
            ->method('get')
            ->will($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null, 'SKU_NOT_IN_QUOTE'],
            ]));

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn($this->itemTransferListMock);

        $this->sessionHandlerMock->expects($this->never())
            ->method('removeProduct');

        $methodGetProductFromQuote = static::getMethod('getProductFromQuote');
        $result = $methodGetProductFromQuote->invokeArgs($this->eventHandler, [
            $this->requestMock->get(EnhancedEcommerceConstants::PRODUCT_FIELD_SKU)
        ]);
        $this->assertNull($result);

        $this->eventHandler->handle($this->requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureProductNotInQuote()
    {
        /*$requestMock = $this->getRequestMock('TEST_SKU');
        $quoteTransferMock = $this->getQuoteTransfer();
        $cartClientMock = $this->getCartClient($quoteTransferMock);

        $requestMock->expects($this->once())
            ->method('get');

        $cartClientMock->expects($this->once())
            ->method('getQuote');

        $cartClientMock->expects($this->once())
            ->method('getQuote');

        $quoteTransferMock->expects($this->once())
            ->method('getItems');

        $this->sessionHandlerMock->expects($this->never())
            ->method('removeProduct');

        $removeProductControllerEventHandler = new RemoveProductControllerEventHandler(
            $this->sessionHandlerMock,
            $cartClientMock
        );

        $removeProductControllerEventHandler->handle($requestMock, 'xx_XX');*/
    }

    /**
     * @param string $name
     *
     * @return \ReflectionMethod
     */
    protected static function getMethod(string $name)
    {
        $class = new ReflectionClass(RemoveProductControllerEventHandler::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
