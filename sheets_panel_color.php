<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');

$addColorMsg = '';
$deleteColorMsg = '';

if (isset($_POST['add_color'])) {
    $small_sheet_color = isset($_POST['small_sheet_color']) ? $_POST['small_sheet_color'] : "";

    // Check if the color already exists in sheets_small_stock
    $checkColorQuery = "SELECT * FROM sheets_small_stock WHERE small_sheet_color = '$small_sheet_color'";
    $result = mysqli_query($con, $checkColorQuery);

    if (mysqli_num_rows($result) > 0) {
        // Color already exists
        $addColorMsg = "Color already added!";
    } else {
        // Insert into sheets_small_stock table
        $insertPanelColorQuery = "INSERT INTO sheets_small_stock ( small_sheet_color) VALUES ('$small_sheet_color')";
        if(mysqli_query($con, $insertPanelColorQuery)) {
            // Insert into sheets_production_small_stock table
            $insertPanelColorQueryProduction = "INSERT INTO sheets_production_small_stock (small_sheet_color) VALUES ('$small_sheet_color')";
            if(mysqli_query($con, $insertPanelColorQueryProduction)) {
                // Color added successfully
                $addColorMsg = "Color added successfully.";
            } else {
                // Error adding color in sheets_production_small_stock
                $addColorMsg = "Error: Unable to add color in production stock.";
            }
        } else {
            // Error adding color
            $addColorMsg = "Error: Unable to add color.";
        }
    }
    // Display the message for 3 seconds
    echo "<script>setTimeout(function(){ document.getElementById('add_message').style.display = 'none'; }, 3000);</script>";
}

if (isset($_POST['delete_color'])) {
 
    $small_sheet_color = $_POST['small_sheet_color_delete'];

    // Delete color from sheets_small_stock table
    $deleteColorQuery = "DELETE FROM sheets_small_stock WHERE small_sheet_color = '$small_sheet_color'";
    if(mysqli_query($con, $deleteColorQuery)) {
        // Delete color from sheets_production_small_stock table
        $deleteColorQueryProduction = "DELETE FROM sheets_production_small_stock WHERE small_sheet_color = '$small_sheet_color'";
        if(mysqli_query($con, $deleteColorQueryProduction)) {
            // Color deleted successfully
            $deleteColorMsg = "Color deleted successfully.";
        } else {
            // Error deleting color in sheets_production_small_stock
            $deleteColorMsg = "Error: Unable to delete color from production stock.";
        }
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
    <title>Color Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
<?php include('include/sheets_nav.php'); ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Add Color</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($addColorMsg)) : ?>
                            <div id="add_message" class="alert alert-info" role="alert">
                                <?php echo $addColorMsg; ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="post">
                            <!-- Add Color Form -->
                            <div class="form-group">
                                <label for="small_sheet_color">Enter Color</label>
                                <input type="text" name="small_sheet_color" id="small_sheet_color" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="add_color">Add Color</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Delete Color</h2>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($deleteColorMsg)) : ?>
                            <div id="delete_message" class="alert alert-info" role="alert">
                                <?php echo $deleteColorMsg; ?>
                            </div>
                        <?php endif; ?>
                        <form action="" method="post">
                            <!-- Delete Color Form -->
                            <div class="form-group">
                                <label for="small_sheet_color_delete">Select Color</label>
                                <select name="small_sheet_color_delete" id="small_sheet_color_delete" class="form-control" required>
                                    <option value="">Select Color</option>
                                    <?php
                                    // Fetch colors from database and populate dropdown
                                    $colorQuery = "SELECT small_sheet_color FROM sheets_small_stock";
                                    $colorResult = mysqli_query($con, $colorQuery);
                                    while ($row = mysqli_fetch_assoc($colorResult)) {
                                        echo "<option value='" . $row['small_sheet_color'] . "'>" . $row['small_sheet_color'] . "</option>";
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
