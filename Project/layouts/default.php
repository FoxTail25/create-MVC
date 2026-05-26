<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/project/webroot/css/style.css" />
    <title><?= isset($title) ? $title : 'MVC';  ?></title>
</head>

<body>
    <div class="content">
        <?= isset($content) ? $content : ''; ?>
    </div>
    <script src="/project/webroot/js/hello.js"></script>
</body>

</html>