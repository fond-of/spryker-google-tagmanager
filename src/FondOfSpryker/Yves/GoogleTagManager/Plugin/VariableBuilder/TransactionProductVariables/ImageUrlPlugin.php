<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;

class ImageUrlPlugin implements TransactionProductVariableBuilderPluginInterface
{
    public const FIELD_NAME = 'imageUrl';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    public function handle(ItemTransfer $itemTransfer, array $params = []): array
    {
        $image = null;

        foreach ($itemTransfer->getImages() as $imageTransfer) {
            $image = $imageTransfer;

            break;
        }

        if ($image === null) {
            return [];
        }

        if ($image instanceof ProductImageTransfer) {
            return [static::FIELD_NAME => $image->getExternalUrlSmall()];
        }

        if ($image instanceof ProductImageStorageTransfer) {
            return [static::FIELD_NAME => $image->getExternalUrlSmall()];
        }

        return [];
    }
}
