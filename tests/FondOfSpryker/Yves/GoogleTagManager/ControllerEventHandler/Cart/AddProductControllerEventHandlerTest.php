<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Cart;

use Codeception\Test\Unit;
use FondOfSpryker\Shared\GoogleTagManager\EnhancedEcommerceConstants;
use FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface;
use Generated\Shared\Transfer\EnhancedEcommerceProductDataTransfer;
use Symfony\Component\HttpFoundation\Request;

class AddProductControllerEventHandlerTest extends Unit
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Session\EnhancedEcommerceSessionHandlerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $sessionHandlerMock;

    /**
     * @var \Generated\Shared\Transfer\EnhancedEcommerceProductDataTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $enhancedEcommerceProductDataTransferMock;

    protected function _before(): void
    {
        $this->sessionHandlerMock = $this->getMockBuilder(EnhancedEcommerceSessionHandlerInterface::class)
            ->setMethods([
                'getAddedProducts',
                'getRemovedProducts',
                'getChangeProductQuantityEventArray',
                'addProduct',
                'changeProductQuantity',
                'removeProduct'
            ])
            ->disableOriginalConstructor()
            ->getMock();

        $this->enhancedEcommerceProductDataTransferMock = $this->getMockBuilder(EnhancedEcommerceProductDataTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return void
     */
    public function testHandleSuccess(): void
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('get')
            ->willReturn($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null, 'TEST_SKU'],
                [EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY, null, 11],
            ]));

        $requestMock->expects($this->exactly(2))
            ->method('get');

        $this->sessionHandlerMock->expects($this->once())
            ->method('addProduct');

        $addProductControllerEventHandler = new AddProductControllerEventHandler($this->sessionHandlerMock);
        $addProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleSuccessWithoutQuantity(): void
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('get')
            ->will($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null, 'TEST_SKU'],
            ]));

        $this->assertEquals('TEST_SKU', $requestMock->get(EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null));

        $requestMock->expects($this->exactly(2))
            ->method('get');

        $this->sessionHandlerMock->expects($this->once())
            ->method('addProduct');

        $addProductControllerEventHandler = new AddProductControllerEventHandler($this->sessionHandlerMock);
        $addProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }

    /**
     * @return void
     */
    public function testHandleFailureWithoutSKU(): void
    {
        $requestMock = $this->createMock(Request::class);
        $requestMock->method('get')
            ->will($this->returnValueMap([
                [EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY, null, '11'],
            ]));

        $this->assertEquals(null, $requestMock->get(EnhancedEcommerceConstants::PRODUCT_FIELD_SKU, null));
        $this->assertEquals('11', $requestMock->get(EnhancedEcommerceConstants::PRODUCT_FIELD_QUANTITY, null));

        $requestMock->expects($this->exactly(2))
            ->method('get');

        $this->sessionHandlerMock->expects($this->never())
            ->method('addProduct');

        $addProductControllerEventHandler = new AddProductControllerEventHandler($this->sessionHandlerMock);
        $addProductControllerEventHandler->handle($requestMock, 'xx_XX');
    }
}
