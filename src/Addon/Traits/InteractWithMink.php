<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Addon\Traits;

use Behat\Mink\Session;

trait InteractWithMink
{
    public function minkSession(string $sessionName): Session
    {
        return $this->getMink()->getSession($sessionName);
    }

    public function session(): Session
    {
        return $this->minkSession('laravel');
    }

    public function visitUrl(string $url): void
    {
        $this->session()->visit($url);
    }
}