<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\TransactionProductFieldPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerConfig getConfig()()
 */
class TransactionProductFieldUrlPlugin extends AbstractPlugin implements TransactionProductFieldPluginInterface
{
    public const ATTR_URL_KEY = 'url_key';

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer $googleTagManagerTransactionProductTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerTransactionProductTransfer
     */
    public function handle(
        GoogleTagManagerTransactionProductTransfer $googleTagManagerTransactionProductTransfer,
        ItemTransfer $itemTransfer,
        array $params = []
    ): GoogleTagManagerTransactionProductTransfer {
        $url = sprintf(
            '%s/%s/%s',
            $this->getHost(),
            $this->getUrlLanguageKey(),
            $this->getUrlKey($itemTransfer)
        );

        return $googleTagManagerTransactionProductTransfer->setUrl($url);
    }

    /**
     * @return string
     */
    protected function getHost(): string
    {
        $hostName = $_SERVER['HTTP_HOST'];

        return $this->getConfig()->getProtocol() . '://' . $hostName;
    }

    /**
     * @return string
     */
    protected function getUrlLanguageKey(): string
    {
        $locale = $this->getFactory()
            ->getStore()
            ->getCurrentLocale();

        $locale = explode('_', $locale);

        return $locale[0];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     *
     * @return string|null
     */
    protected function getUrlKey(ItemTransfer $product): ?string
    {
        $locale = $this->getFactory()
            ->getStore()
            ->getCurrentLocale();

        if (!isset($product->getAbstractAttributes()[$locale][static::ATTR_URL_KEY])) {
            return null;
        }

        return $product->getAbstractAttributes()[$locale][static::ATTR_URL_KEY];
    }
}
