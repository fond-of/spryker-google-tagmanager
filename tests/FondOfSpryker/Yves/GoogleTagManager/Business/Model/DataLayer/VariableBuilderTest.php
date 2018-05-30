<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\Model;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer\VariableBuilder;
use FondOfSpryker\Yves\GoogleTagManager\DataLayer\Variable;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;
use Generated\Shared\Transfer\TaxTotalTransfer;
use Spryker\Client\Product\ProductClientInterface;
use Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface;

class VariableBuilderTest extends Unit
{
    /**
     * @var
     */
    protected $moneyPlugin;

    /**
     * @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $orderTransferMock;

    /**
     * @var \Spryker\Shared\Money\Dependency\Plugin\MoneyPluginInterface |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $moneyPluginMock;

    /**
     * @var \Generated\Shared\Transfer\QuoteProductTransfer |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storageProductTransferMock;

    /**
     * @var \Spryker\Client\Product\ProductClientInterfac |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productClient;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storeTransferMock;

    /**
     * @var \Generated\Shared\Transfer\TaxTotalTransfer |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $taxTotalMock;

    /**
     * @var \Generated\Shared\Transfer\TotalsTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $totalTransferMock;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $quoteTransferMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\DataLayer\Variable
     */
    protected $variable;

    /**
     * @return void
     */
    public function _before()
    {
        $this->orderTransferMock = $this->getMockBuilder(OrderTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->moneyPluginMock = $this->getMockBuilder(MoneyPluginInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->productClient = $this->getMockBuilder( ProductClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->storageProductTransferMock = $this->getMockBuilder(StorageProductTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIdProductAbstract', 'getName', 'getPrice', 'getSku'])
            ->getMock();

        $this->storeTransferMock = $this->getMockBuilder(StoreTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getName'])
            ->getMock();

        $this->taxTotalMock = $this->getMockBuilder(TaxTotalTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAmount'])
            ->getMock();

        $this->totalTransferMock = $this->getMockBuilder(TotalsTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getGrandTotal', 'getTaxTotal'])
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTotals', 'getItems', 'getShipment', 'getStore'])
            ->getMock();

        $this->orderTransferMock = $this->getMockBuilder(OrderTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems', 'getExpenses', 'getPayments', 'getShipmentMethods'])
            ->getMock();

        $this->variableBuilder = new VariableBuilder($this->moneyPluginMock, $this->productClient);
    }

    /**
     * @return void
     */
    public function testGetDefaultVariables()
    {
        $variables = $this->variableBuilder->getDefaultVariables('home');

        $this->assertNotEmpty($variables);
        $this->assertEquals('home', $variables['pageType']);
    }

    /**
     * @return void
     */
    public function testGetProductVariables()
    {
        $this->storageProductTransferMock->expects($this->atLeastOnce())
            ->method('getIdProductAbstract')
            ->willReturn(1);

        $this->storageProductTransferMock->expects($this->atLeastOnce())
            ->method('getName')
            ->willReturn('name');

        $this->storageProductTransferMock->expects($this->atLeastOnce())
            ->method('getSku')
            ->willReturn('sku');

        $this->storageProductTransferMock->expects($this->atLeastOnce())
            ->method('getPrice')
            ->willReturn(1990);

        $variables = $this->variableBuilder->getProductVariables($this->storageProductTransferMock);

        $this->assertNotEmpty($variables);
        $this->assertEquals('1', $variables['productId']);
    }

    /**
     * @return void
     */
    public function testGetCategoryVariables()
    {
        $category = [
            'id_category' => 1,
            'name' => 'name',

        ];

        $products = [
            0 => [
                'id_product_abstract' => '1',
                'abstract_sku' => 'abstract_sku',
                'abstract_name' => 'abstract_name',
                'price' => 1990,
            ],
        ];

        $variables = $this->variableBuilder->getCategoryVariables($category, $products);
        $this->assertNotEmpty($variables);
        $this->assertEquals(1, $variables['categoryId']);
    }

    /**
     * @return void
     */
    public function testGetQuoteVariables()
    {
        $this->storeTransferMock->expects($this->atLeastOnce())
            ->method('getName')
            ->willReturn('store');

        $this->taxTotalMock->expects($this->atLeastOnce())
            ->method('getAmount')
            ->willReturn(1990);

        $this->totalTransferMock->expects($this->atLeastOnce())
            ->method('getGrandTotal')
            ->willReturn(1990);

        $this->totalTransferMock->expects($this->atLeastOnce())
            ->method('getTaxTotal')
            ->willReturn($this->taxTotalMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getTotals')
            ->willReturn($this->totalTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn([]);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getShipment')
            ->willReturn(null);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getStore')
            ->willReturn($this->storeTransferMock);

        $variables = $this->variableBuilder->getQuoteVariables($this->quoteTransferMock, 'SESSION_TEST');

        $this->assertNotEmpty($variables);
        $this->assertEquals('QUOTE', $variables['transactionEntity']);
    }

    /**
     * @return void

    public function testGetOrderVariables()
    {
        $this->orderTransferMock->expects($this->atLeastOnce())
            ->method('getItems')
            ->willReturn([]);

        $this->orderTransferMock->expects($this->atLeastOnce())
            ->method('getExpenses')
            ->willReturn(null);

        $this->orderTransferMock->expects($this->atLeastOnce())
            ->method('getShipmentMethods')
            ->willReturn(null);

        $this->orderTransferMock->expects($this->atLeastOnce())
            ->method('getPayments')
            ->willReturn(null);

        $variables = $this->variableBuilder->getOrderVariables($this->orderTransferMock);

        $this->assertNotEmpty($variables);
        $this->assertEquals('ORDER', $variables['transactionEntity']);
    }*/
}
