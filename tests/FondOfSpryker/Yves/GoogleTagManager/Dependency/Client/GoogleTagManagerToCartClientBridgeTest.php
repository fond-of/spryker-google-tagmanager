<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Cart\CartClientInterface;

class GoogleTagManagerToCartClientBridgeTest extends Unit
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientBridge
     */
    protected $bridge;

    /**
     * @var \Spryker\Client\Cart\CartClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $cartClientMock;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $quoteTransferMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->cartClientMock = $this->getMockBuilder(CartClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->quoteTransferMock = $this->getMockBuilder(QuoteTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->bridge = new GoogleTagManagerToCartClientBridge($this->cartClientMock);
    }

    /**
     * @return void
     */
    public function testGetQuoteSuccess(): void
    {
        $this->cartClientMock->expects($this->atLeastOnce())
            ->method('getQuote')
            ->willReturn($this->quoteTransferMock);

        $this->bridge->getQuote();
    }
}
