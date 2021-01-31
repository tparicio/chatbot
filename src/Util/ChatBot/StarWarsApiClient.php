<?php

namespace App\Util\ChatBot;

class StarWarsApiClient
{
    /**
     * @var string URI to StarWars API
     */
    private const URI = 'https://inbenta-graphql-swapi-prod.herokuapp.com/api';

    /**
     * simple Grahql client with curl request.
     **/
    private function request(string $query): ?array
    {
        $headers = [];
        $headers[] = 'Content-Type: application/json';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, self::URI);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            return null;
        }
        try {
            $response = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return null;
        }

        return $response;
    }

    /**
     * Return a list of films from graphql.
     *
     * @return array
     */
    public function getFilms(): ?array
    {
        $query = '{"query":"{allFilms{edges{node{title}}}}","variables":{}}';
        $response = $this->request($query);
        $films = $response['data']['allFilms']['edges'] ?? [];

        return array_map(static function ($item) {
            return $item['node'] ?? null;
        }, $films);
    }

    /**
     * Return a list of films from graphql.
     *
     * @return array
     */
    public function getCharacters(): ?array
    {
        $query = '{"query":"{allPeople{edges{node{name}}}}","variables":{}}';
        $response = $this->request($query);
        $characters = $response['data']['allPeople']['edges'] ?? [];

        return array_map(static function ($item) {
            return $item['node'] ?? null;
        }, $characters);
    }
}
