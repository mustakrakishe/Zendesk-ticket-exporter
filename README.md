# Zendesk ticket exporter

A web API for [Zendesk](https://www.zendesk.com/) ticket export to CSV

## CSV description

A ticket table fields are given below.

| Name          | Description                   |
|---------------|-------------------------------|
| Ticket ID     | a ticket id                   |
| Description   | a ticket description          |
| Status        | a ticket status               |
| Priority      | a ticket priority             |
| Agent ID      | a ticket submitter id         |
| Agent Name    | a ticket submitter name       |
| Agent Email   | a ticket submitter email      |
| Contact ID    | a ticket requester id         |
| Contact Name  | a ticket requester name       |
| Contact Email | a ticket requester email      |
| Group ID      | a ticket group id             |
| Group Name    | a ticket group name           |
| Company ID    | a ticket organization id      |
| Company Name  | a ticket organization name    |
| Comment       | a ticket comment              |

> Each comment is represended with individual table row doubling the ticket other info.

## Web UI

An app web UI is represented with:
- a required data input form;
- a notification alert.

Form requiers the follow data:
- subdomain;
- email;
- token.

 See [What do I need to make a request?](https://developer.zendesk.com/documentation/developer-tools/beginners-guide-to-the-zendesk-developer-tools/part-1-welcome-to-the-zendesk-platform/#what-do-i-need-to-make-a-request) for more about required data.

> A token using is prefered then a password cause a Zendesk account owner stays abel to give a developer a resource managment only but not the account preferences. 

Form data is empty validating. UI notificateы a useк about process state with an alert under the input form.

## Deployment

This project includes a docker-compose and docker files. So you can up an environment with a [Docker](https://docker.com/).

To up a Docker containers open a CMD in the project dir and run:
```
docker-compose up -d
```
A docker will run 3 containers:
- nginx;
- php-fpm;
- composer.

Composer container will install all required dependencied in a few seconds after it start and shut down by itself. Eventually, you will have two runnig containers: nginx and php-fpm.

## Usage
Browse your site (localhost:80 by default).

The UI will looks like showing below.

![Init state](https://github.com/mustakrakishe/Zendesk-ticket-exporter/blob/master/screenshots/01_init.jpg "Init state")

Fill and submit the form.
An alert will notificate you if something is wrong.

![Notification](https://github.com/mustakrakishe/Zendesk-ticket-exporter/blob/master/screenshots/02_notification.jpg "Notification")

In several seconds, when CSV will be ready for download, you will be invited.

![Download](https://github.com/mustakrakishe/Zendesk-ticket-exporter/blob/master/screenshots/03_download.jpg "Download")

An example of the downloaded csv is shown below.

![CSV](https://github.com/mustakrakishe/Zendesk-ticket-exporter/blob/master/screenshots/04_csv.jpg "CSV")