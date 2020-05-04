<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use Generated\Shared\Transfer\GoogleTagManagerNewsletterDataTransfer;

interface GoogleTagManagerSessionHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerNewsletterDataTransfer $googleTagManagerNewsletterDataTransfer
     *
     * @return void
     */
    public function setNewsletterData(GoogleTagManagerNewsletterDataTransfer $googleTagManagerNewsletterDataTransfer): void;

    /**
     * @return array
     */
    public function getNewsletterData(): array;

    /**
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void;
}
