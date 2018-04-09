<?php

namespace FondOfSpryker\Yves\GoogleTagManager;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\DataLayer\VariableInterface;
use FondOFSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Session\SessionClientInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;
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
     * @var \Spryker\Shared\Money\Dependency\Plugin
     */
    protected $pluginMoneyMock;

    /**
     * @var \Spryker\Client\Session\SessionClientInterface |\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $sessionClientMock;

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

        $this->sessionClientMocK = $this->getMockBuilder(SessionClientInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['all', 'clear', 'get', 'getBag', 'getMetadataBag', 'getId', 'getName', 'has', 'invalidate', 'isStarted', 'migrate', 'registerBag', 'replace', 'remove', 'save', 'set', 'setContainer', 'setId', 'setName', 'start'])
            ->getMock();

        $this->configMock = $this->getMockBuilder(GoogleTagManagerConfig::class)
            ->disableOriginalConstructor()
            ->setMethods(['getContainerID', 'isEnabled'])
            ->getMock();

        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->pluginMoneyMock = $this->getMockBuilder(MoneyPluginInterface::class)
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
            ->withConsecutive(
                [GoogleTagManagerDependencyProvider::PLUGIN_MONEY],
                [GoogleTagManagerDependencyProvider::CART_CLIENT],
                [GoogleTagManagerDependencyProvider::SESSION_CLIENT]
            )->willReturnOnConsecutiveCalls(
                $this->pluginMoneyMock,
                $this->cartClientMock,
                $this->sessionClientMocK
            );

        $googleTagManagerFactory = new GoogleTagManagerFactory();
        $googleTagManagerFactory->setConfig($this->configMock)
            ->setContainer($this->containerMock);

        $twigExtension = $googleTagManagerFactory->createGoogleTagManagerTwigExtension();

        $this->assertInstanceOf(GoogleTagManagerTwigExtension::class, $twigExtension);
    }
}
