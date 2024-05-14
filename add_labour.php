<?php
session_start();
include './include/connection.php';
include_once 'include/admin-main.php';

$updatelabourMsg = '';
$addlabourMsg = '';
$deletelabourMsg = '';

// Add labour Form Handling
if (isset($_POST['add_labour'])) {
    $labour_name = $_POST['labour_name'];
   
    // Check if the labour already exists
    $checkQuery = mysqli_query($con, "SELECT * FROM labour WHERE labour_name = '$labour_name'");
    $rowCount = mysqli_num_rows($checkQuery);

    if ($rowCount > 0) {
        $addlabourMsg = "<p id='addlabourMsg' style='color: red;font-size: medium;text-align: center;'>Labour already exists</p>";
    } else {
        // Insert into labour table
        $insertlabourQuery = "INSERT INTO labour (labour_name) VALUES ('$labour_name')";
        if (mysqli_query($con, $insertlabourQuery)) {
            $addlabourMsg = "<p id='addlabourMsg' style='color: green;font-size: medium;text-align: center;'>labour added successfully</p>";
        } else {
            $addlabourMsg = "<p id='addlabourMsg' style='color: red;font-size: medium;text-align: center;'>Failed to add labour</p>";
        }
    }
}

// Edit labour Form Handling
if (isset($_POST['edit_labour'])) {
    $labour_id = $_POST['labour_id'];
    $new_labour_name = $_POST['new_labour_name'];
  
    // Check if the new labour name already exists (excluding the current labour)
    $checkQuery = mysqli_query($con, "SELECT * FROM labour WHERE labour_name = '$new_labour_name' AND id != $labour_id");
    $rowCount = mysqli_num_rows($checkQuery);

    if ($rowCount > 0) {
        $updatelabourMsg = "<p id='updatelabourMsg' style='color: red;font-size: medium;text-align: center;'>labour name already exists</p>";
    } else {
        // Update labour name and contact
        $updatelabourQuery = "UPDATE labour SET labour_name='$new_labour_name' WHERE id=$labour_id";
        if (mysqli_query($con, $updatelabourQuery)) {
            $updatelabourMsg = "<p id='updatelabourMsg' style='color: green;font-size: medium;text-align: center;'>labour updated successfully</p>";
        } else {
            $updatelabourMsg = "<p id='updatelabourMsg' style='color: red;font-size: medium;text-align: center;'>Failed to update labour</p>";
        }
    }
}

// Delete labour Handling
if (isset($_POST['delete_labour'])) {
    $delete_labour_id = $_POST['delete_labour_id'];

    // Check if delete_labour_id is not empty
    if (!empty($delete_labour_id)) {
        // Delete labour
        $deletelabourQuery = "DELETE FROM labour WHERE id=$delete_labour_id";
        if (mysqli_query($con, $deletelabourQuery)) {
            $deletelabourMsg = "<p id='deletelabourMsg' style='color: green;font-size: medium;text-align: center;'>labour deleted successfully</p>";
        } else {
            $deletelabourMsg = "<p id='deletelabourMsg' style='color: red;font-size: medium;text-align: center;'>Failed to delete labour</p>";
        }
    } else {
        $deletelabourMsg = "<p id='deletelabourMsg' style='color: red;font-size: medium;text-align: center;'>Please select a labour to delete</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit labour</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        setTimeout(function () {
            document.getElementById('addlabourMsg').style.display = 'none';
            document.getElementById('updatelabourMsg').style.display = 'none';
            document.getElementById('deletelabourMsg').style.display = 'none';
        }, 3000); // 3 seconds
    </script>
</head>

<body>
<?php include('include/sheets_nav.php'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Add labour</h2>
                    </div>
                    <div class="card-body">
                        <?php echo $addlabourMsg; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="labour_name">Enter labour Name</label>
                                <input type="text" name="labour_name" id="labour_name" class="form-control" required>
                            </div>
                           
                            <button type="submit" class="btn btn-primary" name="add_labour">Add labour</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Edit labour</h2>
                    </div>
                    <div class="card-body">
                        <?php echo $updatelabourMsg; ?>
                        <?php echo $deletelabourMsg; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="select_labour">Select labour Name</label>
                                <select name="labour_id" id="select_labour" class="form-control">
                                    <option value="">Select labour</option>
                                    <?php
                                    $selectlabourQuery = mysqli_query($con, "SELECT * FROM labour ORDER BY labour_name ASC");
                                    while ($row = mysqli_fetch_assoc($selectlabourQuery)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['labour_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="new_labour_name">New labour Name</label>
                                <input type="text" name="new_labour_name" id="new_labour_name" class="form-control" required>
                            </div>
                           
                            <button type="submit" class="btn btn-primary" name="edit_labour">Edit labour</button>
                        </form>
                        <form action="" method="post" class="mt-3">
                            <div class="form-group">
                                <label for="delete_labour">Delete labour</label>
                                <select name="delete_labour_id" id="delete_labour" class="form-control">
                                    <option value="">Select labour</option>
                                    <?php
                                    $selectlabourQuery = mysqli_query($con, "SELECT * FROM labour ORDER BY labour_name ASC");
                                    while ($row = mysqli_fetch_assoc($selectlabourQuery)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['labour_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger" name="delete_labour">Delete labour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
