<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductImageStorageTransfer;
use Generated\Shared\Transfer\ProductImageTransfer;

class ImageUrlPlugin implements TransactionProductVariableBuilderPluginInterface
{
    public const URL_IMAGE = 'imageUrl';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     *
     * @return array
     */
    public function handle(ItemTransfer $product, array $params = []): array
    {
        $image = null;

        foreach ($product->getImages() as $imageTransfer) {
            $image = $imageTransfer;

            break;
        }

        if ($image === null) {
            return [];
        }

        if ($image instanceof ProductImageTransfer) {
            return [static::URL_IMAGE => $image->getExternalUrlSmall()];
        }

        if ($image instanceof ProductImageStorageTransfer) {
            return [static::URL_IMAGE => $image->getExternalUrlSmall()];
        }

        return [];
    }
}
