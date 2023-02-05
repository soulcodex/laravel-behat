<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Mink;

use Behat\Gherkin\Node\PyStringNode;
use Symfony\Component\DomCrawler\Crawler;

final class MinkRequestHandler
{
    public function __construct(private MinkHandler $handler)
    {
    }

    public function sendRequest(string $method, string $url, array|PyStringNode $parameters = [], array $headers = []): void
    {
        $content = $parameters instanceof PyStringNode
            ? ['content' => $parameters->getRaw()]
            : $parameters;

        $this->request($method, $url, $content, $headers);
    }

    public function basicHttpAuthentication(string $username, string $password): void
    {
        $this->handler->basicAuth($username, $password);
    }

    private function request($method, $url, array $optionalParams = [], array $headers = []): Crawler
    {
        return $this->handler->request($method, $url, $optionalParams, $headers);
    }
}