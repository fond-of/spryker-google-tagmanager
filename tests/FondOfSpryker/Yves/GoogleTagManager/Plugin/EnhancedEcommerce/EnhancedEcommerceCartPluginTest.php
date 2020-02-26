<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory;
use FondOfSpryker\Yves\GoogleTagManager\Model\EnhancedEcommerce\ProductModelBuilderInterface;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

class EnhancedEcommerceCartPluginTest extends Unit
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $factoryMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommerceCartPlugin
     */
    protected $plugin;

    /**
     * @var \Twig_Environment|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $twigEnvironmentMock;

    /**
     * @var \Symfony\Component\HttpFoundation\Request|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $sessionHandlerMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Model\EnhancedEcommerce\ProductModelBuilderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productArrayBuilderMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productStorageClientMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configMock;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $this->factoryMock = $this->getMockBuilder(GoogleTagManagerFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(GoogleTagManagerConfig::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock->method('getEnhancedEcommerceLocale')->willReturn('en_US');

        $this->twigEnvironmentMock = $this->getMockBuilder(Twig_Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sessionHandlerMock = $this->getMockBuilder(EnhancedEcommerceSessionHandlerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productArrayBuilderMock = $this->getMockBuilder(ProductModelBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productStorageClientMock = $this->getMockBuilder(GoogleTagManagerToProductStorageClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new EnhancedEcommerceCartPlugin();
        $this->plugin->setFactory($this->factoryMock);
        $this->plugin->setConfig($this->configMock);
    }

    /**
     * @return void
     */
    public function testGetTemplate(): void
    {
        $this->assertEquals('@GoogleTagManager/partials/enhanced-ecommerce-default.twig', $this->plugin->getTemplate());
    }

    /**
     * @return void
     */
    public function testGetAddedProductsEventSuccess(): void
    {
        $addedProductsArray = include codecept_data_dir('AddedProductsArray.php');
        $addedProductsCompleteArray = include codecept_data_dir('AddedProductsCompleteArray.php');

        $this->twigEnvironmentMock
            ->method('render')
            ->willReturn('');

        $this->sessionHandlerMock->expects($this->atLeastOnce())
            ->method('getAddedProducts')
            ->willReturn($addedProductsArray);

        $this->productArrayBuilderMock->expects($this->atLeastOnce())
            ->method('handle')
            ->willReturn($addedProductsCompleteArray);

        $this->factoryMock->expects($this->atLeastOnce())
            ->method('createEnhancedEcommerceSessionHandler')
            ->willReturn($this->sessionHandlerMock);

        $this->factoryMock->expects($this->atLeastOnce())
            ->method('createEnhancedEcommerceProductArrayBuilder')
            ->willReturn($this->productArrayBuilderMock);

        $this->productArrayBuilderMock->expects($this->atLeastOnce())
            ->method('handle')
            ->with($addedProductsArray)
            ->willReturn($addedProductsCompleteArray);

        $this->plugin->handle($this->twigEnvironmentMock, $this->requestMock, []);

        $methodGetProductFromQuote = $this->getMethod('getAddedProductsEvent');
        $result = $methodGetProductFromQuote->invokeArgs($this->plugin, []);
        $this->assertNotCount(0, $result['ecommerce']['add']['products']);
    }

    /**
     * @return void
     */
    public function testGetAddedProductsEventFailureNoProductInSession(): void
    {
        $addedProductsArray = [];

        $this->twigEnvironmentMock
            ->method('render')
            ->willReturn('');

        $this->sessionHandlerMock->expects($this->atLeastOnce())
            ->method('getAddedProducts')
            ->willReturn($addedProductsArray);

        $this->factoryMock->expects($this->atLeastOnce())
            ->method('createEnhancedEcommerceSessionHandler')
            ->willReturn($this->sessionHandlerMock);

        $this->productArrayBuilderMock->expects($this->never())
            ->method('handle')
            ->with($addedProductsArray);

        $this->plugin->handle($this->twigEnvironmentMock, $this->requestMock, []);

        $methodGetProductFromQuote = $this->getMethod('getAddedProductsEvent');
        $result = $methodGetProductFromQuote->invokeArgs($this->plugin, []);
        $this->assertCount(0, $result);
    }

    /**
     * @return void
     */
    public function testGetRemovedProductsEventSuccess(): void
    {
        $removedProductsCompleteArray = include codecept_data_dir('RemovedProductsArray.php');
        $productAbstractDataArray = include codecept_data_dir('ProductAbstractDataArray.php');

        $this->twigEnvironmentMock
            ->method('render')
            ->willReturn('');

        $this->factoryMock->expects($this->atLeastOnce())
            ->method('getProductStorageClient')
            ->willReturn($this->productStorageClientMock);

        $this->sessionHandlerMock->expects($this->atLeastOnce())
            ->method('getRemovedProducts')
            ->willReturn($removedProductsCompleteArray);

        $this->factoryMock->expects($this->atLeastOnce())
            ->method('createEnhancedEcommerceSessionHandler')
            ->willReturn($this->sessionHandlerMock);

        $this->productStorageClientMock->expects($this->atLeastOnce())
            ->method('findProductAbstractStorageData')
            ->with($productAbstractDataArray['id_product_abstract'], 'en_US')
            ->willReturn($productAbstractDataArray);

        $this->plugin->handle($this->twigEnvironmentMock, $this->requestMock, []);

        $methodGetProductFromQuote = $this->getMethod('getRemovedProducts');
        $products = $methodGetProductFromQuote->invokeArgs($this->plugin, []);
        $this->assertNotCount(0, $products);
    }

    /**
     * @return void
     */
    public function testGetRemovedProductsEventFailureNoProductInSession(): void
    {
        $this->twigEnvironmentMock
            ->method('render')
            ->willReturn('');

        $this->factoryMock->expects($this->never())
            ->method('getProductStorageClient')
            ->willReturn($this->productStorageClientMock);

        $this->plugin->handle($this->twigEnvironmentMock, $this->requestMock, []);

        $methodGetProductFromQuote = $this->getMethod('getRemovedProducts');
        $products = $methodGetProductFromQuote->invokeArgs($this->plugin, []);
        $this->assertCount(0, $products);
    }

    /**
     * @param string $name
     *
     * @throws
     *
     * @return \ReflectionMethod
     */
    protected function getMethod(string $name)
    {
        $class = new ReflectionClass(EnhancedEcommerceCartPlugin::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
