<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

use Codeception\Test\Unit;
use Spryker\Client\Session\SessionClientInterface;

class GoogleTagManagerToSessionClientBridgeTest extends Unit
{
    /**
     * @var \Spryker\Client\Session\SessionClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $sessionClientMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface
     */
    protected $bridge;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $this->sessionClientMock = $this->getMockBuilder(SessionClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->bridge = new GoogleTagManagerToSessionClientBridge($this->sessionClientMock);
    }

    /**
     * @return void
     */
    public function testGetId(): void
    {
        $this->sessionClientMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn('4');

        $id = $this->bridge->getId();

        $this->assertEquals('4', $id);
    }

    /**
     * @return void
     */
    public function testSet(): void
    {
        $this->sessionClientMock->expects($this->atLeastOnce())
            ->method('set')
            ->with('name', 'value');

        $this->bridge->set('name', 'value');
    }

    /**
     * @return void
     */
    public function testGet(): void
    {
        $this->sessionClientMock->expects($this->atLeastOnce())
            ->method('get')
            ->with('stringKey')
            ->willReturn('i.e. string result');

        $string = $this->bridge->get('stringKey');

        $this->assertEquals('i.e. string result', $string);
    }

    /**
     * @return void
     */
    public function testRemove(): void
    {
        $this->sessionClientMock->expects($this->atLeastOnce())
            ->method('remove')
            ->with('name');

        $this->bridge->remove('name');
    }
}
