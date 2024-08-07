<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');

$updateQuantityMsg = '';

// Update Quantity Form Handling
if (isset($_POST['check_quantity'])) {
    $thread_name = $_POST['thread_name'];

    // Retrieve thread_remaining_quantity from the threads table
    $threadQuery = mysqli_query($con, "SELECT thread_remaining_quantity FROM threads WHERE thread_name = '$thread_name'");
    $thread_row = mysqli_fetch_assoc($threadQuery);

    // Determine the remaining quantity for the selected thread
    $remaining_thread_balance = isset($thread_row['thread_remaining_quantity']) ? $thread_row['thread_remaining_quantity'] : 0;

    // Display the remaining balance
    $updateQuantityMsg = "<p style='color: green; font-size: medium; text-align: center;'>Remaining Thread Balance for $thread_name: $remaining_thread_balance</p>";
} 

// Update Quantity Handling
if (isset($_POST['update_thread_balance'])) {
    $new_quantity = $_POST['new_quantity'];
    $thread_name = $_POST['thread_name'];

    // Update thread_remaining_quantity in threads table
    $updateThreadQuery = "UPDATE threads SET thread_remaining_quantity = '$new_quantity' WHERE thread_name = '$thread_name'";
    mysqli_query($con, $updateThreadQuery);

    $updateQuantityMsg = "<p style='color: green; font-size: medium; text-align: center;'>Thread balance updated successfully for $thread_name</p>";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thread Management</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h2>Check & Update Thread Balance</h2>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="thread_name">Select Thread</label>
                                <select name="thread_name" id="thread_name" class="form-control" required>
                                    <option value="">Select Thread</option>
                                    <?php
                                    // Fetch thread names from the database
                                    $threadQuery = mysqli_query($con, "SELECT thread_name FROM threads ORDER BY thread_name ASC");
                                    while ($row = mysqli_fetch_assoc($threadQuery)) {
                                        echo "<option value='" . $row['thread_name'] . "'>" . $row['thread_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary" name="check_quantity">Check Balance</button>
                        </form>
                        <div class="mt-4">
                            <?php echo $updateQuantityMsg; ?>
                        </div>
                        <!-- Form to update thread balance -->
                        <?php if (!empty($updateQuantityMsg)): ?>
                        <form action="" method="post">
                            <input type="hidden" name="thread_name" value="<?php echo $thread_name; ?>">
                            <div class="form-group">
                                <label for="new_quantity">Update Thread Balance</label>
                                <input type="number" name="new_quantity" id="new_quantity" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary" name="update_thread_balance">Update Balance</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
