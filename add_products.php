<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

$addProductMsg = '';
$deleteProductMsg = '';

// Add Product Form Handling
if (isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_base = $_POST['product_base'];
    $product_color = $_POST['product_color'];

    // Check if the product already exists
    $checkQuery = mysqli_query($con, "SELECT * FROM kits_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'");
    $rowCount = mysqli_num_rows($checkQuery);
    $checkQuery = mysqli_query($con, "SELECT * FROM products WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'");
    $rowCount = mysqli_num_rows($checkQuery);
    $checkQuery = mysqli_query($con, "SELECT * FROM sheets_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'");
    $rowCount = mysqli_num_rows($checkQuery);

    if ($rowCount > 0) {
        $addProductMsg = "<p style='color: red;font-size: medium;text-align: center;'>Product already exists</p>";
    } else {
        // Insert into products table
        $insertKitsProductQuery = "INSERT INTO products (product_name, product_base, product_color) VALUES ('$product_name', '$product_base', '$product_color')";
        mysqli_query($con, $insertKitsProductQuery);

        // Insert into kits_product table
        $insertKitsProductQuery = "INSERT INTO kits_product (product_name, product_base, product_color) VALUES ('$product_name', '$product_base', '$product_color')";
        mysqli_query($con, $insertKitsProductQuery);
        
        // Insert into kits_product table
        $insertSheetsProductQuery = "INSERT INTO sheets_product (product_name, product_base, product_color) VALUES ('$product_name', '$product_base', '$product_color')";
        mysqli_query($con, $insertSheetsProductQuery);

        // Check if insertion was successful
        if (mysqli_affected_rows($con) > 0) {
            $addProductMsg = "<p style='color: green;font-size: medium;text-align: center;'>Product added successfully</p>";
        } else {
            $addProductMsg = "<p style='color: red;font-size: medium;text-align: center;'>Failed to add product</p>";
        }
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
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Add Product</h2>
                    </div>
                    <div class="card-body">
                        <?php echo $addProductMsg; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="product_name">Product Name</label>
                                <input type="text" name="product_name" id="product_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="product_base">Product Base Color</label>
                                <input type="text" name="product_base" id="product_base" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="product_color">Product Color</label>
                                <input type="text" name="product_color" id="product_color" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
