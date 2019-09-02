<?php

/**
 * Google Tag Manager tracking integration for Spryker
 *
 * @author      Jozsef Geng <gengjozsef86@gmail.com>
 */

namespace FondOfSpryker\Yves\GoogleTagManager\Twig;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Shared\Twig\TwigExtension;
use Twig_Environment;
use Twig_SimpleFunction;

class GoogleTagManagerTwigExtension extends TwigExtension
{
    const FUNCTION_GOOGLE_TAG_MANAGER = 'googleTagManager';
    const FUNCTION_DATA_LAYER = 'dataLayer';

    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @var string
     */
    protected $containerID;

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\Session\SessionClientInterface
     */
    protected $sessionClient;

    /**
     * @var \Spryker\Yves\Cart\CartFactory
     */
    protected $cartFactory;

    /**
     * @var array
     */
    protected $dataLayerVariables = [];

    /**
     * @var bool
     */
    protected $isEnabled;

    /**
     * @var array
     */
    protected $variableBuilders;

    /**
     * GoogleTagManagerTwigExtension constructor.
     *
     * @param string $containerID
     * @param bool $isEnabled
     * @param array $variableBuilders
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    public function __construct(
        string $containerID,
        bool $isEnabled,
        array $variableBuilders,
        CartClientInterface $cartClient,
        SessionClientInterface $sessionClient
    ) {
        $this->sessionClient = $sessionClient;
        $this->containerID = $containerID;
        $this->cartClient = $cartClient;
        $this->isEnabled = $isEnabled;
        $this->variableBuilders = $variableBuilders;
    }

    /**
     * @return array
     */
    public function getFunctions()
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
     * @param \Twig_Environment $twig
     * @param string $templateName
     *
     * @throws
     *
     * @return string
     */
    public function renderGoogleTagManager(Twig_Environment $twig, $templateName): string
    {
        if (!$this->isEnabled || !$this->containerID) {
            return '';
        }

        return $twig->render($templateName, [
            'containerID' => $this->containerID,
        ]);
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $page
     * @param array $params
     *
     * @throws
     *
     * @return string
     */
    public function renderDataLayer(Twig_Environment $twig, $page, $params): string
    {
        if (!$this->isEnabled || !$this->containerID) {
            return '';
        }

        $this->addDefaultVariables($page);

        switch ($page) {
            case GoogleTagManagerConstants::PAGE_TYPE_PRODUCT:
                $productAbstractTransfer = (new ProductAbstractTransfer())
                    ->fromArray($params['product']->toArray(), true);

                $this->addProductVariables($productAbstractTransfer);
                break;

            case GoogleTagManagerConstants::PAGE_TYPE_CATEGORY:
                $this->addCategoryVariables($params['category'], $params['products']);
                break;

            case GoogleTagManagerConstants::PAGE_TYPE_ORDER:
                $this->addOrderVariables($params['order']);
                break;

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
        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variableBuilders[GoogleTagManagerConstants::PAGE_TYPE_DEFAULT]->getVariable($page)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $product
     *
     * @return array
     */
    protected function addProductVariables(ProductAbstractTransfer $product): array
    {
        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variableBuilders[GoogleTagManagerConstants::PAGE_TYPE_PRODUCT]->getVariables($product)
        );
    }

    /**
     * @param array $category
     * @param array $products
     *
     * @return array
     */
    protected function addCategoryVariables($category, $products): array
    {
        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variableBuilders[GoogleTagManagerConstants::PAGE_TYPE_CATEGORY]->getVariables($category, $products)
        );
    }

    /**
     * @return array
     */
    protected function addQuoteVariables(): array
    {
        $quoteTransfer = $this->cartClient->getQuote();

        if (!$quoteTransfer || count($quoteTransfer->getItems()) == 0) {
            return $this->dataLayerVariables;
        }

        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variableBuilders[GoogleTagManagerConstants::PAGE_TYPE_QUOTE]->getVariables($quoteTransfer, $this->sessionClient->getId())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function addOrderVariables(OrderTransfer $orderTransfer)
    {
        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variableBuilders[GoogleTagManagerConstants::PAGE_TYPE_ORDER]->getVariables($orderTransfer)
        );
    }

    /**
     * @return string
     */
    protected function getDataLayerTemplateName(): string
    {
        return '@GoogleTagManager/partials/data-layer.twig';
    }
}
