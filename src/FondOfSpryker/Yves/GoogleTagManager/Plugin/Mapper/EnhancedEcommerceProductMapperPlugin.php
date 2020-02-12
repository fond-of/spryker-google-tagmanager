<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhancedEcommerceProductMapperPlugin extends AbstractPlugin implements EnhancedEcommerceProductMapperInterface
{
    /**
     * @var array|\FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\ProductFieldMapperPluginInterface[]
     */
    protected $productFieldMapperPlugin;

    /**
     * @var \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    protected $enhancedEcommerceProductTransfer;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\Mapper\EnhancedEcommerceProductMapper\ProductFieldMapperPluginInterface[] $fieldProductMapPlugins
     */
    public function __construct(array $fieldProductMapPlugins)
    {
        $this->enhancedEcommerceProductTransfer = new EnhancedEcommerceProductTransfer();
        $this->productFieldMapperPlugin = $fieldProductMapPlugins;
    }

    /**
     * @param array $product
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    public function map(ProductViewTransfer $productViewTransfer): EnhancedEcommerceProductTransfer
    {
        $this->executePlugins($productViewTransfer);

        return $this->enhancedEcommerceProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function executePlugins(ProductViewTransfer $productViewTransfer): void
    {
        foreach ($this->productFieldMapperPlugin as $plugin) {
            $plugin->map($productViewTransfer, $this->enhancedEcommerceProductTransfer);
        }
    }
}
