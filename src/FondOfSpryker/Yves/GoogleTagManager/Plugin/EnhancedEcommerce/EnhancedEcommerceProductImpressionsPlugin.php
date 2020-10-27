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
     */
    public function handle(Twig_Environment $twig, Request $request, ?array $params = []): string
    {
        $products = (isset($params['products'])) ? $params['products'] : [];
        $list = (isset($params['list'])) ? $params['list'] : 'category';

        return $twig->render($this->getTemplate(), [
            'data' => [
                $this->renderProductImpressions($products, $list),
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
                'impressions' => [],
            ],
        ];

        foreach ($products as $product) {
            if ($this->validateProductArray($product) === false) {
                continue;
            }

            $productCount++;
            $productImpressions['ec_impressions']['impressions'][] = $this->createEnhancedEcommerceProductImpressionTransfer($product, $list, $productCount)->toArray();
        }

        return $productImpressions;
    }

    /**
     * @param array $product
     *
     * @return bool
     */
    protected function validateProductArray(array $product): bool
    {
        if ($this->arrayKeyExistsInProduct(static::ATTRIBUTE, $product) === false) {
            return false;
        }

        if ($this->arrayKeyExistsInProduct(static::ABSTRACT_SKU, $product) === false) {
            return false;
        }

        if ($this->arrayKeyExistsInProduct(static::ATTR_MODEL_UNTRANSLATED, $product[static::ATTRIBUTE]) === false) {
            return false;
        }

        if ($this->arrayKeyExistsInProduct(static::ATTR_STYLE_UNTRANSLATED, $product[static::ATTRIBUTE]) === false) {
            return false;
        }

        return true;
    }

    /**
     * @param array $product
     * @param string $listType
     * @param int $productCount
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductImpressionTransfer
     */
    protected function createEnhancedEcommerceProductImpressionTransfer(
        array $product,
        string $listType,
        int $productCount
    ): EnhancedEcommerceProductImpressionTransfer {
        $productImpressionTransfer = (new EnhancedEcommerceProductImpressionTransfer())
            ->setName($product[static::ATTRIBUTE][static::ATTR_MODEL_UNTRANSLATED])
            ->setId(str_replace('Abstract-', '', $product[static::ABSTRACT_SKU]))
            ->setVariant($product[static::ATTRIBUTE][static::ATTR_STYLE_UNTRANSLATED])
            ->setPrice($this->getFactory()->createMoneyPlugin()->convertIntegerToDecimal($product[static::PRICE]))
            ->setList($listType);
        $productImpressionTransfer->setPosition($productCount);

        return $productImpressionTransfer;
    }

    /**
     * @param string $key
     * @param array $product
     *
     * @return bool
     */
    protected function arrayKeyExistsInProduct(string $key, array $product): bool
    {
        if (array_key_exists($key, $product)) {
            return true;
        }

        $this->getLogger()->info(sprintf(
            'GoogleTagManager: Could not add product to product-impressions, index %s not exists',
            $key
        ), ['product' => json_encode($product)]);

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
