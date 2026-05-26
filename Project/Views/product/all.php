<div>
    <h2>Products</h2>
    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <a href="/product/<?= $product['id'] ?>">
                    <?= $product['name'] ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <br />
    <br />
    <a href="/">Главная страница</a>
</div>