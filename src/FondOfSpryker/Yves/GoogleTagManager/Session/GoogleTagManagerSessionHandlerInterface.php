<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer;

interface GoogleTagManagerSessionHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer $googleTagManagerNewsletterTransfer
     *
     * @return void
     */
    public function setNewsletterData(GoogleTagManagerNewsletterTransfer $googleTagManagerNewsletterTransfer): void;

    /**
     * @return \Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer
     */
    public function getNewsletterData(): GoogleTagManagerNewsletterTransfer;

    /**
     * @return void
     */
    public function removeNewsletterData(): void;

    /**
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void;
}
