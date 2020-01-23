<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \FondOfSpryker\Yves\GoogleTagManager\GoogleTagManagerFactory getFactory()
 */
class GoogleTagManagerTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const TWIG_FUNCTION_GTM_DATA_LAYER = 'dataLayer';
    protected const TWIG_FUNCTION_GOOGLE_TAG_MANAGER = 'googleTagManager';

    /**
     * {@inheritdoc}
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     * @api
     *
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig = $this->addTwigFunctions($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFunctions(Environment $twig): Environment
    {
        $twig->addFunction($this->createGtmDataLayerTwigFunction($twig));
        $twig->addFunction($this->createGoogleTagManagerTwigFunction($twig));

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    protected function createGtmDataLayerTwigFunction(Environment $twig): TwigFunction
    {
        return new TwigFunction(
            static::TWIG_FUNCTION_GTM_DATA_LAYER,
            function (Environment $twig, $page, $params) {
                return $this
                    ->getFactory()
                    ->createGoogleTagManagerTwigExtension()->renderDataLayer($twig, $page, $params);
            },
            [
                'needs_environment' => true,
                'is_safe' => ['html'],
            ]
        );
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\TwigFunction
     */
    protected function createGoogleTagManagerTwigFunction(Environment $twig): TwigFunction
    {
        return new TwigFunction(
            static::TWIG_FUNCTION_GOOGLE_TAG_MANAGER,
            function (Environment $twig, $templateName) {
                return $this
                    ->getFactory()
                    ->createGoogleTagManagerTwigExtension()->renderGoogleTagManager($twig, $templateName);
            },
            [
                'needs_environment' => true,
                'is_safe' => ['html'],
            ]
        );
    }
}
