<?php

namespace Alpixel\Bundle\MenuBundle\Tests\Builder;

use Alpixel\Bundle\MenuBundle\Builder\MenuBuilder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MenuBuilderTest extends KernelTestCase
{
    public function setUp()
    {
        self::bootKernel();
        static::$kernel->getContainer();
    }

    public function testIsValidLocale()
    {
        $this->assertEquals(true, MenuBuilder::isValidLocale('fr'));
        $this->assertEquals(false, MenuBuilder::isValidLocale([]));
    }

    public function testIsValidMachineName()
    {
        $this->assertEquals(true, MenuBuilder::isValidMachineName('Machine Name'));
        $this->assertEquals(false, MenuBuilder::isValidMachineName([]));
    }
}