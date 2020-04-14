<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

class DefaultVariableBuilder
{
    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultVariableBuilderPluginInterface[]
     */
    protected $defaultVariableBuilderPlugins;

    /**
     * @var array
     */
    private $internalIps;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables\DefaultVariableBuilderPluginInterface[] $defaultVariableBuilderPlugins
     * @param array $internalIps
     */
    public function __construct(
        array $defaultVariableBuilderPlugins,
        array $internalIps
    ) {
        $this->defaultVariableBuilderPlugins = $defaultVariableBuilderPlugins;
        $this->internalIps = $internalIps;
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getVariable(string $page, array $params = []): array
    {
        $variables = [
            'pageType' => $page,
        ];

        return $this->executePlugins($variables, \array_merge($params, [
            'internalIps' => $this->internalIps,
        ]));
    }

    /**
     * @param array $variables
     * @param array $params
     *
     * @return array
     */
    protected function executePlugins(array $variables, array $params): array
    {
        foreach ($this->defaultVariableBuilderPlugins as $plugin) {
            $variables = \array_merge($variables, $plugin->handle($variables, $params));
        }

        return $variables;
    }
}
