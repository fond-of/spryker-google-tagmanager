<?php

/**
 * Google Tag Manager tracking integration for Spryker
 *
 * @author      Jozsef Geng <gengjozsef86@gmail.com>
 */
namespace FondOfSpryker\Yves\GoogleTagManager;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class GoogleTagManagerConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getContainerID()
    {
        return $this->get(GoogleTagManagerConstants::CONTAINER_ID);
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->get(GoogleTagManagerConstants::ENABLED);
    }

    /**
     * @return string
     */
    public function getSpecialPriceAttribute(): string
    {
        return $this->get(GoogleTagManagerConstants::ATTRIBUTE_SPECIAL_PRICE);
    }

    /**
     * @return string
     */
    public function getSpecialPriceFromAttribute(): string
    {
        return $this->get(GoogleTagManagerConstants::ATTRIBUTE_SPECIAL_PRICE_FROM);
    }

    /**
     * @return string
     */
    public function getSpecialPriceToAttribute(): string
    {
        return $this->get(GoogleTagManagerConstants::ATTRIBUTE_SPECIAL_PRICE_TO);
    }

    /**
     * @return array
     */
    public function getListenToControllersEnhancedEcommerce(): array
    {
        return $this->get(GoogleTagManagerConstants::EEC_LISTEN_TO_CONTROLLERS, []);
    }

    /**
     * @return array
     */
    public function getListenToControllersGoogleTagManager(): array
    {
        return $this->get(GoogleTagManagerConstants::GTM_LISTEN_TO_CONTROLLERS, []);
    }

    /**
     * @return string
     */
    public function getEnhancedEcommerceLocale(): string
    {
        return $this->get(EnhancedEcommerceConstants::EEC_LOCALE, 'en_US');
    }
}
