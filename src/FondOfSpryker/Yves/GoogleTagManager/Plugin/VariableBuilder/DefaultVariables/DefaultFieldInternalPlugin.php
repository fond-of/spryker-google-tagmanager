<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()
 */
class DefaultFieldInternalPlugin extends AbstractPlugin implements DefaultFieldPluginInterface
{
    public const FIELD_CLIENT_IP = 'clientIp';

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
        $internalIps = $this->getConfig()->getInternalIps();

        if (!$params[static::FIELD_CLIENT_IP]) {
            return $googleTagManagerDefaultTransfer;
        }

        if (!in_array($params[static::FIELD_CLIENT_IP], $internalIps, true)) {
            return $googleTagManagerDefaultTransfer;
        }

        return $googleTagManagerDefaultTransfer->setInternalTraffic(true);
    }
}
