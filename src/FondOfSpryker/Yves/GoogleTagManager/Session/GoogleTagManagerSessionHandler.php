<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Session;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface;
use Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\TransferUnserializationException;
use Spryker\Shared\Log\LoggerTrait;

class GoogleTagManagerSessionHandler implements GoogleTagManagerSessionHandlerInterface
{
    use LoggerTrait;

    public const SESSION_NEWSLETTER_DATA = 'SESSION_NEWSLETTER_DATA';

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface
     */
    protected $sessionClient;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Dependency\Client\GoogleTagManagerToSessionClientInterface $sessionClient
     */
    public function __construct(GoogleTagManagerToSessionClientInterface $sessionClient)
    {
        $this->sessionClient = $sessionClient;
    }

    /**
     * @param \Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer $googleTagManagerNewsletterTransfer
     *
     * @return void
     */
    public function setNewsletterData(GoogleTagManagerNewsletterTransfer $googleTagManagerNewsletterTransfer): void
    {
        $this->sessionClient->set(static::SESSION_NEWSLETTER_DATA, $googleTagManagerNewsletterTransfer->serialize());
    }

    /**
     * @return \Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer
     */
    public function getNewsletterData(): GoogleTagManagerNewsletterTransfer
    {
        $googleTagManagerNewsletterTransfer = new GoogleTagManagerNewsletterTransfer();

        if (!$this->sessionClient->get(static::SESSION_NEWSLETTER_DATA)) {
            return $googleTagManagerNewsletterTransfer;
        }

        try {
            $googleTagManagerNewsletterTransfer->unserialize(
                $this->sessionClient->get(static::SESSION_NEWSLETTER_DATA)
            );
        } catch (TransferUnserializationException $e) {
            $this->getLogger()->notice(sprintf(
                'GoogleTagManager: cant get newsletter session data in %s',
                self::class
            ), ['message' => $e->getMessage()]);
        }

        return $googleTagManagerNewsletterTransfer;
    }

    /**
     * @return void
     */
    public function removeNewsletterData(): void
    {
        $this->sessionClient->remove(static::SESSION_NEWSLETTER_DATA);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function remove(string $key): void
    {
        $this->sessionClient->remove($key);
    }
}
