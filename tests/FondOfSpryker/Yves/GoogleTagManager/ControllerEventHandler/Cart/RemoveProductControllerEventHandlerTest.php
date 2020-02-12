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
    protected function _before(): void
    {

    }

    public function testHandleSuccess(): void
    {
        $sessionHandlerMock = $this->getSessionHandler();
        $requestMock = $this->getRequestMock('TEST_SKU');
        $itemTransferMock = $this->getItemTransferMock('TEST_SKU', 1);
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);

        $removeProductControllerEventHandler = new RemoveProductControllerEventHandler(
            $sessionHandlerMock, $cartClientMock
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

        $removeProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @param string $sku
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getRequestMock(string $sku)
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('get')
            ->will($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null, $sku],
            ]));

        $this->assertEquals($sku, $requestMock->get(EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null));

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
     * @param ItemTransfer|null $itemTransferMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getQuoteTransfer(?ItemTransfer $itemTransferMock)
    {
        $quoteTransferMock = $this->createMock(QuoteTransfer::class);

        if ($itemTransferMock !== null) {
            $quoteTransferMock->method('getItems')
                ->willReturn([$itemTransferMock]);

            $this->assertIsArray($quoteTransferMock->getItems());

            return $quoteTransferMock;
        }

        $quoteTransferMock->method('getItems')
            ->willReturn([]);

        return $quoteTransferMock;
    }

    /**
     * @param QuoteTransfer|null $quoteTransferMock
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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getSessionHandler()
    {
        $sessionHandlerMock = $this->getMockBuilder(EnhancedEcommerceSessionHandlerInterface::class)
            ->setMethods([
                'getAddedProducts',
                'getRemovedProducts',
                'getChangeProductQuantityEventArray',
                'addProduct',
                'changeProductQuantity',
                'removeProduct'
            ])
            ->disableOriginalConstructor()
            ->getMock();

        return $sessionHandlerMock;
    }
}
