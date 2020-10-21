<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteDataLayerVariableBuilderInterface;
use Generated\Shared\Transfer\GooleTagManagerQuoteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class QuoteVariableBuilderPlugin extends AbstractPlugin implements QuoteDataLayerVariableBuilderInterface
{
    public const VARIABLE_BUILDER_NAME = 'quote';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::VARIABLE_BUILDER_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array
     */
    public function getVariables(QuoteTransfer $quoteTransfer): array
    {
        $gooleTagManagerQuoteTransfer = $this->createGoogleTagManagerQuoteTransfer();

        foreach ($this->getFactory()->getQuoteVariableBuilderFieldPlugins() as $plugin) {
            $gooleTagManagerQuoteTransfer = $plugin->handle($gooleTagManagerQuoteTransfer, $quoteTransfer);
        }

        $variables = [
            GoogleTagManagerConstants::TRANSACTION_PRODUCTS => $this->transactionProductsVariableBuilder->getProductsFromQuote($quoteTransfer),
        ];

        return $gooleTagManagerQuoteTransfer->toArray(true, true);
    }

    /**
     * @return \Generated\Shared\Transfer\GooleTagManagerQuoteTransfer
     */
    protected function createGoogleTagManagerQuoteTransfer(): GooleTagManagerQuoteTransfer
    {
        return new GooleTagManagerQuoteTransfer();
    }
}
