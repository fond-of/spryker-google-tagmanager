<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Symfony\Component\HttpFoundation\Request;

class AddProductControllerEventHandler implements ControllerEventHandlerInterface
{
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

        $product = $client->findProductConcreteStorageDataBySku($sku, $locale);

        $addProductEventArray = $request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_ADD)
            ? unserialize($request->getSession()->get(GoogleTagManagerConstants::EEC_EVENT_ADD))
            : $this->getEnhancedEcommerceAddProductEventArray();

        $addProductEventArray = $this->addProduct($addProductEventArray, $product);
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
        return [
            'event' => 'eec.add',
            'ecommerce' => [
                'add' => [
                    'actionField' => ['list' => 'Shopping cart'],
                    'products' => [],
                ],
            ],
        ];
    }

    /**
     * @param array $eec
     * @param array $addProduct
     *
     * @return array
     */
    protected function addProduct(array $eec, array $addProduct): array
    {
        foreach ($eec['ecommerce']['add']['products'] as $index => $product) {
            if ($product[GoogleTagManagerConstants::EEC_PRODUCT_ID] === $addProduct['sku']) {
                $eec['ecommerce']['add']['products'][$index][GoogleTagManagerConstants::EEC_PRODUCT_QUNATITY]++;

                return $eec;
            }
        }

        array_push($eec['ecommerce']['add']['products'], [
            GoogleTagManagerConstants::EEC_PRODUCT_ID => $addProduct['sku'],
            GoogleTagManagerConstants::EEC_PRODUCT_NAME => $addProduct['attributes']['model'],
            GoogleTagManagerConstants::EEC_PRODUCT_VARIANT => $addProduct['attributes']['style'],
            GoogleTagManagerConstants::EEC_PRODUCT_BRAND => $addProduct['attributes']['brand'],
            GoogleTagManagerConstants::EEC_PRODUCT_QUNATITY => 1,
            GoogleTagManagerConstants::EEC_PRODUCT_DIMENSION1 => $addProduct['attributes']['size'],
        ]);

        return $eec;
    }
}
