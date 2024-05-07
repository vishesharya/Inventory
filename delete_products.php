<?php
session_start();
include_once 'include/connection.php';

$addProductMsg = '';
$deleteProductMsg = '';

$product_query = "SELECT DISTINCT product_name FROM sheets_product ORDER BY product_name ASC";
$product_result = mysqli_query($con, $product_query);

// Logic to fetch product bases and colors based on selected product
$selected_product = isset($_POST['product_name']) ? $_POST['product_name'] : null;
if ($selected_product) {
    $product_base_query = "SELECT DISTINCT product_base FROM sheets_product WHERE product_name = '$selected_product' ORDER BY product_base ASC";
    $product_color_query = "SELECT DISTINCT product_color FROM sheets_product WHERE product_name = '$selected_product' ORDER BY product_color ASC";
    $product_base_result = mysqli_query($con, $product_base_query);
    $product_color_result = mysqli_query($con, $product_color_query);
}

// Logic to fetch product bases and colors based on selected product
$selected_product = isset($_POST['product_name']) ? $_POST['product_name'] : null;
if ($selected_product) {
    $product_small_query = "SELECT DISTINCT small_sheet_color FROM sheets_small_stock WHERE product_name = '$selected_product'";
    $product_small_result = mysqli_query($con, $product_small_query);
 
}

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
                        <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name">Select Product:</label>
                                        <select class="form-select" id="product_name" name="product_name" onchange="this.form.submit()">
                                            <option value="" selected disabled>Select Product</option>
                                            <?php while ($row = mysqli_fetch_assoc($product_result)) : ?>
                                                <option value="<?php echo $row['product_name']; ?>" <?php echo $selected_product == $row['product_name'] ? 'selected' : ''; ?>><?php echo $row['product_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_base">Product Base:</label>
                                        <select class="form-select" id="product_base" name="product_base">
                                            <option value="" selected disabled>Select Product Base</option>
                                            <?php if ($selected_product) : ?>
                                                <?php while ($row = mysqli_fetch_assoc($product_base_result)) : ?>
                                                    <option value="<?php echo $row['product_base']; ?>"><?php echo $row['product_base']; ?></option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_color">Product Color:</label>
                                        <select class="form-select" id="product_color" name="product_color">
                                            <option value="" selected disabled>Select Product Color</option>
                                            <?php if ($selected_product) : ?>
                                                <?php while ($row = mysqli_fetch_assoc($product_color_result)) : ?>
                                                    <option value="<?php echo $row['product_color']; ?>"><?php echo $row['product_color']; ?></option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
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
