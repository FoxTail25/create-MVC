<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/project/webroot/css/style.css" />
    <title><?= isset($title) ? $title : 'MVC';  ?></title>
</head>

<body>

<header>
    <h1>Мой Самописный PHP MVC-Фреймворк</h1>
</header>

<nav>
    <a href="/">Главная</a>
    <a href="/product/all">Каталог товаров</a>
</nav>

<div class="container">
    <!-- Сюда наш движок View автоматически подставит контент вьюшки -->
    <?= $content ?>
</div>

</body>
</html>