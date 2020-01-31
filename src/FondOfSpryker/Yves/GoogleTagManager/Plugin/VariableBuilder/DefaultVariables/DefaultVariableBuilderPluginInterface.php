<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

interface DefaultVariableBuilderPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param array $variables
     * @param array $params
     *
     * @return array
     */
    public function handle(array $variables, array $params = []): array;
}
