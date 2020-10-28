<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductsVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer;
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
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer
     */
    public function getProduct(ItemTransfer $itemTransfer): GoogleTagManagerTransactionProductTransfer
    {
        $GoogleTagManagerTransactionProductTransfer = $this->createGoogleTagManagerTransactionProductTransfer();

        foreach ($this->getFactory()->getTransactionProductVariableBuilderFieldPlugins() as $plugin) {
            try {
                $GoogleTagManagerTransactionProductTransfer = $plugin->handle(
                    $GoogleTagManagerTransactionProductTransfer,
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

        return $GoogleTagManagerTransactionProductTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer
     */
    protected function createGoogleTagManagerTransactionProductTransfer(): GoogleTagManagerTransactionProductTransfer
    {
        return new GoogleTagManagerTransactionProductTransfer();
    }
}
