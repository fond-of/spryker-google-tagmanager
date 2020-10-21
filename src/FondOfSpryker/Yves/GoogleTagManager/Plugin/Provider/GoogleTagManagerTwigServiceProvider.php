<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class GoogleTagManagerTwigServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $googleTagManagerTwigExtension = $this
            ->getFactory()
            ->createGoogleTagManagerTwigExtension();

        $app['twig'] = $app->share(
            $app->extend(
                'twig',
                function (Environment $twig) use ($googleTagManagerTwigExtension, $app) {
                    $twig->addExtension($googleTagManagerTwigExtension);

                    return $twig;
                }
            )
        );
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
