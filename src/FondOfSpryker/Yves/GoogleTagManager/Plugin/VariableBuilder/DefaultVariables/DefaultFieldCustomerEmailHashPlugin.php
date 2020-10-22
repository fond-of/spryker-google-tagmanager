<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultFieldPluginInterface;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\GooleTagManagerDefaultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class DefaultFieldCustomerEmailHashPlugin extends AbstractPlugin implements DefaultFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerDefaultTransfer $gooleTagManagerDefaultTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerDefaultTransfer
     */
    public function handle(
        GooleTagManagerDefaultTransfer $gooleTagManagerDefaultTransfer,
        array $params = []
    ): GooleTagManagerDefaultTransfer {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        if (!$quoteTransfer instanceof QuoteTransfer) {
            return $gooleTagManagerDefaultTransfer;
        }

        if (!$quoteTransfer->getBillingAddress() instanceof AddressTransfer) {
            return $gooleTagManagerDefaultTransfer;
        }

        if (!$quoteTransfer->getBillingAddress()->getEmail()) {
            return $gooleTagManagerDefaultTransfer;
        }

        return $gooleTagManagerDefaultTransfer->setExternalIdHash(sha1($quoteTransfer->getBillingAddress()->getEmail()));
    }
}
