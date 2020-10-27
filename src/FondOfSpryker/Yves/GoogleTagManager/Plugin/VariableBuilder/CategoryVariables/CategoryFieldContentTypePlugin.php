<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\CategoryVariables;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\CategoryFieldPluginInterface;
use Generated\Shared\Transfer\GooleTagManagerCategoryTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

class CategoryFieldContentTypePlugin extends AbstractPlugin implements CategoryFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer
     * @param array $category
     * @param array $products
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GooleTagManagerCategoryTransfer
     */
    public function handle(
        GooleTagManagerCategoryTransfer $gooleTagManagerCategoryTransfer,
        array $category,
        array $products = [],
        array $params = []
    ): GooleTagManagerCategoryTransfer {
        if (isset($params[GoogleTagManagerConstants::CATEGORY_ARRAY_CONTENT_TYPE])) {
            $gooleTagManagerCategoryTransfer->setContentType(
                $params[GoogleTagManagerConstants::CATEGORY_ARRAY_CONTENT_TYPE]
            );
        }

        return $gooleTagManagerCategoryTransfer;
    }
}
