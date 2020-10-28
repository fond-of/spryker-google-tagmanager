<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\ProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()
 */
class ProductFieldSalePricePlugin extends AbstractPlugin implements ProductFieldPluginInterface
{
    public const FIELD_NAME = 'sale_price';

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig
     */
    protected $config;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    public function __construct()
    {
        $this->moneyPlugin = $this->getFactory()->getMoneyPlugin();
        $this->config = $this->getConfig();
    }

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer $googleTagManagerProductDetailTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer
     */
    public function handle(
        GoogleTagManagerProductDetailTransfer $googleTagManagerProductDetailTransfer,
        ProductAbstractTransfer $product,
        array $params = []
    ): GoogleTagManagerProductDetailTransfer {
        $specialPrice = $this->getProductSpecialPrice($product);

        return $googleTagManagerProductDetailTransfer->setSalePrice($specialPrice);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return float|null
     */
    protected function getProductSpecialPrice(ProductAbstractTransfer $product): ?float
    {
        if (!array_key_exists($this->config->getSpecialPriceAttribute(), $product->getAttributes())) {
            return null;
        }

        $time = time();
        $specialPriceAttr = (int)$product->getAttributes()[$this->getConfig()->getSpecialPriceAttribute()];
        $specialPrice = $this->getFactory()
            ->getMoneyPlugin()
            ->convertIntegerToDecimal($specialPriceAttr);

        $specialPriceFrom = $product->getAttributes()[$this->config->getSpecialPriceFromAttribute()];
        $specialPriceTo = $product->getAttributes()[$this->config->getSpecialPriceToAttribute()];

        if (!$specialPrice) {
            return null;
        }

        if ($specialPriceFrom === null) {
            return null;
        }

        if (($time >= strtotime($specialPriceFrom)) && ($specialPriceTo === null || $time <= strtotime($specialPriceTo))) {
            return $specialPrice;
        }

        return null;
    }
}
