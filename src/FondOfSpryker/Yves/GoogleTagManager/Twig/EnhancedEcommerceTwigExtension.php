<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Twig;

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
     *
     * @return string
     */
    public function renderEnhancedEcommerce(Twig_Environment $twig, string $page, ?Request $request, array $params = []): string
    {
        if (array_key_exists($page, $this->plugin)) {
            return $this->plugin[$page]->handle($twig, $request, $params);
        }

        return '';
    }

    /**
     * @return string
     */
    protected function getEnhancedMicrodateTemplateName(): string
    {
        return '@GoogleTagManager/partials/enhanced-ecommerce.twig';
    }
}
