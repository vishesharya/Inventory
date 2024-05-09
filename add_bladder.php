<?php
session_start();
include './include/connection.php';
include_once './include/admin-main.php';
?>
<?php
$addProductMsg = '';

// Add Product Form Handling
if (isset($_POST['add_bladder'])) {
    $bladder_name = $_POST['bladder_name'];

    // Check if the bladder already exists
    $checkQuery = mysqli_query($con, "SELECT * FROM bladder WHERE bladder_name = '$bladder_name'");
    $rowCount = mysqli_num_rows($checkQuery);

    if ($rowCount > 0) {
        $addProductMsg = "<p id='addProductMsg' style='color: red;font-size: medium;text-align: center;'>Bladder already exists</p>";
    } else {
        // Insert into bladder table
        $insertBladderProductQuery = "INSERT INTO bladder (bladder_name) VALUES ('$bladder_name')";
        if (mysqli_query($con, $insertBladderProductQuery)) {
            $addProductMsg = "<p id='addProductMsg' style='color: green;font-size: medium;text-align: center;'>Bladder added successfully</p>";
        } else {
            $addProductMsg = "<p id='addProductMsg' style='color: red;font-size: medium;text-align: center;'>Failed to add bladder</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bladder</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        setTimeout(function () {
            document.getElementById('addProductMsg').style.display = 'none';
        }, 1500); // 1.5 seconds
    </script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Add Bladder</h2>
                    </div>
                    <div class="card-body">
                        <?php echo $addProductMsg; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="bladder_name">Bladder Name</label>
                                <input type="text" name="bladder_name" id="bladder_name" class="form-control" required>
                            </div>
                           
                            <button type="submit" class="btn btn-primary" name="add_bladder">Add Bladder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
