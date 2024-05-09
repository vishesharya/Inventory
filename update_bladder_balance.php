<?php
session_start();
include_once 'include/connection.php';

$updateQuantityMsg = '';

// Update Bladder Form Handling
if (isset($_POST['check_quantity'])) {
    $bladder_name = $_POST['bladder_name'];

    // Retrieve remaining bladder quantity from the bladder table
    $bladderQuery = mysqli_query($con, "SELECT bladder_remaining_quantity FROM bladder WHERE bladder_name = '$bladder_name'");
    $bladder_row = mysqli_fetch_assoc($bladderQuery);

    // Determine the remaining quantity for the selected bladder
    $bladder_remaining_quantity = isset($bladder_row['bladder_remaining_quantity']) ? $bladder_row['bladder_remaining_quantity'] : 0;

    // Display the remaining bladder balance
    $updateQuantityMsg = "<p style='color: green; font-size: medium; text-align: center;'>Remaining Bladder Balance for $bladder_name: $bladder_remaining_quantity</p>";

    // Add the form to update bladder balance
    $updateQuantityMsg .= "<form action='' method='post'>";
    $updateQuantityMsg .= "<input type='hidden' name='bladder_name' value='$bladder_name'>";
    $updateQuantityMsg .= "<div class='form-group'>";
    $updateQuantityMsg .= "<label for='new_quantity'>Update Bladder Balance</label>";
    $updateQuantityMsg .= "<input type='number' name='new_quantity' id='new_quantity' class='form-control' required>";
    $updateQuantityMsg .= "</div>";
    $updateQuantityMsg .= "<button type='submit' class='btn btn-primary' name='update_bladder_quantity'>Update Balance</button>";
    $updateQuantityMsg .= "</form>";
}

// Update Bladder Handling
if (isset($_POST['update_bladder_quantity'])) {
    $new_quantity = $_POST['new_quantity'];
    $bladder_name = $_POST['bladder_name'];

    // Update bladder_remaining_quantity in bladder table
    $updateBladderQuery = "UPDATE bladder SET bladder_remaining_quantity = '$new_quantity' WHERE bladder_name = '$bladder_name'";
    mysqli_query($con, $updateBladderQuery);

    $updateQuantityMsg = "<p style='color: green; font-size: medium; text-align: center;'>Bladder balance updated successfully</p>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bladder Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Check & Update Bladder Balance</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="bladder_name">Select Bladder</label>
                                <select name="bladder_name" id="bladder_name" class="form-control" required>
                                    <option value="">Select Bladder</option>
                                    <?php
                                    // Fetch bladder names from the database
                                    $bladderQuery = mysqli_query($con, "SELECT bladder_name FROM bladder ORDER BY bladder_name ASC");
                                    while ($row = mysqli_fetch_assoc($bladderQuery)) {
                                        echo "<option value='" . $row['bladder_name'] . "'>" . $row['bladder_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" name="check_quantity">Check Balance</button>
                        </form>
                        <div class="mt-4">
                            <?php echo $updateQuantityMsg; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
