<?php

namespace FondOfSpryker\Yves\GoogleTagManager;

use Spryker\Yves\Kernel\AbstractFactory;
use FondOfSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()
 */
class GoogleTagManagerFactory extends AbstractFactory
{

    /**
     *
     * @return \FondOfSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension
     */
    public function createGoogleTagManagerTwigExtension()
    {
        return new GoogleTagManagerTwigExtension($this->getContainerID());
    }

    /**
     * @return string
     */
    protected function getContainerID()
    {
        return $this->getConfig()->getContainerID();
    }
}
