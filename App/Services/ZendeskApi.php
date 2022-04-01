<?php

namespace App\Services;

use \App\Resources\QueryBuilder;
use \GuzzleHttp\Client;
use \GuzzleHttp\Psr7\Request;

class ZendeskApi
{
    public function __construct(string $subdomain, string $email, string $token)
    {
        $this->client = new Client([
            'base_uri' => 'https://' . $subdomain . '.zendesk.com/api/v2/',
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($email . '/token:' . $token),
            ],
        ]);
    }

    public function tickets()
    {
        return new QueryBuilder($this->client, new Request('GET', 'tickets'));
    }
}