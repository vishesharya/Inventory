<?php
session_start();
include_once 'include/connection.php';

$addProductMsg = '';
$deleteProductMsg = '';


// Delete Product Form Handling
if (isset($_POST['delete_product'])) {
    $product_name = $_POST['product_name'];
    $product_base = $_POST['product_base'];
    $product_color = $_POST['product_color'];

    // Delete from products table
    $deleteKitsProductQuery = "DELETE FROM products WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
    mysqli_query($con, $deleteKitsProductQuery);

    // Delete from kits_product table
    $deleteKitsProductQuery = "DELETE FROM kits_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
    mysqli_query($con, $deleteKitsProductQuery);

     // Delete from kits_product table
     $deleteSheetsProductQuery = "DELETE FROM sheets_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
     mysqli_query($con, $deleteSheetsProductQuery);

    // Check if deletion was successful
    if (mysqli_affected_rows($con) > 0) {
        $deleteProductMsg = "<p style='color: green;font-size: medium;text-align: center;'>Product deleted successfully</p>";
    } else {
        $deleteProductMsg = "<p style='color: red;font-size: medium;text-align: center;'>Failed to delete product</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
   

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Delete Product</h2>
                    </div>
                    <div class="card-body">
                        <?php echo $deleteProductMsg; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="product_name_delete">Select Product Name</label>
                                <select name="product_name" id="product_name_delete" class="form-control" required>
                                    <option value="">Select Product Name</option>
                                    <?php
                                    // Fetch product names alphabetically from the database
                                    $productQuery = mysqli_query($con, "SELECT DISTINCT product_name FROM sheets_product ORDER BY product_name ASC");
                                    while ($row = mysqli_fetch_assoc($productQuery)) {
                                        echo "<option value='" . $row['product_name'] . "'>" . $row['product_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product_base_delete">Select Product Base Color</label>
                                <select name="product_base" id="product_base_delete" class="form-control" required>
                                    <option value="">Select Product Base Color</option>
                                    <?php
                                    // Fetch product base colors alphabetically from the database
                                    $productBaseQuery = mysqli_query($con, "SELECT DISTINCT product_base FROM sheets_product ORDER BY product_base ASC");
                                    while ($row = mysqli_fetch_assoc($productBaseQuery)) {
                                        echo "<option value='" . $row['product_base'] . "'>" . $row['product_base'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="product_color_delete">Select Product Color</label>
                                <select name="product_color" id="product_color_delete" class="form-control" required>
                                    <option value="">Select Product Color</option>
                                    <?php
                                    // Fetch product colors alphabetically from the database
                                    $productColorQuery = mysqli_query($con, "SELECT DISTINCT product_color FROM sheets_product ORDER BY product_color ASC");
                                    while ($row = mysqli_fetch_assoc($productColorQuery)) {
                                        echo "<option value='" . $row['product_color'] . "'>" . $row['product_color'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger" name="delete_product">Delete Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    // Function to update product colors based on selected product name and base
    function updateProductColors() {
        var productName = document.getElementById('product_name').value;
        var productBase = document.getElementById('product_base').value;

        // Make an AJAX request to fetch product colors based on product name and base
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                var colors = JSON.parse(this.responseText);
                var productColorSelect = document.getElementById('product_color');
                // Clear existing options
                productColorSelect.innerHTML = '<option value="" selected disabled>Select Product Color</option>';
                // Add fetched colors as options
                colors.forEach(function(color) {
                    var option = document.createElement('option');
                    option.value = color;
                    option.text = color;
                    productColorSelect.appendChild(option);
                });
            }
        };
        xhr.open('GET', 'fetch_product_color.php?product_name=' + productName + '&product_base=' + productBase, true);
        xhr.send();
    }

    // Event listeners for product name and product base change
    document.getElementById('product_name').addEventListener('change', updateProductColors);
    document.getElementById('product_base').addEventListener('change', updateProductColors);
</script>
</html>
