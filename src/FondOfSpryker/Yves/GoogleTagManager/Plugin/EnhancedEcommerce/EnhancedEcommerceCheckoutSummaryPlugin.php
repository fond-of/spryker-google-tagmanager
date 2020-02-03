<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhancedEcommerceCheckoutSummaryPlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
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
        return $twig->render($this->getTemplate(), [
            'data' => [
                $this->renderCheckoutPaymentSelection()->toArray(),
                $this->renderSummary()->toArray(),
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-default.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return EnhancedEcommerceTransfer
     */
    protected function renderCheckoutPaymentSelection(): EnhancedEcommerceTransfer
    {
        $cartClient = $this->getFactory()->getCartClient();
        $quoteTransfer = $cartClient->getQuote();

        $enhancedEcommerceTransfer = new EnhancedEcommerceTransfer();
        $enhancedEcommerceTransfer->setEvent(GoogleTagManagerConstants::EEC_EVENT_CHECKOUT_OPTION);
        $enhancedEcommerceTransfer->setEcommerce([
            'checkout_option' => [
                'actionField' => [
                    'step' => GoogleTagManagerConstants::EEC_CHECKOUT_STEP_PAYMENT,
                    'option' => $quoteTransfer->getPayment() instanceof PaymentTransfer
                        ? $quoteTransfer->getPayment()->getPaymentProvider() : '',
                ],
            ],
        ]);

        return $enhancedEcommerceTransfer;
    }

    /**
     * @return EnhancedEcommerceTransfer
     */
    protected function renderSummary(): EnhancedEcommerceTransfer
    {
        $enhancedEcommerceTransfer = new EnhancedEcommerceTransfer();
        $enhancedEcommerceTransfer->setEvent(GoogleTagManagerConstants::EEC_EVENT_CHECKOUT);
        $enhancedEcommerceTransfer->setEcommerce([
            'checkout' => [
                'actionField' => [
                    'step' => GoogleTagManagerConstants::EEC_CHECKOUT_STEP_SUMMARY,
                ],
            ],
        ]);

        return $enhancedEcommerceTransfer;
    }
}
