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

class ChangeQuantityProductControllerEventHandlerTest extends Unit
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
     * @var \FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart\ChangeQuantityProductControllerEventHandler
     */
    protected $plugin;

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

        $this->plugin = new ChangeQuantityProductControllerEventHandler(
            $this->sessionHandlerMock,
            $this->cartClientMock
        );
    }

    /**
     * @return void
     */
    public function testGetMethodName(): void
    {
        $this->assertEquals(ChangeQuantityProductControllerEventHandler::METHOD_NAME, $this->plugin->getMethodName());
    }

    /**
     * @return void
     */
    public function testHandleSuccessIncreaseQuantity(): void
    {
        $this->requestMock->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null, 'SKU-111'],
                [EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY, null, 99],
            ]));

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn($this->itemTransferListMock);

        $this->sessionHandlerMock->expects($this->once())
            ->method('addProduct');

        $this->sessionHandlerMock->expects($this->never())
            ->method('removeProduct');

        $methodGetProductFromQuote = static::getMethod('getProductFromQuote');
        $itemTransferMock = $methodGetProductFromQuote->invokeArgs($this->plugin, ['SKU-111']);
        $this->assertEquals($this->itemTransferListMock[0], $itemTransferMock);

        $itemTransferMock->expects($this->once())
            ->method('getIdProductAbstract')
            ->willReturn(666);

        $itemTransferMock->expects($this->once())
            ->method('getUnitPrice')
            ->willReturn(1234);

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getQuantity')
            ->willReturn($this->itemTransferListMock[0]->getQuantity());

        $this->plugin->handle($this->requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleSuccessReduceQuantity(): void
    {
        $this->requestMock->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null, 'SKU-111'],
                [EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY, null, 1],
            ]));

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn($this->itemTransferListMock);

        $this->sessionHandlerMock->expects($this->never())
            ->method('addProduct');

        $this->sessionHandlerMock->expects($this->once())
            ->method('removeProduct');

        $methodGetProductFromQuote = static::getMethod('getProductFromQuote');
        $itemTransferMock = $methodGetProductFromQuote->invokeArgs($this->plugin, ['SKU-111']);
        $this->assertEquals($this->itemTransferListMock[0], $itemTransferMock);

        $itemTransferMock->expects($this->once())
            ->method('getIdProductAbstract')
            ->willReturn(666);

        $itemTransferMock->expects($this->once())
            ->method('getUnitPrice')
            ->willReturn(1234);

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getQuantity')
            ->willReturn($this->itemTransferListMock[0]->getQuantity());

        $this->plugin->handle($this->requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureMissingSku(): void
    {
        $this->requestMock->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY, null, 1],
            ]));

        $this->requestMock->expects($this->exactly(2))
            ->method('get');

        $this->cartClientMock->expects($this->never())
            ->willReturn($this->quoteTransferMock)
            ->method('getQuote');

        $this->plugin->handle($this->requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureMissingQuantity(): void
    {
        $this->requestMock->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null, 'SKU-111'],
            ]));

        $this->cartClientMock->expects($this->never())
            ->willReturn($this->quoteTransferMock)
            ->method('getQuote');

        $this->plugin->handle($this->requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureProductNotInQuote(): void
    {
        $this->requestMock->expects($this->exactly(2))
            ->method('get')
            ->will($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null, 'SKU_NOT_IN_QUOTE'],
                [EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY, null, 3],
            ]));

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn($this->itemTransferListMock);

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->sessionHandlerMock->expects($this->never())
            ->method('addProduct');

        $this->sessionHandlerMock->expects($this->never())
            ->method('removeProduct');

        $methodGetProductFromQuote = static::getMethod('getProductFromQuote');
        $result = $methodGetProductFromQuote->invokeArgs($this->plugin, ['SKU_NOT_IN_QUOTE']);
        $this->assertNull($result);

        $this->plugin->handle($this->requestMock, 'xx_XX');
    }

    /**
     * @param string $name
     *
     * @return \ReflectionMethod
     */
    protected static function getMethod(string $name)
    {
        $class = new ReflectionClass(ChangeQuantityProductControllerEventHandler::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
