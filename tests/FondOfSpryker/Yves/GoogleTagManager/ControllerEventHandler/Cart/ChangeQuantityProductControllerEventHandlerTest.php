<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\Request;

class ChangeQuantityProductControllerEventHandlerTest extends Unit
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $sessionHandlerMock;

    protected function _before(): void
    {
        $this->sessionHandlerMock = $this->getMockBuilder(EnhancedEcommerceSessionHandlerInterface::class)
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
    }

    /**
     * @return void
     */
    public function testHandleSuccessIncreaseQuantity(): void
    {
        $requestMock = $this->getRequestMock('TEST_SKU', 3);
        $itemTransferMock = $this->getItemTransferMock('TEST_SKU', 1);
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);

        $addProductControllerEventHandler = new ChangeQuantityProductControllerEventHandler(
            $this->sessionHandlerMock,
            $cartClientMock
        );

        $requestMock->expects($this->exactly(2))
            ->method('get');

        $cartClientMock->expects($this->once())
            ->method('getQuote');

        $itemTransferMock->expects($this->once())
            ->method('getSku');

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getQuantity');

        $this->sessionHandlerMock->expects($this->once())
            ->method('addProduct');

        $this->sessionHandlerMock->expects($this->never())
            ->method('removeProduct');

        $addProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleSuccessReduceQuantity(): void
    {
        $requestMock = $this->getRequestMock('TEST_SKU', 1);
        $itemTransferMock = $this->getItemTransferMock('TEST_SKU', 3);
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);

        $requestMock->expects($this->exactly(2))
            ->method('get');

        $cartClientMock->expects($this->once())
            ->method('getQuote');

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getSku');

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getQuantity');

        $this->sessionHandlerMock->expects($this->never())
            ->method('addProduct');

        $this->sessionHandlerMock->expects($this->once())
            ->method('removeProduct');

        $addProductControllerEventHandler = new ChangeQuantityProductControllerEventHandler(
            $this->sessionHandlerMock,
            $cartClientMock
        );

        $addProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureMissingSku(): void
    {
        $requestMock = $this->getRequestMock('', 1);
        $itemTransferMock = $this->getItemTransferMock('TEST_SKU', 3);
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);

        $requestMock->expects($this->exactly(2))
            ->method('get');

        $cartClientMock->expects($this->never())
            ->method('getQuote');

        $addProductControllerEventHandler = new ChangeQuantityProductControllerEventHandler(
            $this->sessionHandlerMock,
            $cartClientMock
        );

        $addProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureMissingQuantity(): void
    {
        $requestMock = $this->getRequestMock('TEST_SKU', 0);
        $itemTransferMock = $this->getItemTransferMock('TEST_SKU', 3);
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);

        $requestMock->expects($this->exactly(2))
            ->method('get');

        $cartClientMock->expects($this->never())
            ->method('getQuote');

        $addProductControllerEventHandler = new ChangeQuantityProductControllerEventHandler(
            $this->sessionHandlerMock,
            $cartClientMock
        );

        $addProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureProductNotInQuote(): void
    {
        $requestMock = $this->getRequestMock('TEST_SKU', 3);
        $quoteTransferMock = $this->getQuoteTransfer(null);
        $cartClientMock = $this->getCartClient($quoteTransferMock);

        $requestMock->expects($this->exactly(2))
            ->method('get');

        $quoteTransferMock->expects($this->once())
            ->method('getItems');

        $cartClientMock->expects($this->once())
            ->method('getQuote');

        $this->sessionHandlerMock->expects($this->never())
            ->method('addProduct');

        $this->sessionHandlerMock->expects($this->never())
            ->method('removeProduct');

        $addProductControllerEventHandler = new ChangeQuantityProductControllerEventHandler(
            $this->sessionHandlerMock,
            $cartClientMock
        );

        $addProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function getRequestMock(string $sku, int $quantity)
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('get')
            ->will($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null, $sku],
                [EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY, null, $quantity],
            ]));

        $this->assertEquals($sku, $requestMock->get(EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null));
        $this->assertEquals($quantity, $requestMock->get(EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY, null));

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
            ->setMethods(['getSku', 'getQuantity'])
            ->getMock();

        $itemTransferMock->method('getSku')->willReturn($sku);
        $itemTransferMock->method('getQuantity')->willReturn($quantity);

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
}
