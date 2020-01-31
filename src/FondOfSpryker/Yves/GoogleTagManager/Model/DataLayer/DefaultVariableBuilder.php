<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

class DefaultVariableBuilder
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultVariableBuilderPluginInterface[]
     */
    protected $defaultVariableBuilderPlugins;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultVariableBuilderPluginInterface[] $defaultVariableBuilderPlugins
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToCartClientInterface $cartClient
     */
    public function __construct(
        array $defaultVariableBuilderPlugins
    ) {
        $this->defaultVariableBuilderPlugins = $defaultVariableBuilderPlugins;
    }

    /**
     * @param string $page
     *
     * @return array
     */
    public function getVariable(string $page): array
    {
        $variables = [
            'pageType' => $page,
        ];

        return $this->executePlugins($variables);
    }

    /**
     * @param array $variables
     *
     * @return array
     */
    protected function executePlugins(array $variables): array
    {
        foreach ($this->defaultVariableBuilderPlugins as $plugin) {
            $variables = array_merge($variables, $plugin->handle($variables));
        }

        return $variables;
    }
}
