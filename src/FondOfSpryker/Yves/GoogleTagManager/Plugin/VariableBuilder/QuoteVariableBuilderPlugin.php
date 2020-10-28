<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteVariableBuilderInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer;
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
        $googleTagManagerTransactionTransfer = $this->createGoogleTagManagerQuoteTransfer();

        foreach ($this->getFactory()->getQuoteVariableBuilderFieldPlugins() as $plugin) {
            try {
                $googleTagManagerTransactionTransfer = $plugin->handle($googleTagManagerTransactionTransfer, $quoteTransfer);
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ), [$e->getMessage()]);
            }
        }

        $googleTagManagerTransactionTransfer = $this->addTransactionProducts($googleTagManagerTransactionTransfer, $quoteTransfer);

        return $this->stripEmptyArrayIndex($googleTagManagerTransactionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer $googleTagManagerTransactionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer
     */
    protected function addTransactionProducts(
        GoogleTagManagerTransactionTransfer $googleTagManagerTransactionTransfer,
        QuoteTransfer $quoteTransfer
    ): GoogleTagManagerTransactionTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $googleTagManagerTransactionTransferProductTransfer = $this->getFactory()
                ->getTransactionProductVariableBuilderPlugin()
                ->getProduct($itemTransfer);

            $googleTagManagerTransactionTransfer->addTransactionProducts($googleTagManagerTransactionTransferProductTransfer);
        }

        return $googleTagManagerTransactionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionTransfer
     */
    protected function createGoogleTagManagerQuoteTransfer(): GoogleTagManagerTransactionTransfer
    {
        return new GoogleTagManagerTransactionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer $googleTagManagerTransactionTransfer
     *
     * @return array
     */
    protected function stripEmptyArrayIndex(GoogleTagManagerTransactionTransfer $googleTagManagerTransactionTransfer): array
    {
        $googleTagManagerQuoteArray = $googleTagManagerTransactionTransfer->toArray(true, true);

        foreach ($googleTagManagerQuoteArray as $field => $value) {
            if ($value === null || $value === '') {
                unset($googleTagManagerQuoteArray[$field]);
            }
        }

        return $googleTagManagerQuoteArray;
    }
}
