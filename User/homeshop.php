<?php
// Get the 4 most recently added products
$stmt = $pdo->prepare('SELECT * FROM products ORDER BY date_added DESC LIMIT 4');
$stmt->execute();
$recently_added_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get featured products from the database
$stmt_featured = $pdo->prepare('SELECT p.* FROM products p JOIN featured_products fp ON p.id = fp.productID ORDER BY fp.featuredID DESC');
$stmt_featured->execute();
$featured_products = $stmt_featured->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header('Home')?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-NJGVhBwi3aMkx2qFPc6z0/uipKS7fIamQcc9Uxg5Bz4CTzMQpzrmYw5QB4Hy+OHnm4LkufxEeRGGKcZstVb5cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div id="featuredCarousel" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <?php foreach ($featured_products as $key => $product): ?>
            <li data-target="#featuredCarousel" data-slide-to="<?= $key ?>" class="<?= $key === 0 ? 'active' : '' ?>"></li>
        <?php endforeach; ?>
    </ol>
    <div class="carousel-inner">
        <?php foreach ($featured_products as $key => $product): ?>
            <div class="carousel-item <?= $key === 0 ? 'active' : '' ?> slide">
                <a href="indexshop.php?page=product&id=<?= $product['id'] ?>">
                    <img class="d-block mx-auto my-3 img-fluid" src="imgs/<?= $product['img'] ?>" alt="<?= $product['name'] ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h5 class="featured-title"><?= $product['name'] ?></h5>
                        <p class="featured-price">&#8369;<?= number_format($product['price'], 2) ?></p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <br>
    <a class="carousel-control-prev" href="#featuredCarousel" role="button" data-slide="prev" style="color: #404040;">
        <i class="fas fa-chevron-left"></i>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#featuredCarousel" role="button" data-slide="next" style="color: #404040;">
        <i class="fas fa-chevron-right"></i>
        <span class="sr-only">Next</span>
    </a>
</div>
<div class="recentlyadded content-wrapper">
    <h2>Recently Added Products</h2>
    <br><br>
    <div class="products-wrapper">
        <?php foreach ($recently_added_products as $product): ?>
            <a href="indexshop.php?page=product&id=<?=$product['id']?>" class="product">
                <img src="imgs/<?=$product['img']?>" alt="<?=$product['name']?>">
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

<!-- Include Bootstrap JS and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Add this script for automatic sliding every 5 seconds and animations -->
<script>
    $(document).ready(function(){
        $('#featuredCarousel').carousel({
            interval: 5000,  // Set the interval to 5 seconds (5000 milliseconds)
            pause: false,    // Don't pause on hover
            wrap: true       // Wrap around when reaching the end
        });
    });
</script>

<style>
    .carousel-inner .carousel-item img {
        width: 100%; /* Make images responsive */
        max-height: 500px; /* Set the maximum height for the images */
        object-fit: contain; /* Ensure images do not stretch but fit within the container */
    }

    .carousel-indicators {
        justify-content: center; /* Center align indicators */
        margin-top: 10px; /* Add some margin for spacing */
    }

    .carousel-indicators li {
        background-color: #777; /* Set default color for indicators */
        border: 1px solid #555; /* Add border for indicators */
        width: 10px; /* Set the width of each indicator */
        height: 10px; /* Set the height of each indicator */
        margin: 0 4px; /* Add margin between indicators */
        border-radius: 50%; /* Create circular indicators */
        cursor: pointer; /* Change cursor on hover */
    }

    .carousel-indicators .active {
        background-color: #333; /* Set color for active indicator */
    }

    .carousel-caption {
        background: rgba(0, 0, 0, 0.3); /* Semi-transparent background */
        padding: 10px;
        border-end-end-radius: 20px;
        border-end-start-radius: 20px;
    }

    .featured-title {
        font-size: 1.5em; /* Increase title font size */
        font-weight: bold;
        color: #fff; /* Set title color to white */
        margin-bottom: 5px;
    }

    .featured-price {
        font-size: 1.2em; /* Increase price font size */
        color: #fff; /* Set price color to white */
        margin: 0;
        font-weight: 700;
    }

    .products-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 80px; /* Adjust the spacing between products */
        justify-content: center;
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
        color: #9e0e0e;
        font-weight: 700;
    }

    .rrp {
        text-decoration: line-through;
        color: #999;
    }
</style>

<?=template_footer()?>
