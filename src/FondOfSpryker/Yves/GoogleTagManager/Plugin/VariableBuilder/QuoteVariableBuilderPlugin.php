<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteVariableBuilderInterface;
use Generated\Shared\Transfer\GooleTagManagerQuoteTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class QuoteVariableBuilderPlugin extends AbstractPlugin implements QuoteVariableBuilderInterface
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
            try {
                $gooleTagManagerQuoteTransfer = $plugin->handle($gooleTagManagerQuoteTransfer, $quoteTransfer);
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ), [$e->getMessage()]);
            }
        }

        $gooleTagManagerQuoteTransfer = $this->addTransactionProducts($gooleTagManagerQuoteTransfer, $quoteTransfer);

        return $this->stripEmptyArrayIndex($gooleTagManagerQuoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerQuoteTransfer $gooleTagManagerQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerQuoteTransfer
     */
    protected function addTransactionProducts(
        GooleTagManagerQuoteTransfer $gooleTagManagerQuoteTransfer,
        QuoteTransfer $quoteTransfer
    ): GooleTagManagerQuoteTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $gooleTagManagerTransactionProductTransfer = $this->getFactory()
                ->getTransactionProductVariableBuilderPlugin()
                ->getProduct($itemTransfer);

            $gooleTagManagerQuoteTransfer->addTransactionProducts($gooleTagManagerTransactionProductTransfer);
        }

        return $gooleTagManagerQuoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GooleTagManagerQuoteTransfer
     */
    protected function createGoogleTagManagerQuoteTransfer(): GooleTagManagerQuoteTransfer
    {
        return new GooleTagManagerQuoteTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer $gooleTagManagerQuoteTransfer
     *
     * @return array
     */
    protected function stripEmptyArrayIndex(GooleTagManagerQuoteTransfer $gooleTagManagerQuoteTransfer): array
    {
        $gooleTagManagerQuoteArray = $gooleTagManagerQuoteTransfer->toArray(true, true);

        foreach ($gooleTagManagerQuoteArray as $field => $value) {
            if ($value === null || $value === '') {
                unset($gooleTagManagerQuoteArray[$field]);
            }
        }

        return $gooleTagManagerQuoteArray;
    }
}
