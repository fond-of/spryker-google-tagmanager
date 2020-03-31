<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
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
                $this->getCheckoutPaymentEvent()->toArray(),
                $this->getSummaryEvent()->toArray(),
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
     * @return \Generated\Shared\Transfer\EnhancedEcommerceTransfer
     */
    protected function getCheckoutPaymentEvent(): EnhancedEcommerceTransfer
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        $enhancedEcommerceTransfer = (new EnhancedEcommerceTransfer())
            ->setEvent(EnhancedEcommerceConstants::EVENT_GENERIC)
            ->setEventCategory(EnhancedEcommerceConstants::EVENT_CATEGORY)
            ->setEventAction(EnhancedEcommerceConstants::EVENT_CHECKOUT_OPTION)
            ->setEventLabel(EnhancedEcommerceConstants::CHECKOUT_STEP_PAYMENT)
            ->setEcommerce([
                    EnhancedEcommerceConstants::EVENT_CHECKOUT_OPTION => [
                        'actionField' => [
                            'step' => EnhancedEcommerceConstants::CHECKOUT_STEP_PAYMENT,
                            'option' => $quoteTransfer->getPayment() instanceof PaymentTransfer
                                ? $quoteTransfer->getPayment()->getPaymentProvider() : '',
                        ],
                    ],
                ]);

        return $enhancedEcommerceTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\EnhancedEcommerceTransfer
     */
    protected function getSummaryEvent(): EnhancedEcommerceTransfer
    {
        $enhancedEcommerceTransfer = (new EnhancedEcommerceTransfer())
            ->setEvent(EnhancedEcommerceConstants::EVENT_GENERIC)
            ->setEventCategory(EnhancedEcommerceConstants::EVENT_CATEGORY)
            ->setEventAction(EnhancedEcommerceConstants::EVENT_CHECKOUT)
            ->setEventLabel(EnhancedEcommerceConstants::CHECKOUT_STEP_SUMMARY)
            ->setEcommerce([
                    EnhancedEcommerceConstants::EVENT_CHECKOUT => [
                        EnhancedEcommerceConstants::EVENT_CHECKOUT => [
                            'actionField' => [
                                'step' => EnhancedEcommerceConstants::CHECKOUT_STEP_SUMMARY,
                            ],
                        ],
                    ],
                ]);

        return $enhancedEcommerceTransfer;
    }
}
