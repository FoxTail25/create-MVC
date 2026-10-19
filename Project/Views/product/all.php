<h2>Каталог товаров</h2>

<?php if (empty($products)): ?>
    <p>Товары не найдены.</p>
<?php else: ?>
    <?php foreach ($products as $product): ?>
        <div class="product-card">
            <h3><?= htmlspecialchars($product['title']) ?></h3>
            <p>Цена: <span class="price"><?= number_format($product['price'], 2, '.', ' ') ?> руб.</span></p>
            <a href="/product/<?= $product['id'] ?>">Подробнее о товаре</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
