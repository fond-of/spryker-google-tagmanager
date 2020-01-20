<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Dependency;

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
