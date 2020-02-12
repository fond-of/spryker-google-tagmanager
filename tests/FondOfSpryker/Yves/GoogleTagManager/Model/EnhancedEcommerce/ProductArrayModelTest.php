<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use FondOfSpryker\Yves\GoogleTagManager\Model\EnhancedEcommerce\ProductArrayModel;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class ProductArrayModelTest extends Unit
{
    /**
     * @return void
     */
    public function testHandleSuccessWithCompleteProductArray(): void
    {
        $itemTransferMock = $this->getItemTransferMock();
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);
        $storageClientMock = $this->getStorageClient();
        $productMapperMock = $this->getProductMapper();

        $productArrayModel = new ProductArrayModel(
            $cartClientMock,
            $storageClientMock,
            $productMapperMock
        );

        $productsArray = [
            [
                EnhancedEcommerceConstants::PRODUCT_FIELD_SKU => 'TEST_SKU',
                EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY => 11,
                EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID => 666,
                EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE => 1111,
            ]
        ];

        $cartClientMock->expects($this->once())
            ->method('getQuote');

        $quoteTransferMock->expects($this->once())
            ->method('getItems');

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getSku');

        $itemTransferMock->expects($this->never())
            ->method('getIdProductAbstract');

        $itemTransferMock->expects($this->never())
            ->method('getUnitPrice');

        $productMapperMock->expects($this->once())
            ->method('map');

        $productArrayModel->handle($productsArray);
    }

    /**
     * @return void
     */
    public function testHandleSuccessMissingAbstractIdInProductArray(): void
    {
        $itemTransferMock = $this->getItemTransferMock();
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);
        $storageClientMock = $this->getStorageClient();
        $productMapperMock = $this->getProductMapper();

        $productArrayModel = new ProductArrayModel(
            $cartClientMock,
            $storageClientMock,
            $productMapperMock
        );

        $productsArray = [
            [
                EnhancedEcommerceConstants::PRODUCT_FIELD_SKU => 'TEST_SKU',
                EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY => 11,
                // EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID => 666,
                EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE => 1111,
            ]
        ];

        $cartClientMock->expects($this->once())
            ->method('getQuote');

        $quoteTransferMock->expects($this->once())
            ->method('getItems');

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getSku');

        $itemTransferMock->expects($this->once())
            ->method('getIdProductAbstract');

        $itemTransferMock->expects($this->never())
            ->method('getUnitPrice');

        $productMapperMock->expects($this->once())
            ->method('map');

        $productArrayModel->handle($productsArray);
    }

    /**
     * @return void
     */
    public function testHandleSuccessMissingPriceInProductArray(): void
    {
        $itemTransferMock = $this->getItemTransferMock();
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);
        $storageClientMock = $this->getStorageClient();
        $productMapperMock = $this->getProductMapper();

        $productArrayModel = new ProductArrayModel(
            $cartClientMock,
            $storageClientMock,
            $productMapperMock
        );

        $productsArray = [
            [
                EnhancedEcommerceConstants::PRODUCT_FIELD_SKU => 'TEST_SKU',
                EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY => 11,
                EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID => 666,
                // EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE => 1111,
            ]
        ];

        $cartClientMock->expects($this->once())
            ->method('getQuote');

        $quoteTransferMock->expects($this->once())
            ->method('getItems');

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getSku');

        $itemTransferMock->expects($this->never())
            ->method('getIdProductAbstract');

        $itemTransferMock->expects($this->once())
            ->method('getUnitPrice');

        $productMapperMock->expects($this->once())
            ->method('map');

        $productArrayModel->handle($productsArray);
    }

    /**
     * @return void
     */
    public function testHandleSuccessMissingProductIdAndPriceInProductArray(): void
    {
        $itemTransferMock = $this->getItemTransferMock();
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);
        $storageClientMock = $this->getStorageClient();
        $productMapperMock = $this->getProductMapper();

        $productArrayModel = new ProductArrayModel(
            $cartClientMock,
            $storageClientMock,
            $productMapperMock
        );

        $productsArray = [
            [
                EnhancedEcommerceConstants::PRODUCT_FIELD_SKU => 'TEST_SKU',
                EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY => 11,
                // EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID => 666,
                // EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE => 1111,
            ]
        ];

        $cartClientMock->expects($this->once())
            ->method('getQuote');

        $quoteTransferMock->expects($this->once())
            ->method('getItems');

        $itemTransferMock->expects($this->atLeastOnce())
            ->method('getSku');

        $itemTransferMock->expects($this->once())
            ->method('getIdProductAbstract');

        $itemTransferMock->expects($this->once())
            ->method('getUnitPrice');

        $productMapperMock->expects($this->once())
            ->method('map');

        $productArrayModel->handle($productsArray);
    }

    /**
     * @return void
     */
    public function testHandleFailureMissingSkuInProductArry(): void
    {
        $itemTransferMock = $this->getItemTransferMock();
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);
        $storageClientMock = $this->getStorageClient();
        $productMapperMock = $this->getProductMapper();

        $productArrayModel = new ProductArrayModel(
            $cartClientMock,
            $storageClientMock,
            $productMapperMock
        );

        $productsArray = [
            [
                // EnhancedEcommerceConstants::PRODUCT_FIELD_SKU => 'TEST_SKU',
                EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY => 11,
                EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID => 666,
                EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE => 1111,
            ]
        ];

        $cartClientMock->expects($this->never())
            ->method('getQuote');

        $productArrayModel->handle($productsArray);
    }

    /**
     * @return void
     */
    public function testHandleFailureMissingQuantityInProductArry(): void
    {
        $itemTransferMock = $this->getItemTransferMock();
        $quoteTransferMock = $this->getQuoteTransfer($itemTransferMock);
        $cartClientMock = $this->getCartClient($quoteTransferMock);
        $storageClientMock = $this->getStorageClient();
        $productMapperMock = $this->getProductMapper();

        $productArrayModel = new ProductArrayModel(
            $cartClientMock,
            $storageClientMock,
            $productMapperMock
        );

        $productsArray = [
            [
                EnhancedEcommerceConstants::PRODUCT_FIELD_SKU => 'TEST_SKU',
                // EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY => 11,
                EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID => 666,
                EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE => 1111,
            ]
        ];

        $cartClientMock->expects($this->never())
            ->method('getQuote');

        $productArrayModel->handle($productsArray);
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getProductMapper()
    {
        $productMapperMock = $this->createMock(EnhancedEcommerceProductMapperInterface::class);

        $productMapperMock->method('map');

        return $productMapperMock;
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getStorageClient()
    {
        $returnArray = [
            'id_product_abstract' => 53,
            'attributes' => [],
            'name' => "Affenzahn Small Friend Tiger",
            'sku' => "Abstract-AFZ-FAS-004-001",
            'url' => "/en/small-friend-tiger",
            'description' => "",
            'meta_title' => "",
            'meta_keywords' => "",
            'meta_description' => "",
            'super_attributes_definition' => [
                "model",
                "model_key",
            ],
            'attribute_map' => [
                'attribute_variants' => [],
                'super_attributes' => [
                    'model' => [
                        'Small Friend'
                    ],
                    'product_concrete_ids' => [
                        'AFZ-FAS-004-001' => 54
                    ]
                ]
            ]
        ];

        $storageClientMock = $this->createMock(GoogleTagManagerToProductStorageClientInterface::class);
        $storageClientMock->method('findProductAbstractStorageData')
            ->with(666, 'en_US')
            ->willReturn($returnArray);

        return $storageClientMock;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductViewTransfer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getProductViewTransfer()
    {
        $productViewTransferMock = $this->getMockBuilder(ProductViewTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['setPrice', 'setQuantity'])
            ->getMock();

        return $productViewTransferMock;
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
     * @return \Generated\Shared\Transfer\QuoteTransfer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getQuoteTransfer(?ItemTransfer $itemTransfer)
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
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEnhancedEcommerceProductTransferMock()
    {
        $enhancedEcommerceProductTransferMock = $this->createMock(EnhancedEcommerceProductTransfer::class);

        return $enhancedEcommerceProductTransferMock;
    }

    /**
     * @param QuoteTransfer|null $quoteTransferMock
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getCartClient(?QuoteTransfer $quoteTransferMock)
    {
        $cartClientMock = $this->createMock(GoogleTagManagerToCartClientInterface::class);

        if ($quoteTransferMock !== null) {
            $cartClientMock->method('getQuote')
                ->willReturn($quoteTransferMock);
        }

        return $cartClientMock;
    }
}
