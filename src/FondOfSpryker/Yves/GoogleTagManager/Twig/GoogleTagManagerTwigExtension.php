<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Twig;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Tax\TaxConstants;
use SprykerShop\Yves\ShopApplication\Plugin\AbstractTwigExtensionPlugin;
use Twig\Environment;
use Twig_SimpleFunction;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()
 */
class GoogleTagManagerTwigExtension extends AbstractTwigExtensionPlugin
{
    public const FUNCTION_GOOGLE_TAG_MANAGER = 'googleTagManager';
    public const FUNCTION_DATA_LAYER = 'dataLayer';

    /**
     * @var array
     */
    protected $dataLayerVariables = [];

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            $this->createGoogleTagManagerFunction(),
            $this->createDataLayerFunction(),
        ];
    }

    /**
     * @return \Twig_SimpleFunction
     */
    protected function createGoogleTagManagerFunction()
    {
        return new Twig_SimpleFunction(
            static::FUNCTION_GOOGLE_TAG_MANAGER,
            [$this, 'renderGoogleTagManager'],
            [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]
        );
    }

    /**
     * @return \Twig_SimpleFunction
     */
    protected function createDataLayerFunction()
    {
        return new Twig_SimpleFunction(
            static::FUNCTION_DATA_LAYER,
            [$this, 'renderDataLayer'],
            [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]
        );
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $templateName
     *
     * @return string
     */
    public function renderGoogleTagManager(Environment $twig, $templateName): string
    {
        $config = $this->getConfig();

        if (!$config->isEnabled() || !$config->getContainerID()) {
            return '';
        }

        return $twig->render($templateName, [
            'containerID' => $config->getContainerID(),
        ]);
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $page
     * @param array $params
     *
     * @return string
     */
    public function renderDataLayer(Environment $twig, $page, $params): string
    {
        if (!$this->getConfig()->isEnabled() || !$this->getConfig()->getContainerID()) {
            return '';
        }

        $this->addDefaultVariables($page);

        switch ($page) {
            case GoogleTagManagerConstants::PAGE_TYPE_PRODUCT:
                $productAbstractTransfer = (new ProductAbstractTransfer())
                    ->setTaxRate(Config::get(TaxConstants::DEFAULT_TAX_RATE))
                    ->fromArray($params['product']->toArray(), true);

                $this->addProductVariables($productAbstractTransfer);
                $this->addQuoteVariables();

                break;
            case GoogleTagManagerConstants::PAGE_TYPE_CATEGORY:
                $this->addCategoryVariables(
                    $params['category'],
                    $params['products'],
                    $params[GoogleTagManagerConstants::CATEGORY_CONTENT_TYPE]
                );
                $this->addQuoteVariables();

                break;

            /*case GoogleTagManagerConstants::PAGE_TYPE_ORDER:
                $this->addOrderVariables($params['order']);

                break;

            case GoogleTagManagerConstants::PAGE_TYPE_NEWSLETTER_SUBSCRIBE:
                $this->addNewsletterSubscribeVariables($page);

                break;*/

            default:
                $this->addQuoteVariables();

                break;
        }

        return $twig->render($this->getDataLayerTemplateName(), [
            'data' => $this->dataLayerVariables,
        ]);
    }

    /**
     * @param string $page
     *
     * @return array
     */
    protected function addDefaultVariables($page): array
    {
        $defaultVariableBuilder = $this->getFactory()
            ->getDefaultVariableBuilderPlugin();

        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $defaultVariableBuilder->getVariable($page, [
                'clientIp' => $this->getClientIpAddress(),
            ])
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return array
     */
    protected function addProductVariables(ProductAbstractTransfer $product): array
    {
        $productVariableBuilder = $this->getFactory()
            ->getProductVariableBuilder();

        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $productVariableBuilder->getVariables($product)
        );
    }

    /**
     * @param array $category
     * @param array $products
     * @param string $contentType
     *
     * @return array
     */
    protected function addCategoryVariables($category, $products, string $contentType): array
    {
        $categoryVariableBuilder = $this->getFactory()->getCategoryVariableBuilderPlugin();

        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $categoryVariableBuilder->getVariables($category, $products, [
                'contentType' => $contentType,
            ])
        );
    }

    /**
     * @return array
     */
    protected function addQuoteVariables(): array
    {
        $quoteVariableBuilder = $this->getFactory()
            ->getQuoteVariableBuilderPlugin();

        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        if (count($quoteTransfer->getItems()) === 0) {
            return $this->dataLayerVariables;
        }

        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $quoteVariableBuilder->getVariables($quoteTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function addOrderVariables(OrderTransfer $orderTransfer): array
    {
        $orderVariableBuilder = $this->getFactory()->createOrderVariableBuilder();

        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $orderVariableBuilder->getVariables($orderTransfer)
        );
    }

    /**
     * @param string $page
     *
     * @return array
     */
    protected function addNewsletterSubscribeVariables(string $page): array
    {
        $newsletterVariableBuilder = $this->getFactory()->getNewsletterVariableBuilder();

        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $newsletterVariableBuilder->getVariables($page)
        );
    }

    /**
     * @return string
     */
    protected function getDataLayerTemplateName(): string
    {
        return '@GoogleTagManager/partials/data-layer.twig';
    }

    /**
     * @return string|null
     */
    protected function getClientIpAddress(): ?string
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'];

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return $ipAddress;
    }
}
