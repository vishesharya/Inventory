<?php
session_start();
include_once 'include/connection.php';

$addColorMsg = '';
$deleteColorMsg = '';

if (isset($_POST['add_color'])) {
    $product_name = $_POST['product_name'];
    $small_sheet_color = isset($_POST['small_sheet_color']) ? $_POST['small_sheet_color'] : "";

    // Check if the color already exists
    $checkColorQuery = "SELECT * FROM sheets_small_stock WHERE product_name = '$product_name' AND small_sheet_color = '$small_sheet_color'";
    $result = mysqli_query($con, $checkColorQuery);

    if (mysqli_num_rows($result) > 0) {
        // Color already exists
        $addColorMsg = "Color already added!";
    } else {
        // Insert into sheets_small_stock table
        $insertPanelColorQuery = "INSERT INTO sheets_small_stock (product_name, small_sheet_color) VALUES ('$product_name', '$small_sheet_color')";
        if(mysqli_query($con, $insertPanelColorQuery)) {
            // Color added successfully
            $addColorMsg = "Color added successfully.";
        } else {
            // Error adding color
            $addColorMsg = "Error: Unable to add color.";
        }
    }
    // Display the message for 3 seconds
    echo "<script>setTimeout(function(){ document.getElementById('add_message').style.display = 'none'; }, 3000);</script>";
}

if (isset($_POST['delete_color'])) {
    $product_name = $_POST['product_name_delete'];
    $small_sheet_color = $_POST['small_sheet_color_delete'];

    // Delete color from sheets_small_stock table
    $deleteColorQuery = "DELETE FROM sheets_small_stock WHERE product_name = '$product_name' AND small_sheet_color = '$small_sheet_color'";
    if(mysqli_query($con, $deleteColorQuery)) {
        // Color deleted successfully
        $deleteColorMsg = "Color deleted successfully.";
    } else {
        // Error deleting color
        $deleteColorMsg = "Error: Unable to delete color.";
    }
    // Display the message for 3 seconds
    echo "<script>setTimeout(function(){ document.getElementById('delete_message').style.display = 'none'; }, 3000);</script>";
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
                        <?php if (!empty($addColorMsg)) : ?>
                            <div id="add_message" class="alert alert-info" role="alert">
                                <?php echo $addColorMsg; ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="product_name">Select Product Name</label>
                                <select name="product_name" id="product_name" class="form-control" required>
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
                                <label for="small_sheet_color">Enter Panel Color</label>
                                <input type="text" name="small_sheet_color" id="small_sheet_color" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="add_color">Add Color</button>
                        </form>
                        <?php if (!empty($deleteColorMsg)) : ?>
                            <div id="delete_message" class="alert alert-info mt-3" role="alert">
                                <?php echo $deleteColorMsg; ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="post">
                            <div class="form-group mt-3">
                                <label for="product_name_delete">Select Product Name</label>
                                <select name="product_name_delete" id="product_name_delete" class="form-control" required>
                                    <option value="">Select Product Name</option>
                                    <?php
                                    // Fetch product names alphabetically from the database
                                    $productQuery = mysqli_query($con, "SELECT DISTINCT product_name FROM sheets_small_stock ORDER BY product_name ASC");
                                    while ($row = mysqli_fetch_assoc($productQuery)) {
                                        echo "<option value='" . $row['product_name'] . "'>" . $row['product_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group mt-3">
                                <label for="product_name_delete">Select Product Name</label>
                                <select name="product_name_delete" id="product_name_delete" class="form-control" required>
                                    <option value="">Select Product Name</option>
                                    <?php
                                    // Fetch product names alphabetically from the database
                                    $productQuery = mysqli_query($con, "SELECT DISTINCT small_sheet_color FROM sheets_small_stock ORDER BY small_sheet_color ASC");
                                    while ($row = mysqli_fetch_assoc($productQuery)) {
                                        echo "<option value='" . $row['product_name'] . "'>" . $row['product_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger" name="delete_color">Delete Color</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
