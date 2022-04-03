<?php

use GuzzleHttp\Exception\ClientException;

if (!$_POST['subdomain'] || !$_POST['email'] || !$_POST['token']) {
    returnAjaxResponse([
        'status' => 'error',
        'body' => 'Fill all the fields, please.',
    ]);
}

require_once '../../vendor/autoload.php';

$client = new App\Services\ZendeskApi\Client($_POST['subdomain'], $_POST['email'], $_POST['token']);

try {
    $ticketsResponse = $client
        ->resource('tickets')
        ->include('users', 'groups', 'organizations')
        ->get();

    $ticketEventsResponse = $client
        ->resource('incremental/ticket_events')
        ->where('start_time', 0)
        ->include('comment_events')
        ->get();
        
} catch (ClientException $e) {
    $responseBody = json_decode($e->getResponse()->getBody()->getContents());

    returnAjaxResponse([
        'status' => 'error',
        'body' => $responseBody->error,
    ]);
}

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

$fileName =  date('Ymd_His') . '_tickets.csv';
$file = fopen('../../storage/' . $fileName, 'w');
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
    $submitter = $users[$ticket['submitter_id']];
    $requester = $users[$ticket['requester_id']];
    $group = $groups[$ticket['group_id']];
    $organization = $organizations[$ticket['organization_id']];
    $comments = $ticketComments[$ticket['id']];

    foreach ($comments as $comment) {
        fputcsv($file, [
            $ticket['id'],
            $ticket['description'],
            $ticket['status'],
            $ticket['priority'],
            $submitter['id'],
            $submitter['name'],
            $submitter['email'],
            $requester['id'],
            $requester['name'],
            $requester['email'],
            $group['id'],
            $group['name'],
            $organization['id'],
            $organization['name'],
            $comment['body'],
        ]);
    }
}

fclose($file);

returnAjaxResponse([
    'status' => 'OK',
    'body' => 'Your <a href="http/DownloadFile.php?file=' . $fileName . '">file</a> is ready for download.',
]);

function returnAjaxResponse($response) {
    echo json_encode($response);
    exit;
}
