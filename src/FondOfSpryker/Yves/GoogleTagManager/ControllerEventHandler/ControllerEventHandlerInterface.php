<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler;

use Symfony\Component\HttpFoundation\Request;

interface ControllerEventHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
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
