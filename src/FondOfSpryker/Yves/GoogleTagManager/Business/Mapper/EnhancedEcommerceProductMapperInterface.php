<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Business\Mapper;

use Generated\Shared\Transfer\EnhancedEcommerceProductTransfer;

interface EnhancedEcommerceProductMapperInterface
{
    /**
     * @param array $product
     *
     * @return \Generated\Shared\Transfer\EnhancedEcommerceProductTransfer
     */
    public function map(array $product): EnhancedEcommerceProductTransfer;
}
