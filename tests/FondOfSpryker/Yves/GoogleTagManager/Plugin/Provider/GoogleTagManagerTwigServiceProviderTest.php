<?php

namespace FondOfSprykerTest\Yves\GoogleTagManager;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Provider\GoogleTagManagerTwigServiceProvider;
use Silex\Application;

class GoogleTagManagerTwigServiceProviderTest extends Unit
{
    /**
     * @var \Silex\Application |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $applicationMock;

    /**
     * @var null|\Spryker\Yves\Kernel\Container|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $containerMock;

    /**
     * @return void
     */
    public function _before()
    {
        $this->applicationMock = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->setMethods(['render'])
            ->getMock();
    }

    /**
     * @return void
     */
    public function testRegister()
    {
        $this->containerMock->expects($this->atLeastOnce())
            ->method('offsetExists')
            ->willReturn(true);

        $serviceProvider = new GoogleTagManagerTwigServiceProvider();
        $serviceProvider->register($this->applicationMock);
    }
}
