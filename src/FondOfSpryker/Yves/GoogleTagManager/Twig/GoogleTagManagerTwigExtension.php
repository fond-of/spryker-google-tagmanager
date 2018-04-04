<?php

namespace FondOFSpryker\Yves\GoogleTagManager\Twig;

use FondOfSpryker\Yves\GoogleTagManager\DataLayer\Variable;
use FondOfSpryker\Yves\GoogleTagManager\DataLayer\VariableInterface;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Shared\Twig\TwigExtension;
use Twig_Environment;
use Twig_SimpleFunction;

class GoogleTagManagerTwigExtension extends TwigExtension
{
    const FUNCTION_GOOGLE_TAG_MANAGER   = 'fondOfSpykerGoogleTagManager';
    const FUNCTION_DATA_LAYER           = 'fondOfSpykerDataLayer';

    private $dataLayerVariables = [];

    /**
     * @var string
     */
    protected $containerID;

    /**
     * @var \Spryker\Client\Cart\CartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Yves\Cart\CartFactory
     */
    protected $cartFactory;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $productClient;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\DataLayer\VariableInterface
     */
    protected $variable;

    /**
     * @param string $containerID
     * @param \Spryker\Yves\Kernel\Application $application
     */
    public function __construct(
        string $containerID,
        VariableInterface $variable,
        CartClientInterface $cartClient
    )
    {
        $this->containerID = $containerID;
        $this->cartClient = $cartClient;
        $this->variable = $variable;
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
     * @param Twig_Environment $twig
     * @param $templateName
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderGoogleTagManager(Twig_Environment $twig, $templateName): string
    {
        if (!$this->containerID) {
            return '';
        }

        return $twig->render($templateName, [
            'containerID' => $this->containerID,
        ]);
    }

    /**
     * @param Twig_Environment $twig
     * @param $page
     * @param $params
     * @return string
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function renderDataLayer(Twig_Environment $twig, $page, $params): string
    {
        $this->addDefaultVariables($page);

        if ($page == Variable::PAGE_TYPE_PRODUCT) {
            $this->addProductVariables($params['product']);
        }

        if ($page == Variable::PAGE_TYPE_CATEGORY) {
            $this->addCategoryVariables($params['category'], $params['products']);
        }

        if ($page == Variable::PAGE_TYPE_ORDER) {
            $this->addOrderVariables($params['quote']);
        }else{
            $this->addQuoteVariables();
        }

        return $twig->render($this->getTemplateName(), [
            'data' => $this->dataLayerVariables
        ]);
    }


    /**
     * @param $page
     * @return array
     */
    private function addDefaultVariables($page) : array
    {
        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variable->getDefaultVariables($page)
        );
    }

    /**
     * @param $product
     * @return array
     */
    private function addProductVariables($product) : array
    {
        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variable->getProductVariables($product)
        );

    }

    /**
     * @param $category
     * @param $products
     * @return array
     */
    private function addCategoryVariables($category, $products) : array
    {
        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variable->getCategoryVariables($category, $products)
        );
    }

    /**
     * @return array
     */
    private function addQuoteVariables() : array
    {
        $quoteTransfer = $this->cartClient->getQuote();

        if (!$quoteTransfer || count($quoteTransfer->getItems()) == 0 ) {
            return $this->dataLayerVariables;
        }

        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variable->getQuoteVariables($quoteTransfer)
        );
    }

    private function addOrderVariables($quote)
    {
        return $this->dataLayerVariables = array_merge(
            $this->dataLayerVariables,
            $this->variable->getOrderVariables($quote)
        );
    }

    /**
     * @return string
     */
    private function getTemplateName() : string
    {
        return '@GoogleTagManager/partials/data-layer.twig';
    }
}