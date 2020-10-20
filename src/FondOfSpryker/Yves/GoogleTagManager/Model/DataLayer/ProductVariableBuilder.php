<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use FondOfSpryker\Client\TaxProductConnector\TaxProductConnectorClient;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Model\ProductDataLayerVariableBuilderInterface;
use Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class ProductVariableBuilder implements ProductDataLayerVariableBuilderInterface
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

    public const VARIABLE_BUILDER_NAME = 'product';

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
     * @return string
     */
    public function getName(): string
    {
        return static::VARIABLE_BUILDER_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $product
     *
     * @return array
     */
    public function getVariables(ProductAbstractTransfer $product): array
    {
        $gooleTagManagerProductDetailTransfer = $this->createGooleTagManagerProductDetailTransfer();

        foreach ($this->productVariableBuilderPlugins as $plugin) {
            $gooleTagManagerProductDetailTransfer = $plugin->handle(
                $gooleTagManagerProductDetailTransfer, $product
            );
        }

        return $gooleTagManagerProductDetailTransfer->toArray(true, true);

        return $this->executePlugins($product, $gooleTagManagerProductDetailTransfer->toArray(true, true));
    }

    /**
     * @return GooleTagManagerProductDetailTransfer
     */
    protected function createGooleTagManagerProductDetailTransfer(): GooleTagManagerProductDetailTransfer
    {
        return new GooleTagManagerProductDetailTransfer();
    }
}
