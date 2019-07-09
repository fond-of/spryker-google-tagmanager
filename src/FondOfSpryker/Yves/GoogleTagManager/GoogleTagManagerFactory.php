<?php

/**
 * Google Tag Manager tracking integration for Spryker
 *
 * @author      Jozsef Geng <gengjozsef86@gmail.com>
 */

namespace FondOfSpryker\Yves\GoogleTagManager;

use FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\VariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Product\ProductClientInterface;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Zed\Tax\Business\Model\PriceCalculationHelper;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()
 */
class GoogleTagManagerFactory extends AbstractFactory
{
    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension
     */
    public function createGoogleTagManagerTwigExtension(): GoogleTagManagerTwigExtension
    {
        return new GoogleTagManagerTwigExtension(
            $this->getContainerID(),
            $this->isEnabled(),
            $this->createDataLayerVariableBuilder(),
            $this->createCartClient(),
            $this->createSessionClient()
        );
    }

    /**
     * @return \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\VariableBuilderInterface
     */
    public function createDataLayerVariableBuilder()
    {
        return new VariableBuilder(
            $this->createMoneyPlugin(),
            $this->createPriceCalculationHelper(),
            $this->createProductClient(),
            $this->getConfig()
        );
    }

    /**
     * @return string
     */
    protected function getContainerID(): string
    {
        return $this->getConfig()->getContainerID();
    }

    /**
     * @return bool
     */
    protected function isEnabled(): bool
    {
        return $this->getConfig()->isEnabled();
    }

    /**
     * @return \Spryker\Client\Cart\CartClientInterface CartClientInterface
     */
    protected function createCartClient(): CartClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::CART_CLIENT);
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface
     */
    protected function createMoneyPlugin(): MoneyPluginInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PLUGIN_MONEY);
    }

    /**
     * @return \Spryker\Client\product\ProductClientInterface
     */
    protected function createProductClient(): ProductClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::PRODUCT_CLIENT);
    }

    /**
     * @return \Spryker\Client\Session\SessionClientInterface;
     */
    protected function createSessionClient(): SessionClientInterface
    {
        return $this->getProvidedDependency(GoogleTagManagerDependencyProvider::SESSION_CLIENT);
    }

    /**
     * @return \Spryker\Zed\Tax\Business\Model\PriceCalculationHelperInterface
     */
    public function createPriceCalculationHelper()
    {
        return new PriceCalculationHelper();
    }
}
