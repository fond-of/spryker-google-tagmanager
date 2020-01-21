<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\ProductVariables;

use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteVariableBuilderPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $variables
     *
     * @return array
     */
    public function handle(QuoteTransfer $quoteTransfer, array $variables): array;
}
