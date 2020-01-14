<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\FilterControllerEventHandler;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 * @method \FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClient getClient()
 */
class EnhancedEcommerceFilterControllerEventHandlerPlugin extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    protected $listenToControllers = [
        'Yves\CartPage\Controller\CartController',
        'Yves\CheckoutPage\Controller\CheckoutController',
        'Yves\ProductDetailPage\Controller\ProductController',
    ];

    protected function checkForValidController(string $className): bool
    {
        foreach ($this->listenToControllers as $controller) {
            if (\strpos($className, $controller) === false) {
                continue;
            }

            if (\strpos($className, $controller) !== false) {
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
        if ($this->checkForValidController(\get_class($event->getController()[0])) === false) {
            return;
        }

        foreach ($this->listenToControllers as $controller) {
            if (\strpos(\get_class($event->getController()[0]), $controller) === false) {
                continue;
            }

            if (\strpos(\get_class($event->getController()[0]), $controller) !== false) {
                break;
            }

            return;
        }

        $cartControllerEventHandler = $this->getFactory()
            ->getCartControllerEventHandler();

        foreach ($cartControllerEventHandler as $controllerEventHandler) {
            if ($controllerEventHandler->getMethodName() === $event->getController()[1]) {
                $controllerEventHandler->handle($event->getRequest(), $this->getClient(), 'en_US');
            }
        }

        return;
    }
}
