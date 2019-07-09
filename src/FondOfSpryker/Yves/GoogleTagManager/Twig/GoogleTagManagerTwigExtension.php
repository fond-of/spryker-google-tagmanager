<?php

/**
 * Google Tag Manager tracking integration for Spryker
 *
 * @author      Jozsef Geng <gengjozsef86@gmail.com>
 */

namespace FondOFSpryker\Yves\GoogleTagManager\Twig;

use FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\VariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\VariableBuilderInterface;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Tax\TaxConstants;
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
     * @var \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\VariableBuilderInterface
     */
    protected $variableBuilder;

    /**
     * GoogleTagManagerTwigExtension constructor
     *
     * @param string $containerID
     * @param bool $isEnabled
     * @param \FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\VariableBuilderInterface $variableBuilder
     * @param \Spryker\Client\Cart\CartClientInterface $cartClient
     * @param \Spryker\Client\Session\SessionClientInterface $sessionClient
     */
    public function __construct(
        string $containerID,
        bool $isEnabled,
        VariableBuilderInterface $variableBuilder,
        CartClientInterface $cartClient,
        SessionClientInterface $sessionClient
    ) {
        $this->sessionClient = $sessionClient;
        $this->containerID = $containerID;
        $this->cartClient = $cartClient;
        $this->isEnabled = $isEnabled;
        $this->variableBuilder = $variableBuilder;
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
     * @return string
     */
    public function renderDataLayer(Twig_Environment $twig, $page, $params): string
    {
        if (!$this->isEnabled || !$this->containerID) {
            return '';
        }

        $this->addDefaultVariables($page);

        if ($page == VariableBuilder::PAGE_TYPE_PRODUCT) {
            /**
             * @todo refractor using plugin
             */
            $product = $this->mapProductViewTransferToProductAbstratTransfer($params['product']);
            $this->addProductVariables($product);
        }

        if ($page == VariableBuilder::PAGE_TYPE_CATEGORY) {
            $this->addCategoryVariables($params['category'], $params['products']);
        }

        if ($page == VariableBuilder::PAGE_TYPE_ORDER) {
            $this->addOrderVariables($params['order']);
        } else {
            $this->addQuoteVariables();
        }

        return $twig->render($this->getDataLayerTemplateName(), [
            'data' => $this->dataLayerVariables,
        ]);
    }

    /**
     * @todo optimize code using a plugin
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $product
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function mapProductViewTransferToProductAbstratTransfer(ProductViewTransfer $productViewTransfer): ProductAbstractTransfer
    {
        $productAbstractTransfer = new ProductAbstractTransfer();

        $productAbstractTransfer->setPrice($productViewTransfer->getPrice());
        $productAbstractTransfer->setSku($productViewTransfer->getSku());
        $productAbstractTransfer->setIdProductAbstract($productViewTransfer->getIdProductAbstract());
        $productAbstractTransfer->setTaxRate(Config::get(TaxConstants::DEFAULT_TAX_RATE));
        $productAbstractTransfer->setAttributes($productViewTransfer->getAttributes());

        return $productAbstractTransfer;
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
            $this->variableBuilder->getDefaultVariables($page)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\StorageProductTransfer $product
     *
     * @return array
     */
    protected function addProductVariables($product): array
    {
        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variableBuilder->getProductVariables($product)
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
            $this->variableBuilder->getCategoryVariables($category, $products)
        );
    }

    /**
     * @return array
     */
    protected function addQuoteVariables(): array
    {
        /**
         * @var Generated\Shared\Transfer\QuoteTransfer
         */
        $quoteTransfer = $this->cartClient->getQuote();

        if (!$quoteTransfer || count($quoteTransfer->getItems()) == 0) {
            return $this->dataLayerVariables;
        }

        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variableBuilder->getQuoteVariables($quoteTransfer, $this->sessionClient->getId())
        );
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return array
     */
    protected function addOrderVariables($orderTransfer)
    {
        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variableBuilder->getOrderVariables($orderTransfer)
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
