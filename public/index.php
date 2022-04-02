<?php

require_once '../vendor/autoload.php';

$EMAIL = 'mustakrkish@gmail.com';
$SUBDOMAIN = 'mystakrakisheco';
$TOKEN = 'SYPia0e4Eu49h22bsepcFjwQbDgnwPrsh2hCGcuD';

echo 'Load a data...' . '<br>';

$client = new App\Services\ZendeskApi\Client($SUBDOMAIN, $EMAIL, $TOKEN);

$response = $client
    ->resource('incremental/ticket_events')
    ->where('start_time', 0)
    ->include('comment_events')
    ->get();

$ticketComments = [];
foreach ($response['ticket_events'] as $ticketEvent) {
    $ticketId = $ticketEvent['ticket_id'];

    foreach ($ticketEvent['child_events'] as $childEvent) {
        if ($childEvent['body']) {
            $datetime = $childEvent['created_at'];
            $ticketComments[$ticketId][$datetime] = $childEvent['body'];
        }
    }
}

$response = $client
    ->resource('tickets')
    ->include('users', 'groups', 'organizations')
    ->get();

echo 'Data is loaded.' . '<br>';
echo 'Prepare loaded data...' . '<br>';

$users = array_column($response['users'], null, 'id');
$groups = array_column($response['groups'], null, 'id');
$organizations = array_column($response['organizations'], null, 'id');
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
    $comments = $ticketComments[$ticket['id']];

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
        implode('/r/n', $comments),
    ]);
}

fclose($file);

echo 'The data is writed to the file.' . '<br>';
