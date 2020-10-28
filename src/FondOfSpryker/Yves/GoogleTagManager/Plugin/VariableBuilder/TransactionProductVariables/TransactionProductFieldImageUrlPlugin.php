<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class TransactionProductFieldImageUrlPlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
{
    public const ATTR_IMAGE_URL = 'imageUrl';

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
        $imageUrl = $this->getImageUrl($itemTransfer);

        if ($imageUrl === null) {
            return $googleTagManagerTransactionProductTransfer;
        }

        return $googleTagManagerTransactionProductTransfer->setImageUrl($imageUrl);
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
