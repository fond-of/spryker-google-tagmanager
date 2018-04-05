<?php

namespace FondOfSpryker\Yves\GoogleTagManager;

use FondOfSpryker\Yves\GoogleTagManager\DataLayer\Variable;
use FondOfSpryker\Yves\GoogleTagManager\DataLayer\VariableInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use FondOfSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()
 */
class GoogleTagManagerFactory extends AbstractFactory
{

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension
     */
    public function createGoogleTagManagerTwigExtension() : GoogleTagManagerTwigExtension
    {
        return new GoogleTagManagerTwigExtension(
            $this->getContainerID(),
            $this->createDataLayerVariables(),
            $this->createCartClient()
        );
    }

    /**
     * @return string
     */
    private function getContainerID():string
    {
        return $this->getConfig()->getContainerID();
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\DataLayer\VariableInterface
     */
    private function createDataLayerVariables() : VariableInterface
    {
        return new Variable();
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function createCartClient()
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CART_CLIENT);
    }
}
