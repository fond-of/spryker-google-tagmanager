<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class TransactionProductFieldImageUrlPlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
{
    public const ATTR_IMAGE_URL = 'imageUrl';

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
        $imageUrl = $this->getImageUrl($itemTransfer);

        if ($imageUrl === null) {
            return $gooleTagManagerTransactionProductTransfer;
        }

        return $gooleTagManagerTransactionProductTransfer->setImageUrl($imageUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string|null
     */
    protected function getImageUrl(ItemTransfer $itemTransfer): ?string
    {
        foreach ($itemTransfer->getImages() as $imageTransfer) {
            if ($imageTransfer instanceof ProductImageTransfer) {
                return $imageTransfer->getExternalUrlSmall();
            }

            if ($imageTransfer instanceof ProductImageStorageTransfer) {
                return $imageTransfer->getExternalUrlSmall();
            }
        }

        return null;
    }
}
