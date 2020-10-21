<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class TransactionProductFieldNamePlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
{
    public const FIELD_NAME = 'name';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return array
     */
    public function handle(ItemTransfer $itemTransfer, array $params = []): array
    {
        $locale = isset($params['locale']) ? $params['locale'] : '_';

        if (!isset($itemTransfer->getAbstractAttributes()[$locale])) {
            return [static::FIELD_NAME => $itemTransfer->getName()];
        }

        if (!isset($itemTransfer->getAbstractAttributes()[$locale][GoogleTagManagerConstants::NAME_UNTRANSLATED])) {
            return [static::FIELD_NAME => $itemTransfer->getName()];
        }

        return [
            static::FIELD_NAME => $itemTransfer->getAbstractAttributes()[$locale][GoogleTagManagerConstants::NAME_UNTRANSLATED],
        ];
    }
}
