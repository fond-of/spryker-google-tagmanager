<?php

namespace FondOfSprykerTest\Yves\GoogleTagManager;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory;
use FondOFSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension;

class GoogleTagManagerFactoryTest extends Unit
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $configMock;

    /**
     * @return void
     */
    public function _before()
    {
        $this->configMock = $this->getMockBuilder(GoogleTagManagerConfig::class)
            ->disableOriginalConstructor()
            ->setMethods(['getContainerID'])
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

        $googleTagManagerFactory = new GoogleTagManagerFactory();
        $googleTagManagerFactory->setConfig($this->configMock);
        $twigExtension = $googleTagManagerFactory->createGoogleTagManagerTwigExtension();

        $this->assertInstanceOf(GoogleTagManagerTwigExtension::class, $twigExtension);
    }
}
