<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Codeception\Test\Unit;
use Twig_Environment;

class EnhancedEcommerceProductDetailPluginTest extends Unit
{
    protected $twigEnvironmentMock;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->twigEnvironmentMock = $this->createMock(Twig_Environment::class);
    }

    /**
     * @return void
     */
    public function handleSucces(): void
    {
        $enhancedEcommerceProductDetailPlugin = new EnhancedEcommerceProductDetailPlugin();
    }
}
