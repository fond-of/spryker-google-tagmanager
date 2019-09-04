<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use Generated\Shared\Transfer\OrderTransfer;

interface OrderVariableBuilderPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param array $variables
     *
     * @return array
     */
    public function handle(OrderTransfer $orderTransfer, array $variables): array;
}
