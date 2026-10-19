<h2><?= htmlspecialchars($product['title']) ?></h2>

<div class="product-card">
    <p><strong>Описание:</strong> <?= htmlspecialchars($product['description'] ?? 'Описание отсутствует.') ?></p>
    <p><strong>Стоимость:</strong> <span class="price"><?= number_format($product['price'], 2, '.', ' ') ?> руб.</span></p>
</div>

<p><a href="/product/all">&larr; Вернуться в каталог</a></p>
