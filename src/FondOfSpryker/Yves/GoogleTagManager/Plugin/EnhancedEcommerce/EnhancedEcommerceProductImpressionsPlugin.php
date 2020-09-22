<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Generated\Shared\Transfer\EnhancedEcommerceProductImpressionTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhancedEcommerceProductImpressionsPlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
    use LoggerTrait;

    public const ATTRIBUTE = 'attributes';
    public const PRICE = 'price';
    public const ABSTRACT_SKU = 'abstract_sku';
    public const ATTR_MODEL_UNTRANSLATED = 'model_untranslated';
    public const ATTR_STYLE_UNTRANSLATED = 'style_untranslated';

    /**
     * @param \Twig_Environment $twig
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array|null $params
     *
     * @return string
     * @throws
     */
    public function handle(Twig_Environment $twig, Request $request, ?array $params = []): string
    {
        $products = (isset($params['products'])) ? $params['products'] : [];
        $list = (isset($params['list'])) ? $params['list'] : 'category';

        return $twig->render($this->getTemplate(), [
            'data' => [
                $this->renderProductImpressions($products, $list)
            ],
        ]);
    }

    /**
     * @param array $products
     * @param string $list
     *
     * @return array
     */
    protected function renderProductImpressions(array $products, string $list): array
    {
        $productCount = 0;
        $productImpressions = [
            'ec_impressions' => [
                'currencyCode' => $this->getFactory()->getStore()->getCurrencyIsoCode(),
                'impressions' => []
            ]
        ];

        foreach ($products as $product) {
            if ($this->arrayKeyExistsInProduct(static::ATTRIBUTE, $product) === false) {
                continue;
            }

            if ($this->arrayKeyExistsInProduct(static::ABSTRACT_SKU, $product) === false) {
                continue;
            }

            if ($this->arrayKeyExistsInProduct(static::ATTR_MODEL_UNTRANSLATED, $product[static::ATTRIBUTE]) === false) {
                continue;
            }

            if ($this->arrayKeyExistsInProduct(static::ATTR_STYLE_UNTRANSLATED, $product[static::ATTRIBUTE]) === false) {
                continue;
            }

            $productCount++;

            $productImpressionTransfer = new EnhancedEcommerceProductImpressionTransfer();
            $productImpressionTransfer->setName($product[static::ATTRIBUTE][static::ATTR_MODEL_UNTRANSLATED]);
            $productImpressionTransfer->setId(\str_replace('Abstract-', '', $product[static::ABSTRACT_SKU]));
            $productImpressionTransfer->setVariant($product[static::ATTRIBUTE][static::ATTR_STYLE_UNTRANSLATED]);
            $productImpressionTransfer->setPrice($this->getFactory()->createMoneyPlugin()->convertIntegerToDecimal($product[static::PRICE]));
            $productImpressionTransfer->setList($list);
            $productImpressionTransfer->setPosition($productCount);

            $productImpressions['ec_impressions']['impressions'][] = [
                $productImpressionTransfer->toArray()
            ];
        }

        return $productImpressions;
    }

    /**
     * @param string $key
     * @param array $product
     *
     * @return bool
     */
    protected function arrayKeyExistsInProduct(string $key, array $product): bool
    {
        if (\array_key_exists($key, $product)) {
            return true;
        }

        $this->getLogger()->alert(sprintf('GoogleTagManager: Could not add product (%s) to product-impressions, index %s not exists',
            $product['id_product_abstract'],
            $key
        ));

        return false;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-default.twig';
    }
}
