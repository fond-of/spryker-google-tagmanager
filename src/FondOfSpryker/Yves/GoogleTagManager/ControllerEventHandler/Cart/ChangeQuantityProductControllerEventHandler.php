<?php


namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use Spryker\Yves\Kernel\FactoryResolverAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class ChangeQuantityProductControllerEventHandler implements ControllerEventHandlerInterface
{
    use FactoryResolverAwareTrait;

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'changeAction';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function handle(Request $request, GoogleTagManagerClientInterface $client, string $locale): void
    {
        $sku = $request->get('sku');
        $newQuantity = $request->get('quantity');

        if (!$sku || !$newQuantity) {
            return;
        }

        $productConcreteData = $this->getFactory()
            ->getProductResourceAliasStorageClient()
            ->getProductConcreteStorageDataBySku($sku, $locale);

        if (!isset($productConcreteData['id_product_abstract'])) {
            return;
        }

        $productDataAbstract = $this->getFactory()
            ->getProductStorageClient()
            ->findProductAbstractStorageData($productConcreteData['id_product_abstract'], $locale);

        $productViewTransfer = $this->getFactory()
            ->getProductStorageClient()
            ->mapProductStorageData($productDataAbstract, $locale, []);

        $sessionHandler = $this->getFactory()->createEnhancedEcommerceSessionHandler();
        $sessionHandler->changeProductQuantity($productViewTransfer, $newQuantity);
    }
}
