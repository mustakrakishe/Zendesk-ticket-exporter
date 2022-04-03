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

    public function get()
    {
        $response = $this->client->send($this->request);
        $content = $response->getBody()->getContents();

        return json_decode($content, true);
    }

    public function include(string ...$relationships)
    {
        return $this->where('include', implode(',', $relationships));
    }

    public function where(string $param, string $value)
    {
        $this->request = $this->request->withUri(
            Uri::withQueryValue(
                $this->request->getUri(),
                $param,
                $value
            )
        );

        return $this;
    }
}