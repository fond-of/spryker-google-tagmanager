<?php


namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\TransactionProductVariables;

use Generated\Shared\Transfer\ItemTransfer;

class UrlPlugin implements TransactionProductVariableBuilderPluginInterface
{
    public const URL = 'url';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     *
     * @return array
     */
    public function handle(ItemTransfer $product, array $params = []): array
    {
        if (!isset($params['locale'])) {
            return [];
        }

        $locale = $params['locale'];

        if ($this->getUrlKey($product, $locale) === null) {
            return [];
        }

        return [static::URL => \sprintf('%s/%s/%s', $this->getHost(), $this->getUrlLanguageKey($locale), $this->getUrlKey($product, $locale))];
    }

    /**
     * @return string
     */
    protected function getHost(): string
    {
        $protocol = \strtolower(\substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https' : 'http';
        $hostName = $_SERVER['HTTP_HOST'];

        return $protocol . '://' . $hostName . '/';
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    protected function getUrlLanguageKey(string $locale): string
    {
        $locale = \explode('_', $locale);

        return $locale[0];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $product
     * @param string $locale
     *
     * @return string|null
     */
    protected function getUrlKey(ItemTransfer $product, string $locale): ?string
    {
        if (!isset($product->getAbstractAttributes()[$locale]['url_key'])) {
            return null;
        }

        return $product->getAbstractAttributes()[$locale]['url_key'];
    }
}
