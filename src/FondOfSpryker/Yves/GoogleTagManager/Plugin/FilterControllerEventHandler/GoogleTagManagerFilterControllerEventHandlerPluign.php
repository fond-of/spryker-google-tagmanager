<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\FilterControllerEventHandler;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()()
 */
class GoogleTagManagerFilterControllerEventHandlerPluign extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    /**
     * @param string $className
     *
     * @return bool
     */
    protected function checkForValidController(string $className): bool
    {
        foreach ($this->getConfig()->getListenToControllersGoogleTagManager() as $controller) {
            if (strpos($className, $controller) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function handle(FilterControllerEvent $event): void
    {
        if (!is_array($event->getController())) {
            return;
        }

        if ($this->checkForValidController(get_class($event->getController()[0])) === false) {
            return;
        }

        $newsletterControllerEventHandler = $this->getFactory()->getNewsletterControllerEventHandler();

        foreach ($newsletterControllerEventHandler as $controllerEventHandler) {
            if ($controllerEventHandler->getMethodName() === $event->getController()[1]) {
                $controllerEventHandler->handle($event->getRequest(), null);
            }
        }
    }
}
