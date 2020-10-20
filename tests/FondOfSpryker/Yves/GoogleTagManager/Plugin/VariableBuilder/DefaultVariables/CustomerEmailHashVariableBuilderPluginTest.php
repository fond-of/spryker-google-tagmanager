<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

use Codeception\Test\Unit;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface;
use FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class CustomerEmailHashVariableBuilderPluginTest extends Unit
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $factoryMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $cartClientMock;

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultCurrencyPlugin
     */
    protected $plugin;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $quoteTransferMock;

    /**
     * @var \Generated\Shared\Transfer\AddressesTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $billingAddressTransfer;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->factoryMock = $this->createMock(GoogleTagManagerFactory::class);
        $this->cartClientMock = $this->createMock(GoogleTagManagerToCartClientInterface::class);
        $this->quoteTransferMock = $this->createMock(QuoteTransfer::class);
        $this->billingAddressTransfer = $this->createMock(AddressTransfer::class);

        $this->plugin = new CustomerEmailHashVariableBuilderPlugin();
        $this->plugin->setFactory($this->factoryMock);
    }

    /**
     * @return void
     */
    public function testHandleSuccess(): void
    {
        $this->factoryMock->expects($this->atLeastOnce())
            ->method('getCartClient')
            ->willReturn($this->cartClientMock);

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getBillingAddress')
            ->willReturn($this->billingAddressTransfer);

        $this->billingAddressTransfer->expects($this->atLeastOnce())
            ->method('getEmail')
            ->willReturn('john.doe@mailinator.com');

        $array = $this->plugin->handle([]);

        $this->assertArrayHasKey('externalIdHash', $array);
    }

    /**
     * @return void
     */
    public function testHandleSuccessFailureNoBillingAddressInQuote(): void
    {
        $this->factoryMock->expects($this->atLeastOnce())
            ->method('getCartClient')
            ->willReturn($this->cartClientMock);

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getBillingAddress')
            ->willReturn(null);

        $array = $this->plugin->handle([]);

        $this->assertArrayNotHasKey('externalIdHash', $array);
        $this->assertCount(0, $array);
    }

    /**
     * @return void
     */
    public function testHandleSuccessFailureNoEmailAddress(): void
    {
        $this->factoryMock->expects($this->atLeastOnce())
            ->method('getCartClient')
            ->willReturn($this->cartClientMock);

        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->quoteTransferMock->expects($this->atLeastOnce())
            ->method('getBillingAddress')
            ->willReturn($this->billingAddressTransfer);

        $this->billingAddressTransfer->expects($this->atLeastOnce())
            ->method('getEmail')
            ->willReturn(null);

        $array = $this->plugin->handle([]);

        $this->assertArrayNotHasKey('externalIdHash', $array);
        $this->assertCount(0, $array);
    }
}
