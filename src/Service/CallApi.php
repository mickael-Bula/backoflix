<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApi
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * méthode de connection à une Api
     *
     * @param string $title
     * 
     * @return array
     */
    public function getMovieByTitle(string $title): array
    {
        $response = $this->client->request(
            'GET',
            'http://www.omdbapi.com/?t=' . $title . '&apikey=' . $_ENV['APIKEY']
        );
        if ($response->getStatusCode() === 200)
        {
            return $response->toArray();
        }
        throw new \Exception('napa');
    }
}