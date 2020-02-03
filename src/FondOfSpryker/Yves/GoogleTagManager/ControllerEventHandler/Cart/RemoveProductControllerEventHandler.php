<?php


namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use Spryker\Yves\Kernel\FactoryResolverAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class RemoveProductControllerEventHandler implements ControllerEventHandlerInterface
{
    use FactoryResolverAwareTrait;

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'removeAction';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $locale
     *
     * @return void
     */
    public function handle(Request $request, string $locale): void
    {
        $sku = $request->get('sku');

        if (!$sku) {
            return;
        }

        $productConcreteData = $this->getFactory()
            ->getProductResourceAliasStorageClient()
            ->findProductConcreteStorageDataBySku($sku, $locale);

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
        $sessionHandler->removeProduct($productViewTransfer);
    }
}