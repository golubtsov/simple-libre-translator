<?php

namespace Nigo\Translator;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class LibreTranslator
{
    private string $url;

    private string $target;

    public function __construct(
        private readonly HttpClientInterface $client,
        string                               $target,
        string                               $url
    )
    {
        $this->url = $url;
        $this->target = $target;
    }

    public static function create(string $target): static
    {
        return new static(HttpClient::create(), $target, $_ENV['TRANSLATOR_URL']);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function send(string $text): array
    {
        $response = $this->client->request('POST', $this->url, [
            'body' => [
                'q' => $text,
                'source' => 'auto',
                'target' => $this->target,
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ]
        ]);

        return json_decode($response->getContent(), true);
    }


    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function translate(string $text)
    {
        return $this->send($text)['translatedText'];
    }
}