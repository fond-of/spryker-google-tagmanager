<?php
namespace FondOfSpryker\Yves\GoogleTagManager;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;

class GoogleTagManagerConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getContainerID()
    {
        return $this->get(GoogleTagManagerConstants::CONTAINER_ID);
    }

}
