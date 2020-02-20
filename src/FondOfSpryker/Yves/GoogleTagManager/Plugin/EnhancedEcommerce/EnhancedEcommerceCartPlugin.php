<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhancedEcommerceCartPlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-default.twig';
    }

    /**
     * @param \Twig_Environment $twig
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array|null $params
     *
     * @throws
     *
     * @return string
     */
    public function handle(Twig_Environment $twig, Request $request, ?array $params = []): string
    {
        return $twig->render($this->getTemplate(), [
            'data' => [
                $this->getAddedProductsEvent(),
                $this->getRemovedProductsEvent(),
            ],
        ]);
    }

    /**
     * @return array
     */
    protected function getAddedProductsEvent(): array
    {
        $addedProductsData = $this->getFactory()
            ->createEnhancedEcommerceSessionHandler()
            ->getAddedProducts(true);

        if (\count($addedProductsData) === 0) {
            return [];
        }

        $addedProducts = $this->getFactory()
            ->createEnhancedEcommerceProductArrayBuilder()
            ->handle($addedProductsData);

        $enhancedEcommerceTransfer = new EnhancedEcommerceTransfer();
        $enhancedEcommerceTransfer->setEvent(EnhancedEcommerceConstants::EVENT_PRODUCT_ADD);
        $enhancedEcommerceTransfer->setEcommerce([
            'add' => [
                'actionField' => [],
                'products' => $addedProducts,
            ],
        ]);

        return $enhancedEcommerceTransfer->toArray();
    }

    /**
     * @return array
     */
    protected function getRemovedProductsEvent(): array
    {
        $removedProducts = $this->getRemovedProducts();

        if (\count($removedProducts) === 0) {
            return [];
        }

        $enhancedEcommerceTransfer = new EnhancedEcommerceTransfer();
        $enhancedEcommerceTransfer->setEvent(EnhancedEcommerceConstants::EVENT_PRODUCT_REMOVE);
        $enhancedEcommerceTransfer->setEcommerce([
            'remove' => [
                'actionField' => [],
                'products' => $removedProducts,
            ],
        ]);

        return $enhancedEcommerceTransfer->toArray();
    }

    /**
     * @return array
     */
    protected function getRemovedProducts(): array
    {
        $removedProducts = $this->getFactory()
            ->createEnhancedEcommerceSessionHandler()
            ->getRemovedProducts(true);

        if (\count($removedProducts) === 0) {
            return [];
        }

        $products = [];

        foreach ($removedProducts as $productArray) {
            if (!isset($productArray[EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID])) {
                continue;
            }

            if (!isset($productArray[EnhancedEcommerceConstants::PRODUCT_FIELD_SKU])) {
                continue;
            }

            if (!isset($productArray[EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY])) {
                continue;
            }

            if (!isset($productArray[EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE])) {
                continue;
            }

            $productAbstractData = $this->getFactory()
                ->getProductStorageClient()
                ->findProductAbstractStorageData($productArray[EnhancedEcommerceConstants::PRODUCT_FIELD_PRODUCT_ABSTRACT_ID], 'en_US');

            $productViewTransfer = (new ProductViewTransfer())->fromArray($productAbstractData, true);
            $productViewTransfer->setPrice($productArray[EnhancedEcommerceConstants::PRODUCT_FIELD_PRICE]);
            $productViewTransfer->setQuantity($productArray[EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY]);

            $products[] = $this->getFactory()
                ->createEnhancedEcommerceProductMapperPlugin()
                ->map($productViewTransfer)->toArray();
        }

        return $products;
    }
}
