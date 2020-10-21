<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionProductFieldBrandPlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
{
    use LoggerTrait;

    public const ATTR_BRAND = 'brand';
    public const DEFAULT_LOCALE = '_';

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer $gooleTagManagerTransactionProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer
     */
    public function handle(
        GooleTagManagerTransactionProductTransfer $gooleTagManagerTransactionProductTransfer,
        ItemTransfer $itemTransfer,
        array $params = []
    ): GooleTagManagerTransactionProductTransfer {
        try {
            $gooleTagManagerTransactionProductTransfer->setBrand($this->getAttrBrand($itemTransfer));
        } catch (Exception $e) {
            $this->getLogger()->notice(sprintf(
                'GoogleTagManager: attribute %s not found in %s',
                $itemTransfer::ABSTRACT_ATTRIBUTES,
                self::class
            ), ['quote' => json_encode($itemTransfer)]);
        }

        return $gooleTagManagerTransactionProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function getAttrBrand(ItemTransfer $itemTransfer): string
    {
        if (isset($itemTransfer->getAbstractAttributes()[static::DEFAULT_LOCALE][static::ATTR_BRAND])) {
            return $itemTransfer->getAbstractAttributes()[static::DEFAULT_LOCALE][static::ATTR_BRAND];
        }

        $locale = $this->getFactory()
            ->getStore()
            ->getCurrentLocale();

        if (isset($itemTransfer->getAbstractAttributes()[$locale][static::ATTR_BRAND])) {
            return $itemTransfer->getAbstractAttributes()[$locale][static::ATTR_BRAND];
        }

        return '';
    }
}
