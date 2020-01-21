<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class SalePricePlugin extends AbstractPlugin implements ProductVariableBuilderPluginInterface
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
        $this->moneyPlugin = $this->getFactory()->createMoneyPlugin();
        $this->config = $this->getFactory()->getGoogleTagManagerConfig();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::FIELD_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return array
     */
    public function handle(ProductAbstractTransfer $product): array
    {
        $specialPrice = $this->getProductSpecialPrice($product);

        if ($specialPrice === null) {
            return [];
        }

        return [static::FIELD_NAME => $specialPrice];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return float|null
     */
    protected function getProductSpecialPrice(ProductAbstractTransfer $product): ?float
    {
        $this->getFactory()->createMoneyPlugin();

        $time = time();

        if (!array_key_exists($this->config->getSpecialPriceAttribute(), $product->getAttributes())) {
            return null;
        }

        $specialPriceAttr = (int)$product->getAttributes()[$this->config->getSpecialPriceAttribute()];
        $specialPrice = $this->moneyPlugin->convertIntegerToDecimal($specialPriceAttr);
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
