<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder;

use Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer;

interface NewsletterFieldPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer $googleTagManagerNewsletterTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer
     */
    public function handle(
        GoogleTagManagerNewsletterTransfer $googleTagManagerNewsletterTransfer,
        array $params = []
    ): GoogleTagManagerNewsletterTransfer;
}
