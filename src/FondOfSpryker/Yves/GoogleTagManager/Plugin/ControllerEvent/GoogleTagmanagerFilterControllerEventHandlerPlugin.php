<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\ControllerEvent;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShopApplicationExtension\Dependency\Plugin\FilterControllerEventHandlerPluginInterface;
use Symfony\Component\HttpFoundation\Request;
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
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return void
     * @api
     *
     */
    public function handle(FilterControllerEvent $event): void
    {
        if (\strpos(\get_class($event->getController()[0]), static::CONTROLLER) === false) {
            return;
        }

        $controllerEventHandlers = $this->getFactory()
            ->getControllerEventHandler();

        foreach($controllerEventHandlers as $controllerEventHandler) {
            if ($controllerEventHandler->getMethodName() === $event->getController()[1]) {
                $controllerEventHandler->hande($event->getRequest(), $this->getClient());
            }
        }

        return;
    }
}
