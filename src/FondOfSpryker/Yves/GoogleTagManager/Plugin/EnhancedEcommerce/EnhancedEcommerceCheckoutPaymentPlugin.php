<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use Generated\Shared\Transfer\EnhancedEcommerceTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class EnhancedEcommerceCheckoutPaymentPlugin extends AbstractPlugin implements EnhancedEcommercePageTypePluginInterface
{
    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce-default.twig';
    }

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
        $enhancedEcommerceTransfer = (new EnhancedEcommerceTransfer())
            ->setEvent(EnhancedEcommerceConstants::EVENT_GENERIC)
            ->setEventCategory(EnhancedEcommerceConstants::EVENT_CATEGORY)
            ->setEventAction(EnhancedEcommerceConstants::EVENT_CHECKOUT)
            ->setEventLabel(EnhancedEcommerceConstants::CHECKOUT_STEP_PAYMENT)
            ->setEcommerce([
                    EnhancedEcommerceConstants::EVENT_CHECKOUT => [
                        'actionField' => [
                            'step' => EnhancedEcommerceConstants::CHECKOUT_STEP_PAYMENT,
                        ],
                    ],
                ]);

        return $twig->render($this->getTemplate(), [
            'data' => [
                $this->stripEmptyArrayIndex($enhancedEcommerceTransfer),
            ],
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\EnhancedEcommerceTransfer $transfer
     *
     * @return array
     */
    protected function stripEmptyArrayIndex(EnhancedEcommerceTransfer $transfer): array
    {
        $result = [];

        foreach ($transfer->toArray() as $key => $value) {
            if (!$value) {
                continue;
            }

            $result[$key] = $value;
        }

        return $result;
    }
}
