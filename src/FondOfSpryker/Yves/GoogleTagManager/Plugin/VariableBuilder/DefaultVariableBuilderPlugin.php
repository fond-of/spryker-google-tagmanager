<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultDataLayerVariableBuilderInterface;
use Generated\Shared\Transfer\GooleTagManagerDefaultTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class DefaultVariableBuilderPlugin extends AbstractPlugin implements DefaultDataLayerVariableBuilderInterface
{
    public const VARIABLE_BUILDER_NAME = 'default';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::VARIABLE_BUILDER_NAME;
    }

    /**
     * @param string $page
     * @param array $params
     *
     * @return array
     */
    public function getVariable(string $page, array $params = []): array
    {
        $gooleTagManagerDefaultTransfer = $this->createGooleTagManagerDefaultTransfer();
        $gooleTagManagerDefaultTransfer->setPageType($page);
        $defaultVariableBuilderPlugins = $this->getFactory()->getDefaultVariableBuilderFieldPlugins();

        foreach ($defaultVariableBuilderPlugins as $plugin) {
            $plugin->handle($gooleTagManagerDefaultTransfer, $params);
        }

        return $this->stripEmptyArrayIndex($gooleTagManagerDefaultTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GooleTagManagerDefaultTransfer
     */
    protected function createGooleTagManagerDefaultTransfer(): GooleTagManagerDefaultTransfer
    {
        return new GooleTagManagerDefaultTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\GooleTagManagerProductDetailTransfer $gooleTagManagerDefaultTransfer
     *
     * @return array
     */
    protected function stripEmptyArrayIndex(GooleTagManagerDefaultTransfer $gooleTagManagerDefaultTransfer): array
    {
        $gooleTagManagerProductDetailArray = $gooleTagManagerDefaultTransfer->toArray(true, true);

        foreach ($gooleTagManagerProductDetailArray as $field => $value) {
            if ($value === null) {
                unset($gooleTagManagerProductDetailArray[$field]);
            }

            if ($value === '') {
                unset($gooleTagManagerProductDetailArray[$field]);
            }
        }

        return $gooleTagManagerProductDetailArray;
    }
}
