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
     *
     * @return mixed
     */
    public function get(string $name, $default = null);

    /**
     * @param string $name
     * @param $value
     *
     * @return void
     */
    public function set(string $name, $value): void;

    /**
     * @param string $name
     *
     * @return void
     */
    public function remove(string $name): void;
}
