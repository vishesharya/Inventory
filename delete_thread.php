<?php
session_start();
include_once 'include/connection.php';

$addThreadtMsg = '';
$deleteThreadMsg = '';


// Delete Product Form Handling
if (isset($_POST['delete_thread'])) {
    $thread_name = $_POST['thread_name'];
  

    // Delete from kits_product table
    $deleteThreadQuery = "DELETE FROM threads WHERE thread_name = '$thread_name'";
    mysqli_query($con, $deleteThreadQuery);

    // Check if deletion was successful
    if (mysqli_affected_rows($con) > 0) {
        $deleteThreadMsg = "<p style='color: green;font-size: medium;text-align: center;'>Thread deleted successfully</p>";
    } else {
        $deleteThreadMsg = "<p style='color: red;font-size: medium;text-align: center;'>Thread to delete product</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Thread</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        // Function to hide the message after 1500 milliseconds (1.5 seconds)
        setTimeout(function () {
            document.getElementById("deleteThreadMsg").style.display = "none";
        }, 1500);
    </script>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Delete  Thread</h2>
                    </div>
                    <div class="card-body">
                      
                        <!-- Set an id for the message div -->
                        <div id="deleteThreadMsg">
                            <?php echo $deleteThreadMsg; ?>
                        </div>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="thread_name_delete">Select Thread</label>
                                <select name="thread_name" id="thread_name_delete" class="form-control" required>
                                    <option value="">Select Thread</option>
                                    <?php
                                    // Fetch product names alphabetically from the database
                                    $threadQuery = mysqli_query($con, "SELECT DISTINCT thread_name FROM threads ORDER BY thread_name ASC");
                                    while ($row = mysqli_fetch_assoc($threadQuery)) {
                                        echo "<option value='" . $row['thread_name'] . "'>" . $row['thread_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-danger" name="delete_thread">Delete Thread</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
