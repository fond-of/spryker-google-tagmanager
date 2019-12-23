<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler\Cart;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler\ControllerEventHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class RemoveProductControllerEventHandler implements ControllerEventHandlerInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'removeAction';
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

        $product = $client->getProductResourceAliasStorageClient()
            ->findProductConcreteStorageDataBySku($sku, $locale);

        $removeProductEventArray = $this->getEnhancedEcommerceRemoveProductEventArray();
        $removeProductEventArray = $this->removeProduct($removeProductEventArray, $product);

        $this->storeInSession($request, $removeProductEventArray);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $removeProductEventArray
     *
     * @return void
     */
    protected function storeInSession(Request $request, array $removeProductEventArray): void
    {
        $request->getSession()->set(GoogleTagManagerConstants::EEC_EVENT_REMOVE, serialize($removeProductEventArray));
    }

    /**
     * @return array
     */
    protected function getEnhancedEcommerceRemoveProductEventArray(): array
    {
        return [
            'event' => 'eec.remove',
            'ecommerce' => [
                'remove' => [
                    'actionField' => ['list' => 'Shopping cart'],
                    'products' => [],
                ],
            ],
        ];
    }

    /**
     * @param array $removeProductEventArray
     * @param array $addToProduct
     *
     * @return array
     */
    protected function removeProduct(array $removeProductEventArray, array $removeProduct): array
    {
        array_push($removeProductEventArray['ecommerce']['remove']['products'], [
            GoogleTagManagerConstants::EEC_PRODUCT_ID => $removeProduct['sku'],
            GoogleTagManagerConstants::EEC_PRODUCT_NAME => $removeProduct['attributes']['model'],
            GoogleTagManagerConstants::EEC_PRODUCT_VARIANT => $removeProduct['attributes']['style'],
            GoogleTagManagerConstants::EEC_PRODUCT_BRAND => $removeProduct['attributes']['brand'],
            GoogleTagManagerConstants::EEC_PRODUCT_QUNATITY => 1,
            GoogleTagManagerConstants::EEC_PRODUCT_DIMENSION1 => $removeProduct['attributes']['size'],
        ]);

        return $removeProductEventArray;
    }
}
