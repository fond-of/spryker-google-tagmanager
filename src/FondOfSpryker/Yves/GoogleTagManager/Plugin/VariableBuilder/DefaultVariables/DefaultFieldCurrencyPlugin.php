<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class DefaultFieldCurrencyPlugin extends AbstractPlugin implements DefaultFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer $googleTagManagerDefaultTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer
     */
    public function handle(
        GoogleTagManagerDefaultTransfer $googleTagManagerDefaultTransfer,
        array $params = []
    ): GoogleTagManagerDefaultTransfer {
        return $googleTagManagerDefaultTransfer->setCurrency($this->getFactory()->getStore()->getCurrencyIsoCode());
    }
}
