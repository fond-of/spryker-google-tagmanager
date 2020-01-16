<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use Spryker\Shared\Kernel\Store;

class DefaultVariableBuilder
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultVariableBuilderPluginInterface[]
     */
    protected $defaultVariableBuilderPlugins;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * DefaultVariableBuilder constructor.
     *
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultVariableBuilderPluginInterface[] $defaultVariableBuilderPlugins
     * @param \Spryker\Shared\Kernel\Store $store
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
            'store' => $this->store->getStoreName(),
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
