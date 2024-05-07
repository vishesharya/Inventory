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
    </select><br><br>
    <label for="enter_panel_color">Enter Panel Color:</label>
    <input type="text" name="enter_panel_color" id="enter_panel_color"><br><br>
    <input type="submit" name="add_color" value="Add Color">
</form>
