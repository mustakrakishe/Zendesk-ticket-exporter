<?php

use App\Services\ZendeskApi;
use GuzzleHttp\Client;

require_once('../vendor/autoload.php');

$EMAIL = 'mustakrkish@gmail.com';
$SUBDOMAIN = 'mystakrakisheco';
$TOKEN = 'SYPia0e4Eu49h22bsepcFjwQbDgnwPrsh2hCGcuD';


$client = new ZendeskApi($SUBDOMAIN, $EMAIL, $TOKEN);
echo $client->getTickets();