<?php

include './include/connection.php';

// Initialize response array
$response = [];

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        if ($con->query("INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')")) {
            $response['success'] = true;
            $response['message'] = 'User added successfully.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to add user.';
        }
    }

    if (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        if ($con->query("DELETE FROM users WHERE id = $user_id")) {
            $response['success'] = true;
            $response['message'] = 'User deleted successfully.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to delete user.';
        }
    }

    if (isset($_POST['forgot_password'])) {
        $username = $_POST['username'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        if ($con->query("UPDATE users SET password = '$new_password' WHERE username = '$username'")) {
            $response['success'] = true;
            $response['message'] = 'Password updated successfully.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to update password.';
        }
    }

    echo json_encode($response);
    exit();
}

// Fetch usernames for dropdown
$usernames = $con->query("SELECT username FROM users");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* ... (existing styles) ... */
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>Add / Delete Users</h1>
    </header>
    <div class="container">
        <!-- Existing sections ... -->

        <!-- Forgot Password Section -->
        <section class="form-container">
            <h2>Forgot Password</h2>
            <form id="forgot-password-form">
                <label for="username">Select Username:</label>
                <select id="username" name="username" required>
                    <?php while ($row = $usernames->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($row['username']); ?>">
                            <?php echo htmlspecialchars($row['username']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                
                <button type="submit">Reset Password</button>
            </form>
        </section>
    </div>
    <script>
        $(document).ready(function() {
            // Handle form submission for adding a user
            $('#add-user-form').submit(function(event) {
                event.preventDefault();
                
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: $(this).serialize() + '&add_user=true',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload(); // Refresh the page to update the user list
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            // Handle delete button click
            $('#users-table').on('click', '.delete-btn', function() {
                var row = $(this).closest('tr');
                var userId = row.data('id');
                
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: { user_id: userId, delete_user: true },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            row.remove(); // Remove the row from the table
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            // Handle form submission for resetting password
            $('#forgot-password-form').submit(function(event) {
                event.preventDefault();
                
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: $(this).serialize() + '&forgot_password=true',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload(); // Refresh the page to update the user list
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>
