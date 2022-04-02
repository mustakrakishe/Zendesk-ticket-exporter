<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Zendesk tickets exporter</title>

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="js/jquery-3.6.0.min.js"></script>
    <script defer src="js/script.js"></script>
</head>

<body>
    <div class="container">
        <h1 class="text-center my-5">Zendesk tickets exporter</h1>

        <div class="col-6 mx-auto">
            <form id="get-tickets-form" action="http/GetTickets.php" method="post">
                <div class="mb-3">
                    <label for="subdomain" class="form-label">Subdomain</label>
                    <input type="text" class="form-control" id="subdomain" name="subdomain">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div class="mb-3">
                    <label for="token" class="form-label">Token</label>
                    <input type="token" class="form-control" id="token" name="token">
                </div>
                <div id="action">
                    <button type="submit" class="btn btn-primary">Get tickets</button>
                </div>
                <div id="alert" class="mt-3 alert"></div>
            </form>
        </div>
    </div>
</body>

</html>