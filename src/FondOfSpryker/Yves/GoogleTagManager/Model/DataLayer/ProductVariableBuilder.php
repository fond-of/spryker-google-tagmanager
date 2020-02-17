<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use FondOfSpryker\Client\TaxProductConnector\TaxProductConnectorClient;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class ProductVariableBuilder
{
    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected $moneyPlugin;

    /**
     * @var \FondOfSpryker\Client\TaxProductConnector\TaxProductConnectorClient
     */
    protected $taxProductConnectorClient;

    /**
     * @var array|\FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\VariableBuilderPluginInterface[]
     */
    protected $productVariableBuilderPlugins;

    /**
     * @param \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface $moneyPlugin
     * @param \FondOfSpryker\Client\TaxProductConnector\TaxProductConnectorClient $taxProductConnectorClient
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables\ProductVariableBuilderPluginInterface[] $productVariableBuilderPlugins
     */
    public function __construct(
        MoneyPluginInterface $moneyPlugin,
        TaxProductConnectorClient $taxProductConnectorClient,
        array $productVariableBuilderPlugins = []
    ) {
        $this->moneyPlugin = $moneyPlugin;
        $this->taxProductConnectorClient = $taxProductConnectorClient;
        $this->productVariableBuilderPlugins = $productVariableBuilderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $product
     *
     * @return array
     */
    public function getVariables(ProductAbstractTransfer $product): array
    {
        $price = $this->moneyPlugin->convertIntegerToDecimal($product->getPrice());
        $priceExcludingTax = $this->moneyPlugin->convertIntegerToDecimal(
            $this->taxProductConnectorClient->getNetPriceForProduct($product)->getNetPrice()
        );

        $gooleTagManagerProductDetailTransfer = (new GooleTagManagerProductDetailTransfer())
            ->setProductId($product->getIdProductAbstract())
            ->setName($this->getProductName($product))
            ->setSku($product->getSku())
            ->setProductPrice($price)
            ->setProductPriceExcludingTax($priceExcludingTax)
            ->setProductTax($this->getProductTax($product))
            ->setProductTaxRate($product->getTaxRate());

        return $this->executePlugins($product, $gooleTagManagerProductDetailTransfer->toArray());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return float
     */
    protected function getProductTax(ProductAbstractTransfer $product): float
    {
        $productAbstract = $this->taxProductConnectorClient->getTaxAmountForProduct($product);
        if ($productAbstract->getTaxAmount() > 0) {
            return $this->moneyPlugin->convertIntegerToDecimal(
                $productAbstract->getTaxAmount()
            );
        }

        return 0;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return string
     */
    protected function getProductName(ProductAbstractTransfer $product): string
    {
        if (!array_key_exists(GoogleTagManagerConstants::NAME_UNTRANSLATED, $product->getAttributes())) {
            return $product->getName();
        }

        if (!$product->getAttributes()[GoogleTagManagerConstants::NAME_UNTRANSLATED]) {
            return $product->getName();
        }

        return $product->getAttributes()[GoogleTagManagerConstants::NAME_UNTRANSLATED];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     * @param array $variables
     *
     * @return array
     */
    protected function executePlugins(ProductAbstractTransfer $product, array $variables): array
    {
        foreach ($this->productVariableBuilderPlugins as $plugin) {
            $variables = array_merge($variables, $plugin->handle($product));
        }

        return $variables;
    }
}
