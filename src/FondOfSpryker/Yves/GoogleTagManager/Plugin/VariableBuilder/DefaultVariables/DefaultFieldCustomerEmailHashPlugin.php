<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultFieldPluginInterface;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class DefaultFieldCustomerEmailHashPlugin extends AbstractPlugin implements DefaultFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer $googleTagManagerDefaultTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer
     */
    public function handle(
        GoogleTagManagerDefaultTransfer $googleTagManagerDefaultTransfer,
        array $params = []
    ): GoogleTagManagerDefaultTransfer {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        if (!$quoteTransfer instanceof QuoteTransfer) {
            return $googleTagManagerDefaultTransfer;
        }

        if (!$quoteTransfer->getBillingAddress() instanceof AddressTransfer) {
            return $googleTagManagerDefaultTransfer;
        }

        if (!$quoteTransfer->getBillingAddress()->getEmail()) {
            return $googleTagManagerDefaultTransfer;
        }

        return $googleTagManagerDefaultTransfer->setExternalIdHash(sha1($quoteTransfer->getBillingAddress()->getEmail()));
    }
}
