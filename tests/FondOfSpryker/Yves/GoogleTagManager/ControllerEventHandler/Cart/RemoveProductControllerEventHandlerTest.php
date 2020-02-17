<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\Request;

class RemoveProductControllerEventHandlerTest extends Unit
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $sessionHandlerMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->sessionHandlerMock = $this->getMockBuilder(EnhancedEcommerceSessionHandlerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return void
     */
    public function testHandleSuccess(): void
    {
        $requestMock = $this->getRequestMock('TEST_SKU');
        $itemTransferMock = $this->getItemTransferMock('TEST_SKU', 1);
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);

        $removeProductControllerEventHandler = new RemoveProductControllerEventHandler(
            $this->sessionHandlerMock,
            $cartClientMock
        );

        $requestMock->expects($this->once())
            ->method('get');

        $cartClientMock->expects($this->once())
            ->method('getQuote');

        $quoteTransferMock->expects($this->once())
            ->method('getItems');

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getSku');

        $itemTransferMock->expects($this->once())
            ->method('getIdProductAbstract');

        $itemTransferMock->expects($this->once())
            ->method('getQuantity');

        $itemTransferMock->expects($this->once())
            ->method('getUnitPrice');

        $this->sessionHandlerMock->expects($this->once())
            ->method('removeProduct');

        $removeProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureSkuMissing(): void
    {
        $requestMock = $this->getRequestMock();
        $itemTransferMock = $this->getItemTransferMock('TEST_SKU', 1);
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);

        $requestMock->expects($this->once())
            ->method('get');

        $cartClientMock->expects($this->never())
            ->method('getQuote');

        $removeProductControllerEventHandler = new RemoveProductControllerEventHandler(
            $this->sessionHandlerMock,
            $cartClientMock
        );

        $removeProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureProductNotInQuote()
    {
        $requestMock = $this->getRequestMock('TEST_SKU');
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

        $removeProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @param string|null $sku
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRequestMock(?string $sku = null)
    {
        $requestMock = $this->createMock(Request::class);
        if ($sku !== null) {
            $requestMock->method('get')
                ->will($this->returnValueMap([
                    [EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null, $sku],
                ]));
        }

        return $requestMock;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getItemTransferMock(string $sku, int $quantity)
    {
        $itemTransferMock = $this->getMockBuilder(ItemTransfer::class)
            ->setMethods(['getSku', 'getQuantity', 'getIdProductAbstract', 'getUnitPrice'])
            ->getMock();

        $itemTransferMock->method('getIdProductAbstract')->willReturn(666);
        $itemTransferMock->method('getSku')->willReturn($sku);
        $itemTransferMock->method('getQuantity')->willReturn($quantity);
        $itemTransferMock->method('getUnitPrice')->willReturn(1111);

        return $itemTransferMock;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer|null $itemTransferMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getQuoteTransfer(?ItemTransfer $itemTransferMock = null)
    {
        $quoteTransferMock = $this->createMock(QuoteTransfer::class);

        if ($itemTransferMock !== null) {
            $quoteTransferMock->method('getItems')
                ->willReturn([$itemTransferMock]);

            return $quoteTransferMock;
        }

        $quoteTransferMock->method('getItems')
            ->willReturn([]);

        return $quoteTransferMock;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransferMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCartClient(?QuoteTransfer $quoteTransferMock)
    {
        $cartClientMock = $this->createMock(GoogleTagManagerToCartClientInterface::class);

        if ($quoteTransferMock !== null) {
            $cartClientMock->method('getQuote')
                ->willReturn($quoteTransferMock);

            $this->assertEquals($quoteTransferMock, $cartClientMock->getQuote());
        }

        return $cartClientMock;
    }
}
