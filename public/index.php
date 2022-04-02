<?php

require_once '../vendor/autoload.php';

$EMAIL = 'mustakrkish@gmail.com';
$SUBDOMAIN = 'mystakrakisheco';
$TOKEN = 'SYPia0e4Eu49h22bsepcFjwQbDgnwPrsh2hCGcuD';

echo 'Load a data...' . '<br>';

$client = new App\Services\ZendeskApi\Client($SUBDOMAIN, $EMAIL, $TOKEN);

$ticketsResponse = $client
    ->resource('tickets')
    ->include('users', 'groups', 'organizations')
    ->get();

$ticketEventsResponse = $client
    ->resource('incremental/ticket_events')
    ->where('start_time', 0)
    ->include('comment_events')
    ->get();

echo 'Data is loaded.' . '<br>';
echo 'Prepare the data...' . '<br>';

$users = array_column($ticketsResponse['users'], null, 'id');
$groups = array_column($ticketsResponse['groups'], null, 'id');
$organizations = array_column($ticketsResponse['organizations'], null, 'id');

$ticketComments = [];
foreach ($ticketEventsResponse['ticket_events'] as $ticketEvent) {
    $ticketId = $ticketEvent['ticket_id'];

    foreach ($ticketEvent['child_events'] as $childEvent) {
        if ($childEvent['body']) {
            $ticketComments[$ticketId][] = $childEvent;
        }
    }
}

echo 'Data is prepared.' . '<br>';
echo 'Write the data to a file...' . '<br>';

$file = fopen('../storage/tickets.csv', 'w');
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

foreach ($ticketsResponse['tickets'] as $ticket) {
    $agent = $users[$ticket['submitter_id']];
    $contact = $users[$ticket['requester_id']];
    $group = $groups[$ticket['group_id']];
    $organization = $organizations[$ticket['organization_id']];
    $comments = $ticketComments[$ticket['id']];

    foreach ($comments as $comment) {
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
            $comment['body'],
        ]);
    }
}

fclose($file);

echo 'The data is writed to the file.' . '<br>';
