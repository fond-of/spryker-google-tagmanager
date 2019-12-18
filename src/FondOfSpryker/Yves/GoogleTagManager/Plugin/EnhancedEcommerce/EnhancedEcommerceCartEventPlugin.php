<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhancedEcommerceCartEventPlugin extends AbstractPlugin implements EnhancedEcommerceEventPluginInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function handle(Request $request): array
    {
        return [
            $this->addProduct($request),
            $this->removeProduct($request),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function addProduct(Request $request): array
    {
        if (!$request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_ADD)) {
            return [];
        }

        $addProductEventArray = unserialize($request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_ADD));

        if (!array_key_exists('event', $addProductEventArray) || $addProductEventArray['event'] !== GoogleTagManagerConstants::EEC_EVENT_ADD) {
            return [];
        }

        return $addProductEventArray;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function removeProduct(Request $request): array
    {
        if (!$request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_REMOVE)) {
            return [];
        }

        $removeProductEventArray = unserialize($request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_REMOVE));

        if (!array_key_exists('event', $removeProductEventArray) || $removeProductEventArray['event'] !== GoogleTagManagerConstants::EEC_EVENT_REMOVE) {
            return [];
        }

        return $removeProductEventArray;
    }
}
