<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhancedEcommerceProductImpressions extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
    /**
     * @param \Twig_Environment $twig
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array|null $params
     *
     * @return string
     */
    public function handle(Twig_Environment $twig, Request $request, ?array $params = []): string
    {
        if (!isset($params['category']) || !isset($params['products'])) {
            return '';
        }

        $category = $params['category'];
        $products = $params['products'];
        $store = $this->getFactory()->getStore();
        $collection = [];
        $counter = 1;

        foreach ($products as $product) {
            $productViewTransfer = (new ProductViewTransfer())->fromArray($product, true);
            $productViewTransfer->setSku($product['abstract_sku']);
            $productViewTransfer->setPrice($product['price']);

            $enhancedEcommerceProductTransfer = $this->getFactory()
                ->getEnhancedEcommerceProductMapperPlugin()
                ->map($productViewTransfer);

            $collection[] = array_merge($enhancedEcommerceProductTransfer->toArray(), [
                'list' => $category['category_key'],
                'position' => $counter++,
            ]);
        }

        $enhancedEcommerceTransfer = new EnhancedEcommerceTransfer();
        $enhancedEcommerceTransfer->setEvent('genericEvent');
        $enhancedEcommerceTransfer->setEventCategory('ecommerce');
        $enhancedEcommerceTransfer->setEventAction('productImpressions');
        $enhancedEcommerceTransfer->setEventLabel('');
        $enhancedEcommerceTransfer->setEcommerce([
            'currencyCode' => $store->getCurrencyIsoCode(),
            'impressions' => $this->stripEmptyValuesFromProductsArray($collection),
        ]);

        return $twig->render($this->getTemplate(), [
            'data' => $enhancedEcommerceTransfer->toArray(),
        ]);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-impressions.twig';
    }

    /**
     * @param array $products
     *
     * @return array
     */
    protected function stripEmptyValuesFromProductsArray(array $products): array
    {
        foreach ($products as $index => $product) {
            foreach ($product as $key => $value) {
                if ($value !== 0 && !$value) {
                    unset($products[$index][$key]);
                }
            }
        }

        return $products;
    }
}
