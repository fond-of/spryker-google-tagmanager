<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductsVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class TransactionProductVariableBuilderPlugin extends AbstractPlugin implements TransactionProductsVariableBuilderPluginInterface
{
    public const VARIABLE_BUILDER_NAME = 'transactionProducts';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::VARIABLE_BUILDER_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer
     */
    public function getProduct(ItemTransfer $itemTransfer): GooleTagManagerTransactionProductTransfer
    {
        $gooleTagManagerTransactionProductTransfer = $this->createGooleTagManagerTransactionProductTransfer();

        foreach ($this->getFactory()->getTransactionProductVariableBuilderFieldPlugins() as $plugin) {
            try {
                $gooleTagManagerTransactionProductTransfer = $plugin->handle(
                    $gooleTagManagerTransactionProductTransfer,
                    $itemTransfer
                );
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ), [$e->getMessage()]);
            }
        }

        return $gooleTagManagerTransactionProductTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer
     */
    protected function createGooleTagManagerTransactionProductTransfer(): GooleTagManagerTransactionProductTransfer
    {
        return new GooleTagManagerTransactionProductTransfer();
    }
}
