<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\VariableBuilder\NewsletterVariables;

use FondOfSpryker\Yves\GoogleTagManager\Dependency\VariableBuilder\NewsletterFieldPluginInterface;
use FondOfSpryker\Yves\GoogleTagManager\Session\GoogleTagManagerSessionHandlerInterface;
use Generated\Shared\Transfer\GoogleTagManagerNewsletterTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class NewsletterFieldCustomerEmailHashPlugin extends AbstractPlugin implements NewsletterFieldPluginInterface
{
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

    public function handle(
        GoogleTagManagerNewsletterTransfer $googleTagManagerNewsletterTransfer,
        array $params = []
    ): GoogleTagManagerNewsletterTransfer {
        $gtmSessionClient = $this
            ->getFactory()
            ->getGoogleTagManagerSessionHandler();

        $googleTagManagerNewsletterTransfer->setExternalIdHash(
            $gtmSessionClient
                ->getNewsletterData()
                ->getExternalIdHash()
        );

        $gtmSessionClient->removeNewsletterData();

        return $googleTagManagerNewsletterTransfer;
    }
}
