<?php

/**
 * Google Tag Manager Data Layer Variables
 *
 * @author      Jozsef Geng <jozsef.geng@fondof.de>
 */

namespace FondOfSpryker\Yves\GoogleTagManager\DataLayer;

interface VariableInterface
{
    /**
     * @param $product
     * @return mixed
     */
    public function getDefaultVariables($page);

    /**
     * @param $product
     * @return mixed
     */
    public function getProductVariables($product);

    /**
     * @param $category
     * @param $products
     * @return mixed
     */
    public function getCategoryVariables($category, $products);

    /**
     * @param $quote
     * @return mixed
     */
    public function getQuoteVariables($quote);
}
