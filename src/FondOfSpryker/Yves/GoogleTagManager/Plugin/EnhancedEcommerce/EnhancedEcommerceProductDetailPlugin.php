<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhancedEcommerceProductDetailPlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
    /**
     * @param \Twig_Environment $twig
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array|null $params
     *
     * @throws
     *
     * @return string
     */
    public function handle(Twig_Environment $twig, Request $request, ?array $params = []): string
    {
        $productViewTransfer = $params['product'];

        $products[] = $this->getFactory()
            ->createEnhancedEcommerceProductMapperPlugin()
            ->map($productViewTransfer)->toArray();

        return $twig->render($this->getTemplate(), [
            'data' => [
                $this->renderProductDetail($products),
            ],
        ]);
    }

    /**
     * @param array $products
     *
     * @return array
     */
    protected function renderProductDetail(array $products): array
    {
        $products = $this->stripEmptyValuesFromProductsArray($products);

        $enhancedEcommerceTransfer = (new EnhancedEcommerceTransfer())
            ->setEvent(EnhancedEcommerceConstants::EVENT_GENERIC)
            ->setEventCategory(EnhancedEcommerceConstants::EVENT_CATEGORY)
            ->setEventAction(EnhancedEcommerceConstants::EVENT_PRODUCT_DETAIL)
            ->setEventLabel($products[0]['id'])
            ->setEcommerce([
                'detail' => [
                    'actionField' => [],
                    'products' => $products
                ],
            ]);

        return $enhancedEcommerceTransfer->toArray();
    }

    /**
     * @param array $products
     *
     * @return array
     */
    protected function stripEmptyValuesFromProductsArray(array $products): array
    {
        foreach ($products as $index => $product) {
            foreach ($product as $key => $value) {
                if ($value !== 0 && !$value) {
                    unset($products[$index][$key]);
                }
            }
        }

        return $products;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-default.twig';
    }
}
