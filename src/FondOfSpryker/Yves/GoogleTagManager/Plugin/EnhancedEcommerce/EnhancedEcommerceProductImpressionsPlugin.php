<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Generated\Shared\Transfer\EnhancedEcommerceProductImpressionTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhancedEcommerceProductImpressionsPlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
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
        $productImpressions = [
            'ec_impressions' => [
                'currencyCode' => $this->getFactory()->getStore()->getCurrencyIsoCode(),
                'impressions' => []
            ]
        ];

        $index = 0;

        foreach ($products as $product) {
            $index++;

            $productImpressionTransfer = new EnhancedEcommerceProductImpressionTransfer();
            $productImpressionTransfer->setName($product[static::ATTRIBUTE][static::ATTR_MODEL_UNTRANSLATED]);
            $productImpressionTransfer->setId(\str_replace('Abstract-', '', $product[static::ABSTRACT_SKU]));
            $productImpressionTransfer->setVariant($product[static::ATTRIBUTE][static::ATTR_STYLE_UNTRANSLATED]);
            $productImpressionTransfer->setPrice($this->getFactory()->createMoneyPlugin()->convertIntegerToDecimal($product[static::PRICE]));
            $productImpressionTransfer->setList($list);
            $productImpressionTransfer->setPosition($index);

            $productImpressions['ec_impressions']['impressions'][] = [
                $productImpressionTransfer->toArray()
            ];
        }

        return $productImpressions;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-default.twig';
    }
}
