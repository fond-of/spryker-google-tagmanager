<?php

namespace FondOFSpryker\Yves\GoogleTagManager\Twig;

use Spryker\Shared\Twig\TwigExtension;
use Twig_Environment;
use Twig_SimpleFunction;

class GoogleTagManagerTwigExtension extends TwigExtension
{
    const FUNCTION_GOOGLE_TAG_MANAGER = 'fondOfSpykerGoogleTagManager';

    /**
     * @var string
     */
    protected $containerID;

    /**
     * @param string $containerID
     */
    public function __construct($containerID)
    {
        $this->containerID = $containerID;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            $this->createGoogleTagManagerFunction()
        ];
    }

    /**
     * @return \Twig_SimpleFunction
     */
    protected function createGoogleTagManagerFunction()
    {
        return new Twig_SimpleFunction(
            static::FUNCTION_GOOGLE_TAG_MANAGER,
            [$this, 'renderGoogleTagManager'],
            [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param string $templateName
     *
     * @return string
     */
    public function renderGoogleTagManager(Twig_Environment $twig, $templateName) : string
    {
        if (!$this->containerID) {
            return '';
        }

        return $twig->render($templateName, [
            'containerID' => $this->containerID,
        ]);
    }
}
