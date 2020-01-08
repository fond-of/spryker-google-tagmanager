<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler\Checkout;

use FondOfSpryker\Client\GoogleTagManager\GoogleTagManagerClientInterface;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Business\ControllerEventHandler\ControllerEventHandlerInterface;
use Symfony\Component\HttpFoundation\Request;

class SummaryControllerEventHandler implements ControllerEventHandlerInterface
{
    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'summaryAction';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    public function handle(Request $request, GoogleTagManagerClientInterface $client, string $locale): void
    {
        $quoteTransfer = $client->getCartClient()->getQuote();

        return;
    }

    /**
     * @return array
     */
    protected function getEnhancedEcommerceCheckoutOption(string $paymentProvider): array
    {
        return [
            'event' => 'eec.checkout_option',
            'ecommerce' => [
                'checkout_option' => [
                    'actionField' => [
                        'step' => GoogleTagManagerConstants::EEC_CHECKOUT_STEP_SUMMARY,
                        'option' => $paymentProvider,
                    ],
                ],
            ],
        ];
    }
}
