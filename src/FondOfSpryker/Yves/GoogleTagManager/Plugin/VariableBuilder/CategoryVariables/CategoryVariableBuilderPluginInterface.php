<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables;

use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;

interface CategoryVariableBuilderPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer
     *
     * @return array
     */
    public function handle(GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer): array;
}
