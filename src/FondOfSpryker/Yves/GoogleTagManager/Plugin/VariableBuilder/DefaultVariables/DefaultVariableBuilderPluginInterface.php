<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

interface DefaultVariableBuilderPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param array $category
     * @param array $products
     *
     * @return array
     */
    public function handle(array $category, array $products): array;
}
