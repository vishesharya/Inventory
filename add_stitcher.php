<?php
session_start();
include './include/connection.php';
include_once 'include/admin-main.php';

$updateStitcherMsg = '';
$addStitcherMsg = '';
$deleteStitcherMsg = '';

// Add Stitcher Form Handling
if (isset($_POST['add_stitcher'])) {
    $stitcher_name = $_POST['stitcher_name'];
    $stitcher_contact = $_POST['stitcher_contact'];
    $stitcher_address = $_POST['stitcher_address'];
    $stitcher_aadhar = $_POST['stitcher_aadhar'];
    $stitcher_pan = $_POST['stitcher_pan'];
    $bank_name = $_POST['bank_name'];
    $bank_no = $_POST['bank_no'];
    $ifsc_code = $_POST['ifsc_code'];

    // Check if the stitcher already exists
    $checkQuery = mysqli_query($con, "SELECT * FROM stitcher WHERE stitcher_name = '$stitcher_name'");
    $rowCount = mysqli_num_rows($checkQuery);

    if ($rowCount > 0) {
        $addStitcherMsg = "<p id='addStitcherMsg' style='color: red;font-size: medium;text-align: center;'>Stitcher already exists</p>";
    } else {
        // Insert into stitcher table
        $insertStitcherQuery = "INSERT INTO stitcher (stitcher_name, stitcher_contact, stitcher_address, stitcher_aadhar, stitcher_pan, bank_name, bank_no, ifsc_code) VALUES ('$stitcher_name', '$stitcher_contact', '$stitcher_address', '$stitcher_aadhar', '$stitcher_pan', '$bank_name', '$bank_no', '$ifsc_code')";
        if (mysqli_query($con, $insertStitcherQuery)) {
            $addStitcherMsg = "<p id='addStitcherMsg' style='color: green;font-size: medium;text-align: center;'>Stitcher added successfully</p>";
        } else {
            $addStitcherMsg = "<p id='addStitcherMsg' style='color: red;font-size: medium;text-align: center;'>Failed to add stitcher</p>";
        }
    }
}

// Edit Stitcher Form Handling
if (isset($_POST['edit_stitcher'])) {
    $stitcher_id = $_POST['stitcher_id'];
    $new_stitcher_name = $_POST['new_stitcher_name'];
    $new_stitcher_contact = $_POST['new_stitcher_contact'];
    $new_stitcher_address = $_POST['new_stitcher_address'];
    $new_stitcher_aadhar = $_POST['new_stitcher_aadhar'];
    $new_stitcher_pan = $_POST['new_stitcher_pan'];
    $new_bank_name = $_POST['new_bank_name'];
    $new_bank_no = $_POST['new_bank_no'];
    $new_ifsc_code = $_POST['new_ifsc_code'];

    // Fetch the existing details of the stitcher
    $stitcherDetailsQuery = mysqli_query($con, "SELECT * FROM stitcher WHERE id = $stitcher_id");
    $stitcherDetails = mysqli_fetch_assoc($stitcherDetailsQuery);

    // Construct the update query
    $updateStitcherQuery = "UPDATE stitcher SET ";

    // Array to store the fields to be updated
    $updateFields = array();

    // Check which fields are provided and add them to the updateFields array
    if (!empty($new_stitcher_name)) {
        $updateFields[] = "stitcher_name = '$new_stitcher_name'";
    }
    if (!empty($new_stitcher_contact)) {
        $updateFields[] = "stitcher_contact = '$new_stitcher_contact'";
    }
    if (!empty($new_stitcher_address)) {
        $updateFields[] = "stitcher_address = '$new_stitcher_address'";
    }
    if (!empty($new_stitcher_aadhar)) {
        $updateFields[] = "stitcher_aadhar = '$new_stitcher_aadhar'";
    }
    if (!empty($new_stitcher_pan)) {
        $updateFields[] = "stitcher_pan = '$new_stitcher_pan'";
    }
    if (!empty($new_bank_name)) {
        $updateFields[] = "bank_name = '$new_bank_name'";
    }
    if (!empty($new_bank_no)) {
        $updateFields[] = "bank_no = '$new_bank_no'";
    }
    if (!empty($new_ifsc_code)) {
        $updateFields[] = "ifsc_code = '$new_ifsc_code'";
    }

    // Append the fields to the update query
    $updateStitcherQuery .= implode(", ", $updateFields);

    // Add the WHERE clause
    $updateStitcherQuery .= " WHERE id = $stitcher_id";

    // Execute the update query
    if (mysqli_query($con, $updateStitcherQuery)) {
        $updateStitcherMsg = "<p id='updateStitcherMsg' style='color: green;font-size: medium;text-align: center;'>Stitcher updated successfully</p>";
    } else {
        $updateStitcherMsg = "<p id='updateStitcherMsg' style='color: red;font-size: medium;text-align: center;'>Failed to update stitcher</p>";
    }
}

// Delete Stitcher Handling
if (isset($_POST['delete_stitcher'])) {
    $delete_stitcher_id = $_POST['delete_stitcher_id'];

    // Check if delete_stitcher_id is not empty
    if (!empty($delete_stitcher_id)) {
        // Delete stitcher
        $deleteStitcherQuery = "DELETE FROM stitcher WHERE id=$delete_stitcher_id";
        if (mysqli_query($con, $deleteStitcherQuery)) {
            $deleteStitcherMsg = "<p id='deleteStitcherMsg' style='color: green;font-size: medium;text-align: center;'>Stitcher deleted successfully</p>";
        } else {
            $deleteStitcherMsg = "<p id='deleteStitcherMsg' style='color: red;font-size: medium;text-align: center;'>Failed to delete stitcher</p>";
        }
    } else {
        $deleteStitcherMsg = "<p id='deleteStitcherMsg' style='color: red;font-size: medium;text-align: center;'>Please select a stitcher to delete</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit Stitcher</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        setTimeout(function () {
            document.getElementById('addStitcherMsg').style.display = 'none';
            document.getElementById('updateStitcherMsg').style.display = 'none';
            document.getElementById('deleteStitcherMsg').style.display = 'none';
        }, 3000); // 3 seconds
    </script>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Add Stitcher</h2>
                    </div>
                    <div class="card-body">
                        <?php echo $addStitcherMsg; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="stitcher_name">Enter Stitcher Name</label>
                                <input type="text" name="stitcher_name" id="stitcher_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="stitcher_contact">Enter Contact Number</label>
                                <input type="number" name="stitcher_contact" id="stitcher_contact" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="stitcher_address">Enter Address</label>
                                <input type="text" name="stitcher_address" id="stitcher_address" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="stitcher_aadhar">Enter Aadhar No</label>
                                <input type="number" name="stitcher_aadhar" id="stitcher_aadhar" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="stitcher_pan">Enter Pan No</label>
                                <input type="text" name="stitcher_pan" id="stitcher_pan" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="bank_name">Enter Bank Name</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="bank_no">Enter Bank Account Number</label>
                                <input type="text" name="bank_no" id="bank_no" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="ifsc_code">Enter IFSC Code</label>
                                <input type="text" name="ifsc_code" id="ifsc_code" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary" name="add_stitcher">Add Stitcher</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h2>Edit Stitcher</h2>
                    </div>
                    <div class="card-body">
                        <?php echo $updateStitcherMsg; ?>
                        <?php echo $deleteStitcherMsg; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="select_stitcher">Select Stitcher Name</label>
                                <select name="stitcher_id" id="select_stitcher" class="form-control">
                                    <option value="">Select Stitcher</option>
                                    <?php
                                    $selectStitcherQuery = mysqli_query($con, "SELECT * FROM stitcher ORDER BY stitcher_name ASC");
                                    while ($row = mysqli_fetch_assoc($selectStitcherQuery)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['stitcher_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="new_stitcher_name">New Stitcher Name</label>
                                <input type="text" name="new_stitcher_name" id="new_stitcher_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new_stitcher_contact">New Contact Number</label>
                                <input type="number" name="new_stitcher_contact" id="new_stitcher_contact" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new_stitcher_address">New Address</label>
                                <input type="text" name="new_stitcher_address" id="new_stitcher_address" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new_stitcher_aadhar">New Aadhar No</label>
                                <input type="number" name="new_stitcher_aadhar" id="new_stitcher_aadhar" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new_stitcher_pan">New Pan No</label>
                                <input type="text" name="new_stitcher_pan" id="new_stitcher_pan" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new_bank_name">New Bank Name</label>
                                <input type="text" name="new_bank_name" id="new_bank_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new_bank_no">New Bank Account Number</label>
                                <input type="text" name="new_bank_no" id="new_bank_no" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new_ifsc_code">New IFSC Code</label>
                                <input type="text" name="new_ifsc_code" id="new_ifsc_code" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-primary" name="edit_stitcher">Edit Stitcher</button>
                        </form>
                        <form action="" method="post" class="mt-3">
                            <div class="form-group">
                                <label for="delete_stitcher">Delete Stitcher</label>
                                <select name="delete_stitcher_id" id="delete_stitcher" class="form-control">
                                    <option value="">Select Stitcher</option>
                                    <?php
                                    $selectStitcherQuery = mysqli_query($con, "SELECT * FROM stitcher ORDER BY stitcher_name ASC");
                                    while ($row = mysqli_fetch_assoc($selectStitcherQuery)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['stitcher_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger" name="delete_stitcher">Delete Stitcher</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
