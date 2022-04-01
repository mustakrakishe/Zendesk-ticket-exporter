<?php

namespace App\Services\ZendeskApi;

use \GuzzleHttp\Client as GuzzleClient;
use \GuzzleHttp\Psr7\Request;

class Client
{
    public function __construct(string $subdomain, string $email, string $token)
    {
        $this->client = new GuzzleClient([
            'base_uri' => 'https://' . $subdomain . '.zendesk.com/api/v2/',
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($email . '/token:' . $token),
            ],
        ]);
    }

    public function resource(string $resource)
    {
        return new QueryBuilder($this->client, new Request('GET', $resource));
    }
}