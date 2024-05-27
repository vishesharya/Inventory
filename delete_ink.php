<?php
session_start();
include_once 'include/connection.php';

$deleteInkMsg = '';

// Delete Ink Form Handling
if (isset($_POST['delete_ink'])) {
    $ink_name = $_POST['ink_name'];

    // Delete from ink table
    $deleteInkQuery = "DELETE FROM ink WHERE ink_name = '$ink_name'";
    mysqli_query($con, $deleteInkQuery);

    // Check if deletion was successful 
    if (mysqli_affected_rows($con) > 0) {
        $deleteInkMsg = "<p style='color: green;font-size: medium;text-align: center;'>Ink deleted successfully</p>";
    } else {
        $deleteInkMsg = "<p style='color: red;font-size: medium;text-align: center;'>Failed to delete ink</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Ink</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        // Function to hide the message after 1500 milliseconds (1.5 seconds)
        setTimeout(function () {
            document.getElementById("deleteInkMsg").style.display = "none";
        }, 1500);
    </script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Delete Ink</h2>
                    </div>
                    <div class="card-body">
                        <!-- Set an id for the message div -->
                        <div id="deleteInkMsg">
                            <?php echo $deleteInkMsg; ?>
                        </div>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="ink_name_delete">Select Ink</label>
                                <select name="ink_name" id="ink_name_delete" class="form-control" required>
                                    <option value="">Select Ink</option>
                                    <?php
                                    // Fetch ink names alphabetically from the database
                                    $inkQuery = mysqli_query($con, "SELECT DISTINCT ink_name FROM ink ORDER BY ink_name ASC");
                                    while ($row = mysqli_fetch_assoc($inkQuery)) {
                                        echo "<option value='" . $row['ink_name'] . "'>" . $row['ink_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger" name="delete_ink">Delete Ink</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
