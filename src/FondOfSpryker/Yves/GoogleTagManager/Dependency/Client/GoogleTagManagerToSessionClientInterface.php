<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

interface GoogleTagManagerToSessionClientInterface
{
    /**
     * @return string
     */
    public function getId(): string;

    /**
     * @param string $name
     * @param null $default
     * @return mixed
     */
    public function get(string $name, $default = null);
}
