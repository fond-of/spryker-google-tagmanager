<?php

namespace FondOfSprykerTest\Yves\GoogleTagManager;

use Codeception\Test\Unit;
use FondOFSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension;
use org\bovigo\vfs\vfsStream;
use Twig_Environment;

class GoogleTagManagerTwigExtensionTest extends Unit
{
    protected $twigEnvironmentMock;

    /**
     * @var \org\bovigo\vfs\vfsStreamDirectory
     */
    protected $vfsStreamDirectory;

    /**
     * @return void
     */
    public function _before()
    {

        $this->twigEnvironmentMock = $this->getMockBuilder(Twig_Environment::class)
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
    public function testGetFunction()
    {
        $googleTagManagerTwigExtension = new GoogleTagManagerTwigExtension('GTM-XXXX');
        $functions = $googleTagManagerTwigExtension->getFunctions();

        $this->assertNotEmpty($functions);
        $this->assertEquals(1, count($functions));
        $this->assertEquals('fondOfSpykerGoogleTagManager', $functions[0]->getName());
    }

    /**
     * @return void
     */
    public function testRenderGoogleTagManager()
    {
        $renderedTemplate = '<script></script>';
        $this->twigEnvironmentMock->expects($this->any())
            ->method('render')
            ->willReturn($renderedTemplate);

        $templateName = '@GoogleTagManager/partials/tag.twig';
        $googleTagManagerTwigExtension = new GoogleTagManagerTwigExtension('GTM-XXXX');
        $renderer = $googleTagManagerTwigExtension->renderGoogleTagManager($this->twigEnvironmentMock, $templateName);

        $this->assertNotEmpty($renderer);
    }

    /**
     * @return void
     */
    public function testRenderGoogleTagManagerWithoutContainerID()
    {
        $renderedTemplate = '<script></script>';
        $this->twigEnvironmentMock->expects($this->any())
            ->method('render')
            ->willReturn($renderedTemplate);

        $templateName = '@GoogleTagManager/partials/tag.twig';
        $googleTagManagerTwigExtension = new GoogleTagManagerTwigExtension(null);
        $renderer = $googleTagManagerTwigExtension->renderGoogleTagManager($this->twigEnvironmentMock, $templateName);

        $this->assertEmpty($renderer);
    }
}
