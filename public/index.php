<?php

require_once '../vendor/autoload.php';

$EMAIL = 'mustakrkish@gmail.com';
$SUBDOMAIN = 'mystakrakisheco';
$TOKEN = 'SYPia0e4Eu49h22bsepcFjwQbDgnwPrsh2hCGcuD';

echo 'Load a data...' . '<br>';

$client = new App\Services\ZendeskApi\Client($SUBDOMAIN, $EMAIL, $TOKEN);

$response = $client
    ->resource('tickets')
    ->include('users', 'groups', 'organizations')
    ->get();

echo 'Data is loaded.' . '<br>';
echo 'Prepare loaded data...' . '<br>';

$users = setIdAsKey($response['users']);
$groups = setIdAsKey($response['groups']);
$organizations = setIdAsKey($response['organizations']);
// $comments = setIdAsKey($response['comments']);

echo 'Data is prepared.' . '<br>';
echo 'Write the data to a file...' . '<br>';

$file = fopen('tickets.csv', 'w');
fputcsv($file, [
    'Ticket ID',
    'Description',
    'Status',
    'Priority',
    'Agent ID',
    'Agent Name',
    'Agent Email',
    'Contact ID',
    'Contact Name',
    'Contact Email',
    'Group ID',
    'Group Name',
    'Company ID',
    'Company Name',
    'Comments',
]);

foreach ($response['tickets'] as $ticket) {
    $agent = $users[$ticket['submitter_id']];
    $contact = $users[$ticket['requester_id']];
    $group = $groups[$ticket['group_id']];
    $organization = $organizations[$ticket['organization_id']];
    $comment = '';

    fputcsv($file, [
        $ticket['id'],
        $ticket['description'],
        $ticket['status'],
        $ticket['priority'],
        $agent['id'],
        $agent['name'],
        $agent['email'],
        $contact['id'],
        $contact['name'],
        $contact['email'],
        $group['id'],
        $group['name'],
        $organization['id'],
        $organization['name'],
    ]);
}

fclose($file);

echo 'The data is writed to the file.' . '<br>';

function setIdAsKey($array) {
    $ids = array_column($array, 'id');
    return array_combine($ids, $array);
}
