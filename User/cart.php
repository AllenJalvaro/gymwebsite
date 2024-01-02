<?php
// If the user clicked the add to cart button on the product page we can check for the form data
if (isset($_POST['product_id'], $_POST['quantity']) && is_numeric($_POST['product_id']) && is_numeric($_POST['quantity'])) {
    // Set the post variables so we easily identify them, also make sure they are integers
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    // Prepare the SQL statement, we basically are checking if the product exists in our database
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$product_id]);
    // Fetch the product from the database and return the result as an Array
    $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if the product exists (array is not empty)
    if ($product && $quantity > 0) {
        // Product exists in the database, now we can create/update the session variable for the cart
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            if (array_key_exists($product_id, $_SESSION['cart'])) {
                // Product exists in the cart so just update the quantity
                $_SESSION['cart'][$product_id] += $quantity;
            } else {
                // Product is not in the cart, so add it
                $_SESSION['cart'][$product_id] = $quantity;
            }
        } else {
            // There are no products in the cart; this will add the first product to the cart
            $_SESSION['cart'] = array($product_id => $quantity);
        }
    }
    // Prevent form resubmission...
    header('location: indexshop.php?page=cart');
    exit;
}

// Remove product from cart, check for the URL param "remove", this is the product id, make sure it's a number and check if it's in the cart
if (isset($_GET['remove']) && is_numeric($_GET['remove']) && isset($_SESSION['cart']) && isset($_SESSION['cart'][$_GET['remove']])) {
    // SweetAlert confirmation
    echo '<script>
            if (confirm("Are you sure you want to remove this item from the cart?")) {
                window.location.href = "remove_cart.php?remove=' . $_GET['remove'] . '";
            } else {
                window.location.href = "indexshop.php?page=cart";
            }
          </script>';
}

// Send the user to the place order page if they click the Place Order button, also, the cart should not be empty
if (isset($_POST['placeorder']) && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    header('Location: indexshop.php?page=placeorder');
    exit;
}

// Check the session variable for products in the cart
$products_in_cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$products = array();
$subtotal = 0.00;

// If there are products in the cart
if ($products_in_cart) {
    // There are products in the cart so we need to select those products from the database
    // Products in cart array to question mark string array, we need the SQL statement to include IN (?,?,?,...etc)
    $array_to_question_marks = implode(',', array_fill(0, count($products_in_cart), '?'));
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id IN (' . $array_to_question_marks . ') ORDER BY date_added DESC');
    // We only need the array keys, not the values; the keys are the id's of the products
    $stmt->execute(array_keys($products_in_cart));
    // Fetch the products from the database and return the result as an Array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Calculate the subtotal
    foreach ($products as $product) {
        $subtotal += (float)$product['price'] * (int)$products_in_cart[$product['id']];
    }
}
?>

<?=template_header('Cart')?>

<div class="cart content-wrapper">
    <h1>Shopping Cart</h1>
    <form id="cartForm">
    <table>
        <thead>
            <tr>
                <td colspan="2">Product</td>
                <td>Price</td>
                <td>Quantity</td>
                <td>Total</td>
                <td>Select</td>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="6" style="text-align:center;">You have no products added in your Shopping Cart</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr data-id="<?=$product['id']?>">
                        <td class="img">
                            <a href="indexshop.php?page=product&id=<?=$product['id']?>">
                                <img src="imgs/<?=$product['img']?>" width="50" height="50" alt="<?=$product['name']?>">
                            </a>
                        </td>
                        <td>
                            <a href="indexshop.php?page=product&id=<?=$product['id']?>"><?=$product['name']?></a>
                            <br>
                            <a href="javascript:void(0);" class="remove" data-id="<?=$product['id']?>">Remove</a>
                        </td>
                        <td class="price">&#8369;<?= number_format($product['price'], 2) ?></td>
                        <td class="quantity">
                            <input type="number" class="quantity-input" name="quantity-<?=$product['id']?>" value="<?=$products_in_cart[$product['id']]?>" min="1" max="<?=$product['quantity']?>" placeholder="Quantity" required>
                        </td>
                        <td class="price total">&#8369;<?= number_format($product['price'] * $products_in_cart[$product['id']], 2) ?></td>
                        <td class="checkbox">
                            <input type="checkbox" class="item-checkbox" name="cart_item[]" value="<?=$product['id']?>">
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="subtotal">
        <span class="text">Subtotal</span>
        <span class="price" id="subtotalAmount">&#8369;<?= number_format($subtotal, 2) ?></span>
    </div>
    <div class="buttons">
        <input type="submit" value="Checkout" name="placeorder">
    </div>
</form>
</div>

<!-- Import SweetAlert CDN -->
<!-- Import SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle quantity change event
        $('.quantity-input').on('input', function() {
            updateCartItem($(this));
        });

        // Handle remove link click event
        $('.remove').on('click', function() {
            var productId = $(this).data('id');
            confirmRemove(productId);
        });

        // Handle checkbox change event
        $('.item-checkbox').on('change', function() {
            updateSubtotal();
        });

        // Function to update cart item
        function updateCartItem(input) {
            var productId = input.attr('name').replace('quantity-', '');
            var quantity = input.val();
            $.ajax({
                type: 'POST',
                url: 'update_cart.php',
                data: {product_id: productId, quantity: quantity},
                success: function(response) {
                    var responseData = JSON.parse(response);
                    input.closest('tr').find('.total').text('₱' + responseData.total);
                    $('.subtotal .price').text('₱' + responseData.subtotal);
                    updateSubtotal();
                }
            });
        }

        // Function to confirm item removal
        function confirmRemove(productId) {
            Swal.fire({
                title: 'Remove Item',
                text: 'Are you sure you want to remove this item from the cart?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    removeCartItem(productId);
                }
            });
        }

        // Function to remove cart item
        function removeCartItem(productId) {
            $.ajax({
                type: 'GET',
                url: 'remove_cart.php',
                data: {remove: productId},
                success: function(response) {
                    var responseData = JSON.parse(response);
                    $('.subtotal .price').text('₱' + responseData.subtotal);

                    // Remove the corresponding row from the table
                    $('tr[data-id="' + productId + '"]').fadeOut(300, function() {
                        $(this).remove();
                        updateSubtotal();
                    });
                }
            });
        }

        // Function to update subtotal based on checked items
        function updateSubtotal() {
            var checkedItems = $('input.item-checkbox:checked');
            var subtotal = 0.00;

            checkedItems.each(function() {
                var productId = $(this).val();
                var quantity = $('.quantity-input[name="quantity-' + productId + '"]').val();
                var productPrice = <?= json_encode($products); ?>.find(p => p.id == productId).price;
                subtotal += parseFloat(productPrice) * parseInt(quantity);
            });

            $('#subtotalAmount').text('₱' + subtotal.toFixed(2));
        }
    });
</script>
