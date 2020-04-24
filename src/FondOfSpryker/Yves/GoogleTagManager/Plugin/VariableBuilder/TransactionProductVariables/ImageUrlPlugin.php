<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;
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
        foreach ($product->getImages() as $image) {
            $image = $image;

            break;
        }

        if (!$image instanceof ProductImageTransfer) {
            return [];
        }

        return [static::URL_IMAGE => $image->getExternalUrlSmall()];
    }
}
