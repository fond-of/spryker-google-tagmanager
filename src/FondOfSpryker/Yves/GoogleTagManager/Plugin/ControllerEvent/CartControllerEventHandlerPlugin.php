<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\ControllerEvent;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 * @method \FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClient getClient()
 */
class CartControllerEventHandlerPlugin extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    public const CONTROLLER = [
        'Yves\CartPage\Controller\CartController',
        'Yves\CartPage\Controller\CheckoutController',
        'Yves\ProductDetailPage\Controller\ProductController'
    ];

    /**
     * Specification:
     * - Subscribes for symfony FilterControllerEvent
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     */
    public function handle(FilterControllerEvent $event): void
    {
        if (in_array(\get_class($event->getController()[0]), static::CONTROLLER)) {
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
