<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GooleTagManagerQuoteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

interface QuoteFieldVariableBuilderPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerQuoteTransfer $gooleTagManagerQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerQuoteTransfer
     */
    public function handle(
        GooleTagManagerQuoteTransfer $gooleTagManagerQuoteTransfer,
        QuoteTransfer $quoteTransfer,
        array $params = []
    ): GooleTagManagerQuoteTransfer;
}
