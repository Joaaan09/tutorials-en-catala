<?php

namespace App\Services;

use GuzzleHttp\Client;

class IFixitService
{
    protected $client;
    protected $baseUrl = 'https://www.ifixit.com/api/2.0/';

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
    }

    public function getTutorials($category = 'Electronics')
    {
        $response = $this->client->get('guides', [
            'query' => ['category' => $category]
        ]);
        
        return json_decode($response->getBody(), true);
    }

    public function getTutorialDetails($guideid)
    {
        // Obtiene el tutorial con todos sus pasos incluidos
        $response = $this->client->get("guides/{$guideid}");
        return json_decode($response->getBody(), true);
    }

    // Este método probablemente NO es necesario, ya que los pasos vienen en getTutorialDetails
    public function getStepDetails($guideId, $stepId)
    {
        // Corrección: Usar GET en lugar de PATCH
        $response = $this->client->get("guides/{$guideId}/steps/{$stepId}");
        return json_decode($response->getBody(), true);
    }
}