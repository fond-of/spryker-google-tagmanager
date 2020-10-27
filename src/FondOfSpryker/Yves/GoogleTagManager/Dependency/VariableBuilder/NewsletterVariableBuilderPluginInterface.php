<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

interface NewsletterVariableBuilderPluginInterface
{
    /**
     * @param string $page
     *
     * @return array
     */
    public function getVariables(string $page): array;
}
