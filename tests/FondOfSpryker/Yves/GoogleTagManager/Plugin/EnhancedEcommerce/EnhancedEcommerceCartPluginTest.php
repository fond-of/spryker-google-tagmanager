<?php

namespace FondOfSpryker\Yves\GoogleTagManager\Plugin\EnhancedEcommerce;

use Codeception\Test\Unit;
use ReflectionClass;

class EnhancedEcommerceCartPluginTest extends Unit
{
    /**
     * @return void
     */
    protected function _before()
    {
        parent::_before(); // TODO: Change the autogenerated stub
    }

    /**
     * @return void
     */
    public function testHandleSuccess(): void
    {
    }

    protected function getMethod(string $name)
    {
        $class = new ReflectionClass(EnhancedEcommerceCartPlugin::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}