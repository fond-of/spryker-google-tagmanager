<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use Symfony\Component\HttpFoundation\Request;

interface ControllerEventHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function handle(Request $request, GoogleTagManagerClientInterface $client, string $locale): void;

    /**
     * @return string
     */
    public function getMethodName(): string;
}
