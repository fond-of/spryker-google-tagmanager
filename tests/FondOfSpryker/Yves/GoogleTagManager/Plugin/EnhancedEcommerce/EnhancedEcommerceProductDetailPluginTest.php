<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

class EnhancedEcommerceProductDetailPluginTest extends Unit
{
    /**
     * @var \Twig_Environment|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $twigEnvironmentMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommerceProductDetailPlugin
     */
    protected $plugin;

    /**
     * @var \Symfony\Component\HttpFoundation\Request|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $factoryMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\EnhancedEcommerceProductMapperInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productMapperPluginMock;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $this->productMapperPluginMock = $this->getMockBuilder(EnhancedEcommerceProductMapperInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->factoryMock = $this->getMockBuilder(GoogleTagManagerFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigEnvironmentMock = $this->getMockBuilder(Twig_Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigEnvironmentMock
            ->method('render')
            ->willReturn('');

        $this->plugin = new EnhancedEcommerceProductDetailPlugin();
        $this->plugin->setFactory($this->factoryMock);
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
    public function testhandleSucces(): void
    {
        $productViewTransfer = include codecept_data_dir('ProductViewTransfer.php');
        $enhancedEcommerceProductTransfer = include codecept_data_dir('EnhancedEcommerceProductTransfer.php');

        $this->productMapperPluginMock->expects($this->atLeastOnce())
            ->method('map')
            ->with($productViewTransfer)
            ->willReturn($enhancedEcommerceProductTransfer);

        $this->factoryMock->expects($this->atLeastOnce())
            ->method('getEnhancedEcommerceProductMapperPlugin')
            ->willReturn($this->productMapperPluginMock);

        $methodGetProductFromQuote = $this->getMethod('renderProductDetail');
        $result = $methodGetProductFromQuote->invokeArgs($this->plugin, [[$enhancedEcommerceProductTransfer->toArray()]]);
        $this->assertNotCount(0, $result['ecommerce']['detail']['products']);

        $this->plugin->handle($this->twigEnvironmentMock, $this->requestMock, ['product' => $productViewTransfer]);
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
        $class = new ReflectionClass(EnhancedEcommerceProductDetailPlugin::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
