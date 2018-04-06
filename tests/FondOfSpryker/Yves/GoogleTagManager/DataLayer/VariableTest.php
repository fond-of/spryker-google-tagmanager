<?php

namespace FondOfSprykerTest\Yves\DataLayer;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\DataLayer\Variable;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

class VariableTest extends Unit
{
    /**
     * @var \Generated\Shared\Transfer\OrderTransfer $orderTransfer |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $orderTransferMock;

    /**
     * @var \Generated\Shared\Transfer\QuoteProductTransfer |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storageProductTransferMock;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer |\PHPUnit\Framework\MockObject\MockObject
     */
    protected $storeTransferMock;

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

        $this->storageProductTransferMock = $this->getMockBuilder(StorageProductTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getIdProductAbstract', 'getName', 'getSku'])
            ->getMock();

        $this->storeTransferMock = $this->getMockBuilder(StoreTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getName'])
            ->getMock();

        $this->totalTransferMock = $this->getMockBuilder(TotalsTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getGrandTotal'])
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->setMethods(['getTotals', 'getItems', 'getShipment', 'getStore'])
            ->getMock();

        $this->variable = new Variable();
    }

    /**
     * @return void
     */
    public function testGetDefaultVariables()
    {
        $variables = $this->variable->getDefaultVariables('home');

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

        $variables = $this->variable->getProductVariables($this->storageProductTransferMock);

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

        $variables = $this->variable->getCategoryVariables($category, $products);
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

        $this->totalTransferMock->expects($this->atLeastOnce())
            ->method('getGrandTotal')
            ->willReturn(1990);

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

        $variables = $this->variable->getQuoteVariables($this->quoteTransferMock);

        $this->assertNotEmpty($variables);
        $this->assertEquals('QUOTE', $variables['transactionEntity']);
    }

    /**
     * @return void
     */
    public function testGetOrderVariables()
    {
        /*$variables = $this->variable->getOrderVariables($this->quoteTransferMock);

        $this->assertNotEmpty($variables);
        $this->assertEquals('ORDER', $variables['transactionEntity']);*/
    }
}
