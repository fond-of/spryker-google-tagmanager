<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables;

use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;

interface CategoryVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer
     *
     * @return array
     */
    public function handle(GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer): array;
}
