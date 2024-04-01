<?php

namespace PHPDIContainer\Tests;

use Aruka\Container\Container;
use Aruka\Container\Exceptions\ContainerException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{

    public function test_getServiceFromContainer()
    {
        $container = new Container();

        $container->add('aruka-class', ArukaClass::class);

        $this->assertInstanceOf(ArukaClass::class, $container->get('aruka-class'));
    }

    public function test_containerHasContainerException()
    {
        $container = new Container();

        $this->expectException(ContainerException::class);

        $container->add('not-existing-class');
    }

    public function test_containerHasMethod()
    {
        $container = new Container();

        $container->add('aruka-class', ArukaClass::class);

        $this->assertTrue($container->has('aruka-class'));

        $this->assertFalse($container->has('not-existing-class'));
    }

    public function test_recursiveAutowired()
    {
        $container = new Container();

        $container->add('aruka-class', ArukaClass::class);

        /** var ArukaClass $arukaClass */
        $arukaClass = $container->get('aruka-class');

        /** var AnotherClass $anotherClass */
        $anotherClass = $arukaClass->getAnotherClass();

        $this->assertInstanceOf(AnotherClass::class,$arukaClass->getAnotherClass());
        $this->assertInstanceOf(YouTube::Class,$anotherClass->getYoutube());
        $this->assertInstanceOf(Telegram::class, $anotherClass->getTelegram());
    }

}
