<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;


use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class CustomerEmailHashVariableBuilderPlugin extends AbstractPlugin implements DefaultVariableBuilderPluginInterface
{

    /**
     * @return string
     */
    public function getName(): string
    {
        // TODO: Implement getName() method.
    }

    /**
     * @param array $variables
     * @param array $params
     *
     * @return array
     */
    public function handle(array $variables, array $params = []): array
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        if (!$quoteTransfer instanceof QuoteTransfer) {
            return [];
        }

        if (!$quoteTransfer->getBillingAddress() instanceof AddressTransfer) {
            return [];
        }

        if (!$quoteTransfer->getBillingAddress()->getEmail()) {
            return [];
        }

        return [
            'externalIdHash' => \sha1($quoteTransfer->getBillingAddress()->getEmail()),
        ];
    }
}
