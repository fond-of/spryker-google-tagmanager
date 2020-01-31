<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Checkout;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use Spryker\Yves\Kernel\FactoryResolverAwareTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class SummaryControllerEventHandler implements ControllerEventHandlerInterface
{
    use FactoryResolverAwareTrait;

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return 'summaryAction';
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $locale
     *
     * @return void
     */
    public function handle(Request $request, string $locale): void
    {
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
