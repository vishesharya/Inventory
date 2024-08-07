<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');
$addInkMsg = '';

// Add Ink Form Handling
if (isset($_POST['add_ink'])) {
    $ink_name = $_POST['ink_name']; 
    $quantity = $_POST['quantity'];

    // Check if the ink already exists
    $checkQuery = mysqli_query($con, "SELECT * FROM ink WHERE ink_name = '$ink_name'");
    $rowCount = mysqli_num_rows($checkQuery);

    if ($rowCount > 0) {
        // If ink already exists, update the quantity
        $updateInkQuery = "UPDATE ink SET ink_remaining_quantity = ink_remaining_quantity + $quantity WHERE ink_name = '$ink_name'";
        if (mysqli_query($con, $updateInkQuery)) {
            $addInkMsg = "<p id='addInkMsg' style='color: green;font-size: medium;text-align: center;'>Ink quantity updated successfully</p>";
        } else {
            $addInkMsg = "<p id='addInkMsg' style='color: red;font-size: medium;text-align: center;'>Failed to update ink quantity</p>";
        }
    } else {
        // If ink doesn't exist, insert a new record
        $insertInkQuery = "INSERT INTO ink (ink_name, ink_remaining_quantity) VALUES ('$ink_name', $quantity)";
        if (mysqli_query($con, $insertInkQuery)) {
            $addInkMsg = "<p id='addInkMsg' style='color: green;font-size: medium;text-align: center;'>Ink added successfully</p>";
        } else {
            $addInkMsg = "<p id='addInkMsg' style='color: red;font-size: medium;text-align: center;'>Failed to add ink</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Ink</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        setTimeout(function () {
            document.getElementById('addInkMsg').style.display = 'none';
        }, 1500); // 1.5 seconds
    </script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Add Ink</h2>
                    </div>
                    <div class="card-body">
                        <?php echo $addInkMsg; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="ink_name">Ink Name</label>
                                <input type="text" name="ink_name" id="ink_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="quantity">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="add_ink">Add Ink</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
