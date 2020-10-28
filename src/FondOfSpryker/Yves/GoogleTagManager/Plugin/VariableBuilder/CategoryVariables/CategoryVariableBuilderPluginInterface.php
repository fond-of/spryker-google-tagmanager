<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables;

use Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer;

interface CategoryVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer
     *
     * @return array
     */
    public function handle(GoogleTagManagerCategoryTransfer $googleTagManagerCategoryTransfer): array;
}
