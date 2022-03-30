<?php

require_once '../vendor/autoload.php';

$EMAIL = 'mustakrkish@gmail.com';
$SUBDOMAIN = 'mystakrakisheco';
$TOKEN = 'SYPia0e4Eu49h22bsepcFjwQbDgnwPrsh2hCGcuD';

$client = new \App\Services\ZendeskApi($SUBDOMAIN, $EMAIL, $TOKEN);
$response = $client->getTickets();

$tickets = json_decode($response->getBody()->getContents(), true)['tickets'];

echo '<pre>';
print_r($tickets);
echo '</pre>';