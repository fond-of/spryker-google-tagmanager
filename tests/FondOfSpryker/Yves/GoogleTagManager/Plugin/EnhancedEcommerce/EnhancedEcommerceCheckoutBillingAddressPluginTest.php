<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

class EnhancedEcommerceCheckoutBillingAddressPluginTest extends Unit
{
    /**
     * @var \Twig_Environment|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $twigEnvironmentMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce\EnhancedEcommerceCheckoutBillingAddressPlugin
     */
    protected $plugin;

    /**
     * @var \Symfony\Component\HttpFoundation\Request|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $requestMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $factoryMock;

    /**
     * @var \Generated\Shared\Transfer\ItemTransfer[]|\PHPUnit\Framework\MockObject\MockObject[]
     */
    protected $itemTransferListMock;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $quoteTransferMock;

    /**
     * @var \Generated\Shared\Transfer\ItemTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $itemTransferMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $cartClientMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $storageClientMock;

    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before();

        $this->factoryMock = $this->getMockBuilder(GoogleTagManagerFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigEnvironmentMock = $this->getMockBuilder(Twig_Environment::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->itemTransferMock = $this->getMockBuilder(ItemTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->cartClientMock = $this->getMockBuilder(GoogleTagManagerToCartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storageClientMock = $this->getMockBuilder(GoogleTagManagerToProductStorageClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->twigEnvironmentMock
            ->method('render')
            ->willReturn('');

        $this->plugin = new EnhancedEcommerceCheckoutBillingAddressPlugin();
        $this->plugin->setFactory($this->factoryMock);
    }

    /**
     * @return void
     */
    public function testGetTemplate(): void
    {
        $this->assertEquals('@GoogleTagManager/partials/enhanced-ecommerce-default.twig', $this->plugin->getTemplate());
    }

    /**
     * @return void
     */
    public function testhandleSucces(): void
    {
        $productAbstractDataArray = include codecept_data_dir('ProductAbstractDataArray.php');

        $this->itemTransferMock->method('getIdProductAbstract')->willReturn(53);
        $this->itemTransferMock->method('getUnitPrice')->willReturn(3999);
        $this->itemTransferMock->method('getQuantity')->willReturn(1);

        $this->itemTransferListMock = [$this->itemTransferMock];

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn($this->itemTransferListMock);

        $this->factoryMock->expects($this->atLeastOnce())
            ->method('getCartClient')
            ->willReturn($this->cartClientMock);

        $this->factoryMock->expects($this->atLeastOnce())
            ->method('getProductStorageClient')
            ->willReturn($this->storageClientMock);

        $this->storageClientMock->expects($this->atLeastOnce())
            ->method('findProductAbstractStorageData')
            ->with(53, 'en_US')
            ->willReturn($productAbstractDataArray);

        $methodGetProductFromQuote = $this->getMethod('renderCartViewProducts');
        $products = $methodGetProductFromQuote->invokeArgs($this->plugin, []);
        $this->assertNotCount(0, $products);

        $this->plugin->handle($this->twigEnvironmentMock, $this->requestMock, []);
    }

    /**
     * @return void
     */
    public function testhandleFailure(): void
    {
        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn([]);

        $this->factoryMock->expects($this->atLeastOnce())
            ->method('getCartClient')
            ->willReturn($this->cartClientMock);

        $this->factoryMock->expects($this->never())
            ->method('getProductStorageClient');

        $methodGetProductFromQuote = $this->getMethod('renderCartViewProducts');
        $products = $methodGetProductFromQuote->invokeArgs($this->plugin, []);
        $this->assertCount(0, $products);

        $this->plugin->handle($this->twigEnvironmentMock, $this->requestMock, []);
    }

    /**
     * @param string $name
     *
     * @throws
     *
     * @return \ReflectionMethod
     */
    protected function getMethod(string $name)
    {
        $class = new ReflectionClass(EnhancedEcommerceCheckoutBillingAddressPlugin::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
