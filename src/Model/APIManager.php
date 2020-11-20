<?php

namespace App\Model;

use Symfony\Component\HttpClient\HttpClient;

class APIManager extends AbstractManager
{
    private $client;
    public function __construct()
    {
        $this->client = HttpClient::create();
    }
    public function getData(): array
    {
        $dataAPI = $this->client->request('GET', "http://api.citybik.es/v2/networks");
        if ($dataAPI->getStatusCode() === 200) {
            return $dataAPI->toArray();
        } else {
            return [];
        }
    }

    public function filterCountry($dataAPI)
    {
        $country = [];
        foreach ($dataAPI as $index) {
            foreach ($index as $rent) {
                $country[] = $rent["location"]["country"];
            }
        }
        return(array_unique($country));
    }

    public function filterCity($dataAPI, $country)
    {
        $city = [$country];
        foreach ($dataAPI as $index) {
            foreach ($index as $rent) {
                if ($country === $rent["location"]["country"]) {
                    $city[] = $rent["location"]["city"];
                }
            }
        }
        return $city;
    }

    public function numberHorsesCity(array $dataAPI, string $city): int
    {
        $id = "";
        foreach ($dataAPI as $index) {
            foreach ($index as $rent) {
                if ($city === $rent["location"]["city"]) {
                    $id = $rent["id"];
                }
            }
        }

        $freeHorses = 0;

        $numbers = ($this->client->request('GET', 'http://api.citybik.es/v2/networks/' . $id))->toArray();
        foreach ($numbers['network']['stations'] as $number) {
            $freeHorses += $number['free_bikes'];
        }
        return $freeHorses;
    }
}
