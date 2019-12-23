<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Twig;

use Exception;
use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Spryker\Shared\Twig\TwigExtension;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;
use Twig_SimpleFunction;

class EnhancedEcommerceTwigExtension extends TwigExtension
{
    public const FUNCTION_ENHANCED_ECOMMERCE = 'enhancedEcommerce';

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommercePageTypePluginInterface[]
     */
    protected $plugin;

    public function __construct(array $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            $this->createEnhancedEcommerceFunction(),
        ];
    }

    /**
     * @return \Twig_SimpleFunction
     */
    protected function createEnhancedEcommerceFunction(): Twig_SimpleFunction
    {
            return new Twig_SimpleFunction(
                static::FUNCTION_ENHANCED_ECOMMERCE,
                [$this, 'renderEnhancedEcommerce'],
                [
                    'is_safe' => ['html'],
                    'needs_environment' => true,
                ]
            );
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $page
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param array $params
     *
     * @throws
     * @throws \Exception
     *
     * @return string
     */
    public function renderEnhancedEcommerce(Twig_Environment $twig, string $page, ?Request $request, array $params = []): string
    {
        switch ($page) {
            case GoogleTagManagerConstants::EEC_PAGE_TYPE_CART:
                return $this->plugin[GoogleTagManagerConstants::EEC_PAGE_TYPE_CART]->handle($twig, $request, $params);

            case GoogleTagManagerConstants::EEC_PAGE_TYPE_PRODUCT_DETAIL:
                return $this->plugin[GoogleTagManagerConstants::EEC_PAGE_TYPE_PRODUCT_DETAIL]->handle($twig, $request, $params);
                break;

            case GoogleTagManagerConstants::EEC_PAGE_TYPE_CHECKOUT_BILLING_ADDRESS:
                return $this->plugin[GoogleTagManagerConstants::EEC_PAGE_TYPE_CHECKOUT_BILLING_ADDRESS]->handle($twig, $request, $params);
                break;

            case GoogleTagManagerConstants::EEC_PAGE_TYPE_PURCHASE:
                return $this->plugin[GoogleTagManagerConstants::EEC_PAGE_TYPE_PURCHASE]->handle($twig, $request, $params);
                break;
        }

        throw new Exception(sprintf('no plugin for enhanced ecommerce found %s', __METHOD__));
    }

    /**
     * @return string
     */
    protected function getEnhancedMicrodateTemplateName(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce.twig';
    }
}
