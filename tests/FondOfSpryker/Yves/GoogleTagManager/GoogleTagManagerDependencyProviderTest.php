<?php

namespace FondOfSprykerTest\Yves\GoogleTagManager;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerDependencyProvider;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Yves\Kernel\Container;

class GoogleTagManagerDependencyProviderTest extends Unit
{
    /**
     * @var \Spryker\Shared\Kernel\BundleProxy|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $bundleProxyMock;

    /**
     * @var \Spryker\Client\Cart\CartClient|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $cartClientMock;

    /**
     * @var \Spryker\Yves\Kernel\Container|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $containerMock;

    /**
     * @var \Spryker\Shared\Kernel\AbstractLocator|\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $locatorMock;

    /**
     * @return void
     */
    public function _before()
    {
        $this->bundleProxyMock = $this->getMockBuilder(BundleProxy::class)
            ->disableOriginalConstructor()
            ->setMethods(['client'])
            ->getMock();

        $this->cartClientMock = $this->getMockBuilder(CartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->containerMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->setMethods(['getLocator'])
            ->getMock();

        $this->locatorMock = $this->getMockBuilder(Container::class)
            ->disableOriginalConstructor()
            ->setMethods(['cart'])
            ->getMock();
    }

    /**
     * @return void
     */
    public function testProvideDependencies()
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method("getLocator")
            ->willReturn($this->locatorMock);

        $this->locatorMock->expects($this->atLeastOnce())
            ->method('cart')
            ->willReturn($this->bundleProxyMock);

        $this->bundleProxyMock->expects($this->atLeastOnce())
            ->method('client')
            ->willReturn($this->cartClientMock);

        $googlTagManagerDependencyProvider = new GoogleTagManagerDependencyProvider();

        $googlTagManagerDependencyProvider->provideDependencies($this->containerMock);

        $valueNames = $this->containerMock->keys();

        $this->assertTrue(in_array(GoogleTagManagerDependencyProvider::CART_CLIENT, $valueNames));
        $this->assertNotNull($this->containerMock[GoogleTagManagerDependencyProvider::CART_CLIENT]);
    }
}
