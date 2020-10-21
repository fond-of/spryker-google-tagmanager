<?php

namespace FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\Newsletter;

use FondOfSpryker\Yves\GoogleTagManager\ControllerEventHandler\ControllerEventHandlerInterface;
use FondOfSpryker\Yves\GoogleTagManager\Session\GoogleTagManagerSessionHandlerInterface;
use Generated\Shared\Transfer\GoogleTagManagerNewsletterDataTransfer;
use Symfony\Component\HttpFoundation\Request;

class NewsletterSubscribeEventHandler implements ControllerEventHandlerInterface
{
    public const METHOD_NAME = 'submitAction';
    public const NEWSLETTER_SUBSCRIPTION_FORM = 'NewsletterSubscriptionForm';
    public const NEWSLETTER_SUBSCRIPTION_FORM_EMAIL = 'email';

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Session\GoogleTagManagerSessionHandlerInterface
     */
    protected $sessionHandler;

    public function __construct(GoogleTagManagerSessionHandlerInterface $sessionHandler)
    {
        $this->sessionHandler = $sessionHandler;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string|null $locale
     *
     * @return void
     */
    public function handle(Request $request, ?string $locale): void
    {
        $newsletterSubscriptionData = $request->get(static::NEWSLETTER_SUBSCRIPTION_FORM);

        if (!$newsletterSubscriptionData) {
            return;
        }

        if (!isset($newsletterSubscriptionData[static::NEWSLETTER_SUBSCRIPTION_FORM_EMAIL])) {
            return;
        }

        $email = $newsletterSubscriptionData[static::NEWSLETTER_SUBSCRIPTION_FORM_EMAIL];

        $googleTagManagerNewsletterDataTransfer = new GoogleTagManagerNewsletterDataTransfer();
        $googleTagManagerNewsletterDataTransfer->setExternalIdHash(sha1($email));

        $this->sessionHandler->setNewsletterData($googleTagManagerNewsletterDataTransfer);
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return static::METHOD_NAME;
    }
}
