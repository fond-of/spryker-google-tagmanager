<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultFieldVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerDefaultTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class DefaultFieldStoreNamePlugin extends AbstractPlugin implements DefaultFieldVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerDefaultTransfer $gooleTagManagerDefaultTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerDefaultTransfer
     */
    public function handle(
        GooleTagManagerDefaultTransfer $gooleTagManagerDefaultTransfer,
        array $params = []
    ): GooleTagManagerDefaultTransfer {
        return $gooleTagManagerDefaultTransfer->setStore($this->getFactory()->getStore()->getStoreName());
    }
}
