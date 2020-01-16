<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface;

class EnhancedEcommerceSessionHandler implements EnhancedEcommerceSessionHandlerInterface
{
    /**
     * @var GoogleTagManagerToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param GoogleTagManagerToSessionClientInterface $sessionClient
     */
    public function __construct(GoogleTagManagerToSessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }
}
