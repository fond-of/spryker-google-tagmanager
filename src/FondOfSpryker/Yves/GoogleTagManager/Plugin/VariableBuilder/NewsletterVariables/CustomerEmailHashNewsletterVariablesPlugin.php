<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\NewsletterVariables;

use FondOfSpryker\Shared\GoogleTagManager\GoogleTagManagerConstants;
use FondOfSpryker\Yves\GoogleTagManager\Session\GoogleTagManagerSessionHandlerInterface;

class CustomerEmailHashNewsletterVariablesPlugin
{
    public const EMAIL = 'email';
    public const EXTERNAL_ID_HASH = 'external_id_hash';

    /**
     * @var \FondOfSpryker\Yves\GoogleTagManager\Session\GoogleTagManagerSessionHandlerInterface
     */
    protected $sessionHandler;

    /**
     * @param \FondOfSpryker\Yves\GoogleTagManager\Session\GoogleTagManagerSessionHandlerInterface $sessionHandler
     */
    public function __construct(GoogleTagManagerSessionHandlerInterface $sessionHandler)
    {
        $this->sessionHandler = $sessionHandler;
    }

    /**
     * @param array $variables
     * @param array $params
     *
     * @return array
     */
    public function handle(array $variables, array $params = []): array
    {
        $googleTagManagerNewsletterDataArray = $this->sessionHandler->getNewsletterData();

        if (!isset($googleTagManagerNewsletterDataArray[static::EXTERNAL_ID_HASH])) {
            return [];
        }

        $this->sessionHandler->remove(GoogleTagManagerConstants::SESSION_NEWSLETTER_DATA);

        return [
            'externalIdHash' => $googleTagManagerNewsletterDataArray[static::EXTERNAL_ID_HASH],
        ];
    }
}
