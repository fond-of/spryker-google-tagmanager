<?php

namespace FondOfSpryker\Yves\GoogleTagManager;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\DataLayer\VariableInterface;
use FondOfSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension;
use Spryker\Client\Cart\CartClientInterface;
use Twig_Environment;

class GoogleTagManagerTwigExtensionTest extends Unit
{
    /**
     * @var \Spryker\Client\Cart\CartClientInterface |\PHPUnit\Framework\MockObject\MockObject|null
     */
    protected $cartClientMocK;

    /**
     * @var \FondOFSpryker\Yves\GoogleTagManager\Twig\GoogleTagManagerTwigExtension
     */
    protected $googleTagManagerTwigExtension;

    /**
     * @var \Twig_Environment
     */
    protected $twigEnvironmentMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\DataLayer\Variable |\PHPUnit\Framework\MockObject\MockObject|null
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
        $this->cartClientMocK = $this->getMockBuilder(CartClientInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['addItem', 'addItems', 'changeItemQuantity', 'clearQuote', 'decreaseItemQuantity', 'getItemCount', 'getQuote', 'increaseItemQuantity', 'removeItem', 'removeItems', 'reloadItems', 'storeQuote'])
            ->getMock();

        $this->twigEnvironmentMock = $this->getMockBuilder(Twig_Environment::class)
            ->disableOriginalConstructor()
            ->setMethods(['render'])
            ->getMock();

        $this->variableMock = $this->getMockBuilder(VariableInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['getDefaultVariables', 'getCategoryVariables', 'getProductVariables', 'getQuoteVariables', 'getOrderVariables'])
            ->getMock();

        $this->googleTagManagerTwigExtension = new GoogleTagManagerTwigExtension(
            'GTM-XXXX',
            true,
            $this->variableMock,
            $this->cartClientMocK
        );
    }

    /**
     * @return void
     */
    public function testGetFunction()
    {
        $functions = $this->googleTagManagerTwigExtension->getFunctions();

        $this->assertNotEmpty($functions);
        $this->assertEquals(2, count($functions));
        $this->assertEquals('fondOfSpykerGoogleTagManager', $functions[0]->getName());
        $this->assertEquals('fondOfSpykerDataLayer', $functions[1]->getName());
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

        $renderer = $this->googleTagManagerTwigExtension->renderGoogleTagManager($this->twigEnvironmentMock, $templateName);

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
        $googleTagManagerTwigExtension = new GoogleTagManagerTwigExtension(
            '',
            true,
            $this->variableMock,
            $this->cartClientMocK
        );

        $renderer = $googleTagManagerTwigExtension->renderGoogleTagManager($this->twigEnvironmentMock, $templateName);

        $this->assertEmpty($renderer);
    }

    /**
     * @return void
     */
    public function testRenderDataLayer()
    {
        $renderedTemplate = '<script>var dataLayer = [()]</script>';

        $this->cartClientMocK->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn(null);

        $this->twigEnvironmentMock->expects($this->atLeastOnce())
            ->method('render')
            ->willReturn($renderedTemplate);

        $this->variableMock->expects($this->atLeastOnce())
            ->method('getDefaultVariables')
            ->willReturn([]);

        $renderer = $this->googleTagManagerTwigExtension->renderDataLayer($this->twigEnvironmentMock, 'home', []);

        $this->assertNotEmpty($renderer);
    }
}
