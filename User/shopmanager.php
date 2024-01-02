<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop Manager</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.css">

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Your custom CSS -->
    <style>
        /* Your custom styles go here */
    </style>
</head>

<body>











    <div class="container mt-5">
        <h2 class="mb-4">Product List</h2>

        <!-- Add Product Button -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>

        <!-- DataTable -->
        <table id="productTable" class="table">
            <!-- DataTable Header and Body remain unchanged -->
        </table>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add Product Form -->
                    <form id="addProductForm">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="productName" required>
                        </div>
                        <div class="mb-3">
                            <label for="productDesc" class="form-label">Product Description</label>
                            <textarea class="form-control" id="productDesc" name="productDesc" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="productPrice" name="productPrice" required>
                        </div>
                        <div class="mb-3">
                            <label for="productRRP" class="form-label">RRP</label>
                            <input type="number" class="form-control" id="productRRP" name="productRRP" required>
                        </div>
                        <div class="mb-3">
                            <label for="productQuantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="productQuantity" name="productQuantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="productImage" class="form-label">Image URL</label>
                            <input type="text" class="form-control" id="productImage" name="productImage">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- DataTables JavaScript -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.js"></script>

    <!-- Your custom JavaScript -->
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            var table = $('#productTable').DataTable();

            // Add Product Form Submission
            $('#addProductForm').submit(function (e) {
                e.preventDefault();

                // Collect form data
                var formData = {
                    productName: $('#productName').val(),
                    productDesc: $('#productDesc').val(),
                    productPrice: $('#productPrice').val(),
                    productRRP: $('#productRRP').val(),
                    productQuantity: $('#productQuantity').val(),
                    productImage: $('#productImage').val(),
                };

                // Perform AJAX request to add product
                $.ajax({
                    url: 'add_product.php', // Create a new PHP file for handling the addition logic
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                        // Refresh DataTable after successful addition
                        table.ajax.reload();
                        // Close the modal
                        $('#addProductModal').modal('hide');
                        // Clear form fields
                        $('#addProductForm')[0].reset();
                    },
                    error: function (error) {
                        console.log(error);
                        // Handle error if needed
                    }
                });
            });

            // DataTable initialization and other code remain unchanged
        });
    </script>















    <div class="container mt-5">

        <!-- DataTable -->
        <table id="productTable" class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>RRP</th>
                    <th>Quantity</th>
                    <th>Image</th>
                    <th>Date Added</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include('db_conn.php');

                $result = mysqli_query($conn, "SELECT * FROM products");

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['name']}</td>
                            <td>{$row['desc']}</td>
                            <td>{$row['price']}</td>
                            <td>{$row['rrp']}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['img']}</td>
                            <td>{$row['date_added']}</td>
                            <td>
                                <button class='btn btn-warning btn-sm edit-btn' data-bs-toggle='modal' data-bs-target='#editProductModal' data-product-id='{$row['id']}'>Edit</button>
                                <button class='btn btn-danger btn-sm delete-btn' data-product-id='{$row['id']}'>Delete</button>
                            </td>
                          </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <!-- Your add product modal content goes here -->
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <!-- Your edit product modal content goes here -->
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-ZvpUoO/+PpLXR1lu4jmpXWu80pZlYUAfxl5NsBMWOEPSjUn/6Z/hRTt8+pR6L4N2" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- DataTables JavaScript -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.6/js/jquery.dataTables.js"></script>

    <!-- Your custom JavaScript -->
    <script>
        $(document).ready(function () {
            $('#productTable').DataTable();

            // Add your logic for handling edit and delete buttons
            $('#productTable').on('click', '.edit-btn', function () {
                var productId = $(this).data('product-id');
                // Implement logic to fetch product details and populate the edit modal
            });

            $('#productTable').on('click', '.delete-btn', function () {
                var productId = $(this).data('product-id');
                // Implement logic to delete the product
            });
        });
    </script>

</body>

</html>
