<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use Symfony\Component\HttpFoundation\Request;

interface ControllerEventHandlerInterface
{
    /**
     * @param Request $request
     * @param string $locale
     *
     * @return void
     */
    public function handle(Request $request, string $locale): void;

    /**
     * @return string
     */
    public function getMethodName(): string;
}
