<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

interface CategoryVariableBuilderPluginInterface
{
    /**
     * @param array $category
     * @param array $products
     * @param array $params
     *
     * @return array
     */
    public function getVariables(array $category, array $products, array $params = []): array;
}
