<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\QuoteVariableBuilderInterface;
use Generated\Shared\Transfer\GooleTagManagerTransactionTransfer;
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
        $gooleTagManagerTransactionTransfer = $this->createGoogleTagManagerQuoteTransfer();

        foreach ($this->getFactory()->getQuoteVariableBuilderFieldPlugins() as $plugin) {
            try {
                $gooleTagManagerTransactionTransfer = $plugin->handle($gooleTagManagerTransactionTransfer, $quoteTransfer);
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ), [$e->getMessage()]);
            }
        }

        $gooleTagManagerTransactionTransfer = $this->addTransactionProducts($gooleTagManagerTransactionTransfer, $quoteTransfer);

        return $this->stripEmptyArrayIndex($gooleTagManagerTransactionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerTransactionTransfer $gooleTagManagerTransactionTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionTransfer
     */
    protected function addTransactionProducts(
        GooleTagManagerTransactionTransfer $gooleTagManagerTransactionTransfer,
        QuoteTransfer $quoteTransfer
    ): GooleTagManagerTransactionTransfer {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $gooleTagManagerTransactionTransferProductTransfer = $this->getFactory()
                ->getTransactionProductVariableBuilderPlugin()
                ->getProduct($itemTransfer);

            $gooleTagManagerTransactionTransfer->addTransactionProducts($gooleTagManagerTransactionTransferProductTransfer);
        }

        return $gooleTagManagerTransactionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionTransfer
     */
    protected function createGoogleTagManagerQuoteTransfer(): GooleTagManagerTransactionTransfer
    {
        return new GooleTagManagerTransactionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer $gooleTagManagerTransactionTransfer
     *
     * @return array
     */
    protected function stripEmptyArrayIndex(GooleTagManagerTransactionTransfer $gooleTagManagerTransactionTransfer): array
    {
        $gooleTagManagerQuoteArray = $gooleTagManagerTransactionTransfer->toArray(true, true);

        foreach ($gooleTagManagerQuoteArray as $field => $value) {
            if ($value === null || $value === '') {
                unset($gooleTagManagerQuoteArray[$field]);
            }
        }

        return $gooleTagManagerQuoteArray;
    }
}
