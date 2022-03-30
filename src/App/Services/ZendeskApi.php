<?php

namespace App\Services;

use GuzzleHttp\Client;

class ZendeskApi
{
    public function __construct(string $subdomain, string $email, string $token)
    {
        $this->client = new Client([
            'base_uri' => 'https://' . $subdomain . '.zendesk.com/api/v2/',
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($email . '/tocken:' . $token),
            ],
        ]);
    }

    public function getTickets()
    {
        return $this->client->get('tickets');
    }
}