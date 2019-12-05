<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Business\Model\DataLayer;

use Spryker\Shared\Kernel\Store;

class DefaultVariableBuilder
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultVariableBuilderPluginInterface[]
     */
    protected $defaultVariableBuilderPlugins;

    /**
     * @var Store
     */
    protected $store;

    /**
     * DefaultVariableBuilder constructor.
     *
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultVariableBuilderPluginInterface[] $defaultVariableBuilderPlugins
     * @param Store $store
     */
    public function __construct(array $defaultVariableBuilderPlugins, Store $store)
    {
        $this->defaultVariableBuilderPlugins = $defaultVariableBuilderPlugins;
        $this->store = $store;
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
            'currency' => $this->store->getCurrencyIsoCode(),
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
            $variables = array_merge($variables, $plugin->handle());
        }

        return $variables;
    }
}
