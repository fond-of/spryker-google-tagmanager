<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

use Generated\Shared\Transfer\GooleTagManagerDefaultTransfer;

interface DefaultVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerDefaultTransfer $gooleTagManagerDefaultTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerDefaultTransfer
     */
    public function handle(
        GooleTagManagerDefaultTransfer $gooleTagManagerDefaultTransfer,
        array $params = []
    ): GooleTagManagerDefaultTransfer;
}
