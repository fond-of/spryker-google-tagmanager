<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;

class EnhancedEcommerceSessionHandlerTest extends Unit
{
    /**
     * @return void
     */
    public function testGetChangeProductQuantityEventArraySuccessWithoutRemovingDataFromSession(): void
    {
        $sessionClientMock = $this->getSessionClientMock();
        $cartClientMock = $this->getCartClientMock();
        $productMapperMock = $this->getProductMapperMock();

        $sessionClientMock->method('get')
            ->willReturn([
                [EnhancedEcommerceConstants::SESSION_REMOVED_CHANGED_QUANTITY, null, []]
            ]);

        $enhancedEcommerceSessionHandler = new EnhancedEcommerceSessionHandler(
            $sessionClientMock,
            $cartClientMock,
            $productMapperMock
        );

        $sessionClientMock->expects($this->exactly(2))->method('get');
        $sessionClientMock->expects($this->never())->method('remove');

        $enhancedEcommerceSessionHandler->getChangeProductQuantityEventArray();
    }

    /**
     * @return void
     */
    public function testGetChangeProductQuantityEventArraySuccessRemovingDataFromSession(): void
    {
        $sessionClientMock = $this->getSessionClientMock();
        $cartClientMock = $this->getCartClientMock();
        $productMapperMock = $this->getProductMapperMock();

        $sessionClientMock->method('get')
            ->willReturn([
                [EnhancedEcommerceConstants::SESSION_REMOVED_CHANGED_QUANTITY, null, []]
            ]);

        $enhancedEcommerceSessionHandler = new EnhancedEcommerceSessionHandler(
            $sessionClientMock,
            $cartClientMock,
            $productMapperMock
        );

        $sessionClientMock->expects($this->exactly(2))->method('get');
        $sessionClientMock->expects($this->once())->method('remove');

        $enhancedEcommerceSessionHandler->getChangeProductQuantityEventArray(true);
    }

    /**
     * @return void
     */
    public function testGetChangeProductQuantityEventArrayFailureNoDataInSession(): void
    {
        $sessionClientMock = $this->getSessionClientMock();
        $cartClientMock = $this->getCartClientMock();
        $productMapperMock = $this->getProductMapperMock();

        $enhancedEcommerceSessionHandler = new EnhancedEcommerceSessionHandler(
            $sessionClientMock,
            $cartClientMock,
            $productMapperMock
        );

        $sessionClientMock->expects($this->once())->method('get');

        $enhancedEcommerceSessionHandler->getChangeProductQuantityEventArray();
    }

    public function testGetAddedProductsSuccess(): void
    {
        $sessionClientMock = $this->getSessionClientMock();
        $cartClientMock = $this->getCartClientMock();
        $productMapperMock = $this->getProductMapperMock();

        $enhancedEcommerceSessionHandler = new EnhancedEcommerceSessionHandler(
            $sessionClientMock,
            $cartClientMock,
            $productMapperMock
        );

        $sessionClientMock->expects($this->once())->method('get');

        $enhancedEcommerceSessionHandler->getAddedProducts();
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getProductMapperMock()
    {
        $productMapperMock = $this->createMock(EnhancedEcommerceProductMapperInterface::class);

        $productMapperMock->method('map');

        return $productMapperMock;
    }

    /**
     * @param QuoteTransfer|null $quoteTransferMock
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCartClientMock()
    {
        $cartClientMock = $this->createMock(GoogleTagManagerToCartClientInterface::class);

        return $cartClientMock;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getQuoteTransferMock(?ItemTransfer $itemTransfer)
    {
        $quoteTransferMock = $this->createMock(QuoteTransfer::class);

        if ($itemTransfer !== null) {
            $quoteTransferMock->method('getItems')
                ->willReturn([$itemTransfer]);

            return $quoteTransferMock;
        }

        $quoteTransferMock->method('getItems')
            ->willReturn([]);

        return $quoteTransferMock;
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getItemTransferMock()
    {
        $itemTransferMock = $this->getMockBuilder(ItemTransfer::class)
            ->setMethods(['getIdProductAbstract', 'getUnitPrice', 'getSku'])
            ->getMock();

        $itemTransferMock->method('getSku')->willReturn('TEST_SKU');
        $itemTransferMock->method('getIdProductAbstract')->willReturn(666);
        $itemTransferMock->method('getUnitPrice')->willReturn(1111);

        return $itemTransferMock;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSessionClientMock()
    {
        $sessionClientMock = $this->getMockBuilder(GoogleTagManagerToSessionClientInterface::class)
            ->setMethods(['getId', 'get', 'set', 'remove'])
            ->disableOriginalConstructor()
            ->getMock();

        return $sessionClientMock;
    }

}
