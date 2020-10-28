<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder;

use Exception;
use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\DefaultVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class DefaultVariableBuilderPlugin extends AbstractPlugin implements DefaultVariableBuilderPluginInterface
{
    use LoggerTrait;

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
        $googleTagManagerDefaultTransfer = $this->createGoogleTagManagerDefaultTransfer();
        $googleTagManagerDefaultTransfer->setPageType($page);
        $defaultVariableBuilderPlugins = $this->getFactory()->getDefaultVariableBuilderFieldPlugins();

        foreach ($defaultVariableBuilderPlugins as $plugin) {
            try {
                $plugin->handle($googleTagManagerDefaultTransfer, $params);
            } catch (Exception $e) {
                $this->getLogger()->notice(sprintf(
                    'GoogleTagManager: error in %s, plugin %s',
                    self::class,
                    get_class($plugin)
                ));
            }
        }

        return $this->stripEmptyArrayIndex($googleTagManagerDefaultTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\GoogleTagManagerDefaultTransfer
     */
    protected function createGoogleTagManagerDefaultTransfer(): GoogleTagManagerDefaultTransfer
    {
        return new GoogleTagManagerDefaultTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerProductDetailTransfer $googleTagManagerDefaultTransfer
     *
     * @return array
     */
    protected function stripEmptyArrayIndex(GoogleTagManagerDefaultTransfer $googleTagManagerDefaultTransfer): array
    {
        $googleTagManagerProductDetailArray = $googleTagManagerDefaultTransfer->toArray(true, true);

        foreach ($googleTagManagerProductDetailArray as $field => $value) {
            if ($value === null) {
                unset($googleTagManagerProductDetailArray[$field]);
            }

            if ($value === '') {
                unset($googleTagManagerProductDetailArray[$field]);
            }
        }

        return $googleTagManagerProductDetailArray;
    }
}
