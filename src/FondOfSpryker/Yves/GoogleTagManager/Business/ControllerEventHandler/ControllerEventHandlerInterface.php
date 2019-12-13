<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use Symfony\Component\HttpFoundation\Request;

interface ControllerEventHandlerInterface
{
    /**
     * @param Request $request
     */
    public function hande(Request $request, GoogleTagManagerClientInterface $client): void;

    /**
     * @return string
     */
    public function getMethodName(): string;
}
