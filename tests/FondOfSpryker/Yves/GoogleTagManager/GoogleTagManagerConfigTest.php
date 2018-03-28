<?php

namespace FondOfSprykerTest\Yves\GoogleTagManager;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig;
use org\bovigo\vfs\vfsStream;

class GoogleTagManagerConfigTest extends Unit
{
    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $vfsStreamDirectory;

    /**
     * @return void
     */
    public function _before()
    {
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
    public function testGetContainerID()
    {
        $googleTagManagerConfig = new GoogleTagManagerConfig();

        $this->assertEquals('GTM-XXXX', $googleTagManagerConfig->getContainerID());
    }
}
