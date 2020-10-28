<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionProductFieldNamePlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
{
    use TransactionProductLocalizedAttributeTrait;

    public const ATTR_NAME_UNTRANSLATED = 'name_untranslated';

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

        $nameUntranslated = $this->getAttr($itemTransfer, $locale, static::ATTR_NAME_UNTRANSLATED);

        if ($nameUntranslated) {
            return $googleTagManagerTransactionProductTransfer->setName($nameUntranslated);
        }

        return $googleTagManagerTransactionProductTransfer->setName($itemTransfer->getName());
    }
}
