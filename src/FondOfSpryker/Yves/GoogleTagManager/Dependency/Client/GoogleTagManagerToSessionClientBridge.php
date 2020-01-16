<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

use Spryker\Client\Session\SessionClientInterface;

class GoogleTagManagerToSessionClientBridge implements GoogleTagManagerToSessionClientInterface
{
    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    private $sessionClient;

    public function __construct(SessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->sessionClient->getId();
    }

    /**
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->sessionClient-$this->get($name, $default);
    }
}
