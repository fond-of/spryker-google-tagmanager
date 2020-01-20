<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\FactoryResolverAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AddProductControllerEventHandler
 * @package FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class AddProductControllerEventHandler implements ControllerEventHandlerInterface
{
    use FactoryResolverAwareTrait;

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'addAction';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface $client
     * @param string $locale
     *
     * @return void
     */
    public function handle(Request $request, GoogleTagManagerClientInterface $client, string $locale): void
    {
        $sku = $request->get('sku');

        $productConcreteData = $client->getProductResourceAliasStorageClient()
            ->findProductConcreteStorageDataBySku($sku, $locale);

        if (!isset($productConcreteData['id_product_abstract'])) {
            return;
        }

        $productDataAbstract = $client->getProductStorageClient()
            ->findProductAbstractStorageData($productConcreteData['id_product_abstract'], $locale);

        $productViewTransfer = $client->getProductStorageClient()
            ->mapProductStorageData($productDataAbstract, $locale, []);

        $sessionHandler = $this->getFactory()->createEnhancedEcommerceSessionHandler();
        $sessionHandler->addProductToAddProductEvent();

        $addProductEventArray = $request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_ADD)
            ? unserialize($request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_ADD))
            : $this->getEnhancedEcommerceAddProductEventArray();

        $addProductEventArray = $this->addProduct($addProductEventArray, $productViewTransfer);
        $this->storeInSession($request, $addProductEventArray);

        return;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $eec
     *
     * @return void
     */
    protected function storeInSession(Request $request, array $addProductEventArray): void
    {
        $request->getSession()->set(GoogleTagManagerConstants::EEC_EVENT_ADD, serialize($addProductEventArray));
    }

    /**
     * @return array
     */
    protected function getEnhancedEcommerceAddProductEventArray(): array
    {
        $enhancedEcommerceTransfer = new EnhancedEcommerceTransfer();
        $enhancedEcommerceTransfer->setEvent(GoogleTagManagerConstants::EEC_EVENT_ADD);
        $enhancedEcommerceTransfer->setEcommerce([
            'add' => [
                'actionField' => ['list' => 'Shopping cart'],
                'products' => [],
            ],
        ]);

        return $enhancedEcommerceTransfer->toArray();
    }

    /**
     * @param array $addProductEventArray
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param int $quantity
     *
     * @return array
     */
    protected function addProduct(array $addProductEventArray, ProductViewTransfer $productViewTransfer, int $quantity = 1): array
    {
        foreach ($addProductEventArray['ecommerce']['add']['products'] as $index => $product) {
            if ($product[GoogleTagManagerConstants::EEC_PRODUCT_ID] === $productViewTransfer->getSku()) {
                $addProductEventArray['ecommerce']['add']['products'][$index][GoogleTagManagerConstants::EEC_PRODUCT_QUNATITY] + $quantity;

                return $addProductEventArray;
            }
        }

        array_push($addProductEventArray['ecommerce']['add']['products'], [
            GoogleTagManagerConstants::EEC_PRODUCT_ID => $productViewTransfer->getSku(),
            GoogleTagManagerConstants::EEC_PRODUCT_NAME => $productViewTransfer->getAttributes()['model'],
            GoogleTagManagerConstants::EEC_PRODUCT_VARIANT => $productViewTransfer->getAttributes()['style'],
            GoogleTagManagerConstants::EEC_PRODUCT_BRAND => $productViewTransfer->getAttributes()['brand'],
            GoogleTagManagerConstants::EEC_PRODUCT_QUNATITY => $quantity,
            GoogleTagManagerConstants::EEC_PRODUCT_DIMENSION1 => $productViewTransfer->getAttributes()['size'],
            GoogleTagManagerConstants::EEC_PRODUCT_PRICE => $productViewTransfer->getPrice() / 100,
        ]);

        return $addProductEventArray;
    }
}
