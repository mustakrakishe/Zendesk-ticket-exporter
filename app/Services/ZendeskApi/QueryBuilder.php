<?php

namespace App\Services\ZendeskApi;

use \GuzzleHttp\Psr7\Uri;

class QueryBuilder
{
    private $client;
    private $request;

    public function __construct(\GuzzleHttp\Client $client, \GuzzleHttp\Psr7\Request $request)
    {
        $this->client = $client;
        $this->request = $request;
    }

    public function with(string ...$relationships)
    {
        $this->request->withUri(
            Uri::withQueryValue(
                $this->request->getUri,
                'include',
                implode(',', $relationships)
            )
        );

        return $this;
    }

    public function get()
    {
        $response = $this->client->send($this->request);

        $content = $response->getBody()->getContents();
        return json_decode($content, true)['tickets'];
    }
}