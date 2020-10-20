<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

use Generated\Shared\Transfer\GooleTagManagerDefaultTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()
 */
class InternalVariableBuilderPlugin extends AbstractPlugin implements DefaultVariableBuilderPluginInterface
{
    public const FIELD_CLIENT_IP = 'clientIp';

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
        $internalIps = $this->getConfig()->getInternalIps();

        if (!$params[static::FIELD_CLIENT_IP]) {
            return $gooleTagManagerDefaultTransfer;
        }

        if (!in_array($params[static::FIELD_CLIENT_IP], $internalIps, true)) {
            return $gooleTagManagerDefaultTransfer;
        }

        return $gooleTagManagerDefaultTransfer->setInternalTraffic(true);
    }
}
