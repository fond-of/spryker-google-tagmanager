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
     * @param $value
     *
     * @return mixed
     */
    public function set(string $name, $value): void
    {
        $this->sessionClient->set($name, $value);
    }

    /**
     * @param string $name
     * @param null $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->sessionClient->get($name, $default);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function remove(string $name): void
    {
        $this->sessionClient->remove($name);
    }
}
