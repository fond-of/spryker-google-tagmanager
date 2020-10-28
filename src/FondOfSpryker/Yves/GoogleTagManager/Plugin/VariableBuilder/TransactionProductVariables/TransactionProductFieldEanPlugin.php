<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionProductFieldEanPlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
{
    use TransactionProductLocalizedAttributeTrait;

    public const ATTR_EAN = 'ean';

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer $googleTagManagerTransactionProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer
     */
    public function handle(
        GoogleTagManagerTransactionProductTransfer $googleTagManagerTransactionProductTransfer,
        ItemTransfer $itemTransfer,
        array $params = []
    ): GoogleTagManagerTransactionProductTransfer {
        $locale = $this->getFactory()
            ->getStore()
            ->getCurrentLocale();

        $ean = $this->getAttr($itemTransfer, $locale, static::ATTR_EAN);

        return $googleTagManagerTransactionProductTransfer->setEan($ean);
    }
}
