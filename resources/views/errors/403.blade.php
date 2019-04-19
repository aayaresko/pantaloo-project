<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>403</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/forbidden.css?v=1.0.2">
</head>
<body class="ipBlock">

<div class="msgPanel ipBlock">
    <div class="msg-content">
        <div class="panel panel-default">
            <div class="panel-body">
                <span class="msg-img"></span>
                <div class="msg-title">Prohibited Visit</div>
                <div class="msg-text"><p>Due to local regulatory rules, viewing and using this website is prohibited from your current location.</p>RefNo: {{ \Helpers\GeneralHelper::visitorIpCloudFire() }}</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>