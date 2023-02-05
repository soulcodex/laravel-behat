<?php

declare(strict_types=1);

namespace Soulcodex\Behat\Mink;

use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Session as MinkSession;
use Exception;
use Soulcodex\Behat\Mink\Exception\UnmarshalJsonBody;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

final class MinkHandler
{
    public function __construct(private MinkSession $session)
    {
    }

    public function request(string $method, string $url, array $parameters = [], array $headers = []): Crawler
    {
        throw new Exception('Request send handling not implemented');
    }

    public function session(): MinkSession
    {
        return $this->session;
    }

    public function driver(): DriverInterface
    {
        return $this->session()->getDriver();
    }

    public function client(): AbstractBrowser
    {
        return $this->driver()->getClient();
    }

    public function clientRequest(): object
    {
        return $this->client()->getRequest();
    }

    public function jsonResponse(): array
    {
        $plainResponse = $this->session()->getPage()->getContent();
        $body = json_decode($plainResponse, true);

        if (!is_array($body)) {
            throw UnmarshalJsonBody::unmarshalBody($plainResponse);
        }

        return $body;
    }

    public function plainResponse(): string
    {
        return $this->session()->getPage()->getContent();
    }

    public function responseHeaders(): array
    {
        return $this->normalizedHeaders(array_change_key_case($this->session()->getResponseHeaders()));
    }

    public function hasResponseHeader(string $headerName): bool
    {
        return array_key_exists($headerName, $this->responseHeaders());
    }

    public function responseHeaderByName(string $headerName): mixed
    {
        return $this->hasResponseHeader($headerName)
            ? $this->responseHeaders()[$headerName]
            : null;
    }

    public function basicAuth(string $username, string $password): void
    {
        $this->client()->setServerParameter('PHP_AUTH_USER', $username);
        $this->client()->setServerParameter('PHP_AUTH_PW', $password);
    }

    public function resetServerParameters(): void
    {
        $this->client()->setServerParameters([]);
    }

    public function responseContain(string $needle): void
    {
        if (!str_contains($this->plainResponse(), $needle)) {
            throw new Exception(sprintf("The response don't contain <%s>", $needle));
        }
    }

    private function resetSession(): void
    {
        $this->session()->reset();
        $this->resetServerParameters();
    }

    private function normalizedHeaders(array $headers): array
    {
        return array_map('implode', array_filter($headers));
    }
}