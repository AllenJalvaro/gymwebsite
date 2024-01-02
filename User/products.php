<?php
// Select all products ordered by the date added
$stmt = $pdo->query('SELECT * FROM products ORDER BY date_added DESC');
// Fetch all products from the database
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the total number of products
$total_products = count($products);
?>

<?=template_header('Products')?>

<div class="products content-wrapper">
    
    <br><br>
    <div class="tabs">
        <a href="indexshop.php?page=products&p=1" class="active">All</a>
        <a href="indexshop.php?page=products&p=2">Promo Deals</a>
        <a href="indexshop.php?page=products&p=3">Activewear</a>
        <a href="indexshop.php?page=products&p=4">Shoes</a>
        <a href="indexshop.php?page=products&p=5">Weights</a>
    </div>

    <p><?= number_format($total_products) ?> Products</p>
    <div class="products-wrapper">
        <?php foreach ($products as $product): ?>
            <a href="indexshop.php?page=product&id=<?=$product['id']?>" class="product">
                <img src="imgs/<?=$product['img']?>" width="200" height="200" alt="<?=$product['name']?>">
                <span class="name"><?=$product['name']?></span>
                <span class="price">
                    &#8369;<?= number_format($product['price'], 2) ?>
                    <?php if ($product['rrp'] > 0): ?>
                        <span class="rrp">&#8369;<?= number_format($product['rrp'], 2) ?></span>
                    <?php endif; ?>
                </span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
    .products-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 5px; /* Adjust the spacing between products */
    }

    .product {
        text-align: center;
        width: 200px;
        text-decoration: none;
        color: #000;
    }

    .product img {
        width: 100%;
        height: 200px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    .name {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .price {
        font-size: 1.2em;
        color: #9e0e0e !important;
        font-weight: 700;
    }

    .rrp {
        text-decoration: line-through;
        color: #999;
    }
</style>

<?=template_footer()?>
