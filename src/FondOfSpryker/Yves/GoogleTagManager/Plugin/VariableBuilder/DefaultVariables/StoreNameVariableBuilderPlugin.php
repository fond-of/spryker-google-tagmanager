<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\DefaultVariables;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class StoreNameVariableBuilderPlugin extends AbstractPlugin implements DefaultVariableBuilderPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        // TODO: Implement getName() method.
    }

    /**
     * @param array $variables
     * @param array $params
     *
     * @return array
     */
    public function handle(array $variables, array $params = []): array
    {
        return [
            'store' => $this->getFactory()->getStore()->getStoreName(),
        ];
    }
}
