<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\ControllerEvent;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 * @method \FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClient getClient()
 */
class GoogleTagmanagerFilterControllerEventHandlerPlugin extends AbstractPlugin implements FilterControllerEventHandlerPluginInterface
{
    public const CONTROLLER = 'Yves\CartPage\Controller\CartController';

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
        if (\strpos(\get_class($event->getController()[0]), static::CONTROLLER) === false) {
            return;
        }

        $controllerEventHandlers = $this->getFactory()
            ->getControllerEventHandler();

        foreach ($controllerEventHandlers as $controllerEventHandler) {
            if ($controllerEventHandler->getMethodName() === $event->getController()[1]) {
                $controllerEventHandler->handle($event->getRequest(), $this->getClient(), 'en_US');
            }
        }

        return;
    }
}
