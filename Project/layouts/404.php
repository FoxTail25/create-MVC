<?php
http_response_code(404);
?>
</head>
<link rel="stylesheet" type="text/css" href="/project/webroot/css/style.css" />

<title>404</title>

<head>

<body>
    <div class="content">
        <?= isset($content) ? $content : ''; ?>
    </div>
</body>
<?php
exit();
