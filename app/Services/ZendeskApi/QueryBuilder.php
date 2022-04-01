<?php

namespace App\Services\ZendeskApi;

use \GuzzleHttp\Psr7\Uri;

class QueryBuilder
{
    private \GuzzleHttp\Client $client;
    private \GuzzleHttp\Psr7\Request $request;

    public function __construct(\GuzzleHttp\Client $client, \GuzzleHttp\Psr7\Request $request)
    {
        $this->client = $client;
        $this->request = $request;
    }

    public function include(string ...$relationships)
    {
        $uri = Uri::withQueryValue(
            $this->request->getUri(),
            'include',
            implode(',', $relationships)
        );

        $this->request = $this->request->withUri($uri);

        return $this;
    }

    public function get()
    {
        $response = $this->client->send($this->request);

        $content = $response->getBody()->getContents();
        return json_decode($content, true);
    }
}