<?php
session_start();
include_once 'include/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_color'])) {
    $select_product = $_POST['select_product'];
    $enter_panel_color = strtoupper($_POST['enter_panel_color']);

    // Check if product already exists
    $query = "SELECT * FROM sheets_small_stock WHERE product_name = '$select_product' AND small_sheet_color = '$enter_panel_color'";
    $result = mysqli_query($connection, $query);
    if (mysqli_num_rows($result) > 0) {
        echo "Product already exists.";
    } else {
        // Insert new record
        $insert_query = "INSERT INTO sheets_small_stock (product_name, small_sheet_color) VALUES ('$select_product', '$enter_panel_color')";
        if (mysqli_query($connection, $insert_query)) {
            echo "Color added successfully.";
        } else {
            echo "Error: " . $insert_query . "<br>" . mysqli_error($connection);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Small Panel Color</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 20px;
    }
    label {
        font-weight: bold;
    }
    select, input[type="text"], input[type="submit"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    .message {
        text-align: center;
        margin-top: 10px;
        color: #4CAF50;
    }
    .error {
        text-align: center;
        margin-top: 10px;
        color: #f44336;
    }
</style>
</head>
<body>
<div class="container">
    <h2>Add Small Panel Color</h2>
    <?php
    session_start();
    include_once 'include/connection.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_color'])) {
        $select_product = $_POST['select_product'];
        $enter_panel_color = strtoupper($_POST['enter_panel_color']);

        // Check if product already exists
        $query = "SELECT * FROM sheets_small_stock WHERE product_name = '$select_product' AND small_sheet_color = '$enter_panel_color'";
        $result = mysqli_query($connection, $query);
        if (mysqli_num_rows($result) > 0) {
            echo "<p class='error'>Product already exists.</p>";
        } else {
            // Insert new record
            $insert_query = "INSERT INTO sheets_small_stock (product_name, small_sheet_color) VALUES ('$select_product', '$enter_panel_color')";
            if (mysqli_query($connection, $insert_query)) {
                echo "<p class='message'>Color added successfully.</p>";
            } else {
                echo "<p class='error'>Error: " . $insert_query . "<br>" . mysqli_error($connection) . "</p>";
            }
        }
    }
    ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="select_product">Select Product:</label>
        <select name="select_product" id="select_product">
            <?php
            $product_query = "SELECT DISTINCT product_name FROM sheets_product";
            $product_result = mysqli_query($connection, $product_query);
            while ($row = mysqli_fetch_assoc($product_result)) {
                echo "<option value='" . $row['product_name'] . "'>" . $row['product_name'] . "</option>";
            }
            ?>
        </select>
        <label for="enter_panel_color">Enter Panel Color:</label>
        <input type="text" name="enter_panel_color" id="enter_panel_color">
        <input type="submit" name="add_color" value="Add Color">
    </form>
</div>
</body>
</html>
