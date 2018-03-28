<?php

namespace FondOfSprykerTest\Yves\GoogleTagManager;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\Plugin\Provider\GoogleTagManagerTwigServiceProvider;
use org\bovigo\vfs\vfsStream;
use Silex\Application;

class GoogleTagManagerTwigServiceProviderTest extends Unit
{
    /**
     * @var \Silex\Application |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $applicationMock;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $vfsStreamDirectory;

    /**
     * @return void
     */
    public function _before()
    {

        $this->applicationMock = $this->getMockBuilder(Application::class)
            ->disableOriginalConstructor()
            ->setMethods(['render'])
            ->getMock();


        $this->vfsStreamDirectory = vfsStream::setup('root', null, [
            'config' => [
                'Shared' => [
                    'stores.php' => file_get_contents(codecept_data_dir('stores.php')),
                    'config_default.php' => file_get_contents(codecept_data_dir('config_default.php')),
                ],
            ],
        ]);
    }

    /**
     * @return void
     */
    public function testRegister()
    {
        $serviceProvider = new GoogleTagManagerTwigServiceProvider();
        //$serviceProvider->register($this->applicationMock);
    }
}
