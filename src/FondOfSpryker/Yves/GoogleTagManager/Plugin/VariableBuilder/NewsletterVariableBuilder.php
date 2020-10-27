<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Model\DataLayer;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\NewsletterVariableBuilderPluginInterface;
use Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class NewsletterVariableBuilder extends AbstractPlugin implements NewsletterVariableBuilderPluginInterface
{
    /**
     * @param string $page
     *
     * @return array
     */
    public function getVariables(string $page): array
    {
        $googleTagManagerNewsletterTransfer = $this->createGoogleTagManagerNewsletterTransfer();

        foreach ($this->getFactory()->getNewsletterVariableBuilderFieldPlugins() as $plugin) {
            $plugin->handle($googleTagManagerNewsletterTransfer);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer
     */
    protected function createGoogleTagManagerNewsletterTransfer(): GoogleTagManagerNewsletterTransfer
    {
        return new GoogleTagManagerNewsletterTransfer();
    }
}
