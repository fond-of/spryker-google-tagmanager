<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;

trait TransactionProductLocalizedAttributeTrait
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getAttr(ItemTransfer $itemTransfer, string $locale, string $field): string
    {
        if (isset($itemTransfer->getAbstractAttributes()['_'][$field])) {
            return $itemTransfer->getAbstractAttributes()['_'][$field];
        }

        if (isset($itemTransfer->getAbstractAttributes()[$locale][$field])) {
            return $itemTransfer->getAbstractAttributes()[$locale][$field];
        }

        return '';
    }
}
