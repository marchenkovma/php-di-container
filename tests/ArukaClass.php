<?php

namespace PHPDIContainer\Tests;

class ArukaClass
{

    public function __construct(
        private readonly AnotherClass $anotherClass
    )
    {
    }

    public function getAnotherClass(): AnotherClass
    {
        return $this->anotherClass;
    }
}
