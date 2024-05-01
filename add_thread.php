<?php
session_start();
include_once 'include/connection.php';

$addProductMsg = '';

// Add Product Form Handling
if (isset($_POST['add_thread'])) {
    $thread_name = $_POST['thread_name'];

    // Check if the thread already exists
    $checkQuery = mysqli_query($con, "SELECT * FROM threads WHERE thread_name = '$thread_name'");
    $rowCount = mysqli_num_rows($checkQuery);

    if ($rowCount > 0) {
        $addProductMsg = "<p id='addProductMsg' style='color: red;font-size: medium;text-align: center;'>Thread already exists</p>";
    } else {
        // Insert into threads table
        $insertThreadProductQuery = "INSERT INTO threads (thread_name) VALUES ('$thread_name')";
        if (mysqli_query($con, $insertThreadProductQuery)) {
            $addProductMsg = "<p id='addProductMsg' style='color: green;font-size: medium;text-align: center;'>Thread added successfully</p>";
        } else {
            $addProductMsg = "<p id='addProductMsg' style='color: red;font-size: medium;text-align: center;'>Failed to add thread</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Thread</title>
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
                        <h2>Add Thread</h2>
                    </div>
                    <div class="card-body">
                        <?php echo $addProductMsg; ?>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="thread_name">Thread Name</label>
                                <input type="text" name="thread_name" id="thread_name" class="form-control" required>
                            </div>
                           
                            <button type="submit" class="btn btn-primary" name="add_thread">Add Thread</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
