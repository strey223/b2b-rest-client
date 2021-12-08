<?php
namespace b2b;

use GuzzleHttp\Client;

class Api
{
    /** @var Client $client */
    protected $client;
    protected $host;
    protected $apiKey;

    protected $lastStatus;

    public function __construct($host, $apiKey)
    {
        $this->client = new Client([
            'base_uri' => "https://$host",
            'headers' => [
                'ApiKey' => $apiKey,
            ],
        ]);
    }

    public function allowedFeatures()
    {
        $response = $this->client->request('GET', '/certsrv/ca/allowed_features');
        $this->lastStatus = $response->getStatusCode();

        return $response->getBody()->getContents();
    }

    public function uploadCertificate($certificateContentBase64, $isFeatureCodes = false)
    {
        $params = ['certificate' => $certificateContentBase64];
        if ($isFeatureCodes) {
            $params['feature_codes'] = '210';
        }

        $response = $this->client->request('POST', '/certsrv/ca/upload_certificate', [
            'json' => $params,
        ]);
        $this->lastStatus = $response->getStatusCode();

        return $response->getBody()->getContents();
    }
}