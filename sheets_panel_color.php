<?php
session_start();
include_once 'include/connection.php';

$addProductMsg = '';



// Delete Product Form Handling
if (isset($_POST['delete_product'])) {
    $product_name = $_POST['product_name'];
    $product_base = $_POST['small_sheet_color'];
  

    // Delete from products table
    $insertPanelColorQuery = "INSERT INTO stitcher (product_name, small_sheet_color) VALUES ('$stitcher_name', '$small_sheet_color')";
    mysqli_query($con, $deletePanelColorQuery);

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
                        <h2>Add Panel Color</h2>
                    </div>
                    <div class="card-body">
                        <?php echo $addProductMsg; ?>
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
                           
                            <button type="submit" class="btn btn-primary" name="delete_product">Add Color</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
