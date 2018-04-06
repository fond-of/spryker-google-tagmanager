<?php

namespace FondOfSprykerTest\Yves\GoogleTagManager;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\DataLayer\VariableInterface;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerDependencyProvider;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory;
use FondOFSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Yves\Kernel\Container;

class GoogleTagManagerFactoryTest extends Unit
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $cartClientMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configMock;

    /**
     * @var null|\Spryker\Yves\Kernel\Container|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $containerMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\DataLayer\Variable |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $variableMock;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $vfsStreamDirectory;

    /**
     * @return void
     */
    public function _before()
    {
        $this->cartClientMock = $this->getMockBuilder(CartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock = $this->getMockBuilder(GoogleTagManagerConfig::class)
            ->disableOriginalConstructor()
            ->setMethods(['getContainerID', 'isEnabled'])
            ->getMock();

        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->variableMock = $this->getMockBuilder(VariableInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return void
     */
    public function testCreateGoogleTagManagerTwigExtension()
    {
        $this->configMock->expects($this->atLeastOnce())
            ->method('getContainerID')
            ->willReturn('GTM-XXXX');

        $this->configMock->expects($this->atLeastOnce())
            ->method('isEnabled')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('offsetExists')
            ->willReturn(true);

        $this->containerMock->expects($this->atLeastOnce())
            ->method('offsetGet')
            ->with(GoogleTagManagerDependencyProvider::CART_CLIENT)
            ->willReturn($this->cartClientMock);

        $googleTagManagerFactory = new GoogleTagManagerFactory();
        $googleTagManagerFactory->setConfig($this->configMock)
            ->setContainer($this->containerMock);

        $twigExtension = $googleTagManagerFactory->createGoogleTagManagerTwigExtension();

        $this->assertInstanceOf(GoogleTagManagerTwigExtension::class, $twigExtension);
    }
}
