<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;

class ProductArrayModel implements ProductModelBuilderInterface
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface
     */
    protected $productMapper;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface $cartClient
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface $storageClient
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductResourceAliasStorageClientBridge $aliasStorageClient
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface $productMapper
     */
    public function __construct(
        GoogleTagManagerToCartClientInterface $cartClient,
        GoogleTagManagerToProductStorageClientInterface $storageClient,
        EnhancedEcommerceProductMapperInterface $productMapper
    ) {
        $this->productMapper = $productMapper;
        $this->storageClient = $storageClient;
        $this->cartClient = $cartClient;
    }

    /**
     * @param array $productsArray
     *
     * @return array
     */
    public function handle(array $productsArray): array
    {
        $products = [];

        foreach ($productsArray as $product) {
            if (!isset($product[EnhancedEcommerceConstants::PRODUCT_FIELD_SKU])) {
                continue;
            }

            if (!isset($product[EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY])) {
                continue;
            }

            $itemTransfer = $this->getItemTransferFromQuote($product[EnhancedEcommerceConstants::PRODUCT_FIELD_SKU]);

            if ($itemTransfer === null) {
                continue;
            }

            if (!isset($product[EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID])) {
                $product[EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID] = $itemTransfer->getIdProductAbstract();
            }

            if (!isset($product[EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE])) {
                $product[EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE] = $itemTransfer->getUnitPrice();
            }

            $productDataAbstract = $this->storageClient
                ->findProductAbstractStorageData($product[EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID], 'en_US');

            $productViewTransfer = (new ProductViewTransfer())->fromArray($productDataAbstract, true);
            $productViewTransfer->setPrice($product[EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE]);
            $productViewTransfer->setQuantity($product[EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY]);

            $products[] = $this->productMapper->map($productViewTransfer)->toArray();
        }

        return $products;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function getItemTransferFromQuote(string $sku): ?ItemTransfer
    {
        $quoteTransfer = $this->cartClient
            ->getQuote();

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $sku) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
