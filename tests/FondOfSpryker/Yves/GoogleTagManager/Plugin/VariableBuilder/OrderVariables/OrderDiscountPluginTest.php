<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CalculatedDiscountTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\TotalsTransfer;

class OrderDiscountPluginTest extends Unit
{
    /**
     * @var \Generated\Shared\Transfer\OrderTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $orderTransferMock;

    /**
     * @var \Generated\Shared\Transfer\TotalsTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $totalsTransferMock;

    /**
     * @var \Generated\Shared\Transfer\CalculatedDiscountTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $calculatedDiscountTransferMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\OrderVariables\OrderDiscountPlugin
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->orderTransferMock = $this->createMock(OrderTransfer::class);
        $this->totalsTransferMock = $this->createMock(TotalsTransfer::class);
        $this->calculatedDiscountTransferMock = $this->createMock(CalculatedDiscountTransfer::class);

        $this->plugin = new OrderDiscountPlugin();
    }

    /**
     * @return void
     */
    public function testHandleSuccess(): void
    {
        $this->orderTransferMock->expects($this->atLeastOnce())
            ->method('getTotals')
            ->willReturn($this->totalsTransferMock);

        $this->calculatedDiscountTransferMock->expects($this->atLeastOnce())
            ->method('getVoucherCode')
            ->willReturn('VOUCHER_CODE');

        $calculatedDiscountTransferMockCollection = [
            $this->calculatedDiscountTransferMock,
        ];

        $this->orderTransferMock->expects($this->atLeastOnce())
            ->method('getCalculatedDiscounts')
            ->willReturn($calculatedDiscountTransferMockCollection);

        $this->totalsTransferMock->expects($this->atLeastOnce())
            ->method('getDiscountTotal')
            ->willReturn(2990);

        $array = $this->plugin->handle($this->orderTransferMock, []);

        $this->assertArrayHasKey('discountTotal', $array);
        $this->assertEquals(29.90, $array['discountTotal']);

        $this->assertArrayHasKey('voucherCode', $array);
        $this->assertEquals('VOUCHER_CODE', $array['voucherCode']);
    }

    /**
     * @return void
     */
    public function testHandleFailure(): void
    {
        $this->orderTransferMock->expects($this->atLeastOnce())
            ->method('getTotals')
            ->willReturn(null);

        $this->orderTransferMock->expects($this->atLeastOnce())
            ->method('getCalculatedDiscounts')
            ->willReturn([]);

        $array = $this->plugin->handle($this->orderTransferMock, []);

        $this->assertArrayNotHasKey('discountTotal', $array);
        $this->assertArrayNotHasKey('voucherCode', $array);
    }
}
