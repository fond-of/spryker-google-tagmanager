<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler;

use Symfony\Component\HttpFoundation\Request;

class AddProductControllerEventHandler implements ControllerEventHandlerInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'addAction';
    }

    /**
     * @param Request $request
     */
    public function hande(Request $request): void
    {
        // TODO: Implement hande() method.
    }
}
