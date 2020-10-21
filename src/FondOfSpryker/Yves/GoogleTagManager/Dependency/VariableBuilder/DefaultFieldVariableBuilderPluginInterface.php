<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GooleTagManagerDefaultTransfer;

interface DefaultFieldVariableBuilderPluginInterface
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
