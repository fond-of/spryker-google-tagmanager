<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $variables
     *
     * @return array
     */
    public function handle(OrderTransfer $orderTransfer, array $variables): array;
}
