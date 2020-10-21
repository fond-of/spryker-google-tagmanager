<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

interface DefaultDataLayerVariableBuilderInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getVariable(string $page, array $params = []): array;
}
