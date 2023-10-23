<?php

namespace App\Services;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiService
{
    private const API_URL = 'https://api.pexels.com/v1/search?query=';


    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getImage(string $city): array
    {
        $client = HttpClient::create();
        $header = [
            'Authorization' => $_ENV['PEXEL_API_KEY'],
        ];

        $reponse  = $client->request(
            'GET',
            self::API_URL . $city,
            [
                'headers' => $header,
            ]
        );
        $statusCode = $reponse->getStatusCode();
        if ($statusCode !== 200) {
            return [];
        }

        return $reponse->toArray();
    }
}
