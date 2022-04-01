<?php

require_once '../vendor/autoload.php';

$EMAIL = 'mustakrkish@gmail.com';
$SUBDOMAIN = 'mystakrakisheco';
$TOKEN = 'SYPia0e4Eu49h22bsepcFjwQbDgnwPrsh2hCGcuD';

$client = new \App\Services\ZendeskApi($SUBDOMAIN, $EMAIL, $TOKEN);
$tickets = $client->getTickets();

$FIELD_HEADERS = [
    'id' => 'Ticket ID',
    'description' => 'Description',
    'status' => 'Status',
    'priority' => 'Priority',
    'submitter_id' => 'Agent ID',
    'Agent Name',
    'Agent Email',
    'requester_id' => 'Contact ID',
    'Contact Name',
    'Contact Email',
    'group_d' => 'Group ID',
    'Group Name',
    'brand_id' => 'Company ID',
    'Company Name',
    'Comments',
];

echo '<pre>';
print_r($tickets);
echo '</pre>';
