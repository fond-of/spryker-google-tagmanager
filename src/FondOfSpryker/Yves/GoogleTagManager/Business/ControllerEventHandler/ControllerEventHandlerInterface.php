<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler;

use Symfony\Component\HttpFoundation\Request;

interface ControllerEventHandlerInterface
{
    /**
     * @param Request $request
     */
    public function hande(Request $request): void;

    /**
     * @return string
     */
    public function getMethodName(): string;
}
