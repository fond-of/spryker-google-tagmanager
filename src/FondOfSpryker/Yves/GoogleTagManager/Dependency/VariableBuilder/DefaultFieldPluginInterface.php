<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer;

interface DefaultFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer $googleTagManagerDefaultTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer
     */
    public function handle(
        GoogleTagManagerDefaultTransfer $googleTagManagerDefaultTransfer,
        array $params = []
    ): GoogleTagManagerDefaultTransfer;
}
