<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');

$addBladdertMsg = '';
$deleteBladderMsg = '';


// Delete Product Form Handling
if (isset($_POST['delete_bladder'])) {
    $bladder_name = $_POST['bladder_name'];
  

    // Delete from kits_product table
    $deleteBladderQuery = "DELETE FROM bladder WHERE bladder_name = '$bladder_name'";
    mysqli_query($con, $deleteBladderQuery);

    // Check if deletion was successful
    if (mysqli_affected_rows($con) > 0) {
        $deleteBladderMsg = "<p style='color: green;font-size: medium;text-align: center;'>Bladder deleted successfully</p>";
    } else {
        $deleteBladderMsg = "<p style='color: red;font-size: medium;text-align: center;'>Bladder to delete product</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Bladder</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        // Function to hide the message after 1500 milliseconds (1.5 seconds)
        setTimeout(function () {
            document.getElementById("deleteBladderMsg").style.display = "none";
        }, 1500);
    </script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Delete Bladder</h2>
                    </div>
                    <div class="card-body">
                    
                        <!-- Set an id for the message div -->
                        <div id="deleteBladderMsg">
                            <?php echo $deleteBladderMsg; ?>
                        </div>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="bladder_name_delete">Select Bladder</label>
                                <select name="bladder_name" id="bladder_name_delete" class="form-control" required>
                                    <option value="">Select Bladder</option>
                                    <?php
                                    // Fetch product names alphabetically from the database
                                    $bladderQuery = mysqli_query($con, "SELECT DISTINCT bladder_name FROM bladder ORDER BY bladder_name ASC");
                                    while ($row = mysqli_fetch_assoc($bladderQuery)) {
                                        echo "<option value='" . $row['bladder_name'] . "'>" . $row['bladder_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger" name="delete_bladder">Delete Bladder</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
