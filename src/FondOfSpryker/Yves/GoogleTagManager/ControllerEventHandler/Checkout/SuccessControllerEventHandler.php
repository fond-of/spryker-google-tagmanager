<?php


namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Checkout;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use Spryker\Yves\Kernel\FactoryResolverAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class SuccessControllerEventHandler implements ControllerEventHandlerInterface
{
    use FactoryResolverAwareTrait;

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'successAction';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $locale
     *
     * @return void
     */
    public function handle(Request $request, string $locale): void
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        $request->getSession()->set(GoogleTagManagerConstants::EEC_PAGE_TYPE_PURCHASE, $quoteTransfer);
    }
}
