<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\Client;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

class GoogleTagManagerToProductStorageClientBridgeTest extends Unit
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToProductStorageClientBridge
     */
    protected $bridge;

    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $productStorageClientMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->productStorageClientMock = $this->getMockBuilder(ProductStorageClientInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->bridge = new GoogleTagManagerToProductStorageClientBridge($this->productStorageClientMock);
    }

    /**
     * @return void
     */
    public function testFindProductAbstractStorageDataSuccess(): void
    {
        $productAbstractDataArray = include codecept_data_dir('ProductAbstractDataArray.php');

        $this->productStorageClientMock->expects($this->atLeastOnce())
            ->method('findProductAbstractStorageData')
            ->with(53, 'en_US')
            ->willReturn($productAbstractDataArray);

        $result = $this->bridge->findProductAbstractStorageData(53, 'en_US');

        $this->assertNotCount(0, $result);
    }

    /**
     * @return void
     */
    public function testMapProductStorageData(): void
    {
        $productViewTransfer = include codecept_data_dir('ProductViewTransfer.php');
        $productAbstractDataArray = include codecept_data_dir('ProductAbstractDataArray.php');

        $this->productStorageClientMock->expects($this->atLeastOnce())
            ->method('mapProductStorageData')
            ->with($productAbstractDataArray, 'en_US', [])
            ->willReturn($productViewTransfer);

        $result = $this->bridge->mapProductStorageData($productAbstractDataArray, 'en_US', []);
        $this->assertInstanceOf(ProductViewTransfer::class, $result);
    }
}
