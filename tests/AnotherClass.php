<?php

namespace PHPDIContainer\Tests;

class AnotherClass
{
    public function __construct(
        private readonly Telegram $telegram,
        private readonly YouTube $youtube,
    )
    {
    }

    public function getTelegram(): Telegram
    {
        return $this->telegram;
    }

    public function getYoutube(): YouTube
    {
        return $this->youtube;
    }
}
