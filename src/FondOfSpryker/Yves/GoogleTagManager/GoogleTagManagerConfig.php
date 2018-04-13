<?php

/**
 * Google Tag Manager tracking integration for Spryker
 *
 * @author      Jozsef Geng <gengjozsef86@gmail.com>
 */
namespace FondOfSpryker\Yves\GoogleTagManager;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class GoogleTagManagerConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getContainerID()
    {
        return $this->get(GoogleTagManagerConstants::CONTAINER_ID);
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->get(GoogleTagManagerConstants::ENABLED);
    }
}
