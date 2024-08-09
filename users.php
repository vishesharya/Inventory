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
    if (isset($_POST['reset_password'])) {
        $username = $_POST['username'];
        $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    
        if ($con->query("UPDATE users SET password = '$new_password' WHERE username = '$username'")) {
            $response['success'] = true;
            $response['message'] = 'Password reset successfully.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to reset password.';
        }
    }
    
    
    echo json_encode($response);
    exit();
}

// Fetch users for initial load
$result = $con->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }
        header {
            background: #333;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: #fff;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 20px;
            flex: 1;
        }
        .form-container h2 {
            margin-top: 0;
        }
        label {
            display: block;
            margin: 8px 0 4px;
        }
        input, select {
            width: calc(100% - 22px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #555;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
        }
        .action-buttons form {
            margin: 0;
        }
        .forms-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>Add / Delete / Reset Password</h1>
    </header>
    <div class="container">
        <section>
            <h2>Users</h2>
            <table id="users-table">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr data-id="<?php echo $row['id']; ?>">
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo $row['role'] == 1 ? 'Admin' : 'User'; ?></td>
                    <td>
                        <button class="delete-btn">Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>
        
        <section class="forms-row">
            <div class="form-container">
                <h2>Add User</h2>
                <form id="add-user-form">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                    
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    
                    <label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                    </select>
                    
                    <button type="submit">Add User</button>
                </form>
            </div>
            
            <div class="form-container">
                <h2>Forgot Password</h2>
                <form id="reset-password-form">
                    <label for="reset-username">Username:</label>
                    <input type="text" id="reset-username" name="username" required>
                    
                    <label for="new-password">New Password:</label>
                    <input type="password" id="new-password" name="new_password" required>
                    
                    <button type="submit">Reset Password</button>
                </form>
            </div>
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

            // Handle form submission for resetting password
            $('#reset-password-form').submit(function(event) {
                event.preventDefault();
                
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: $(this).serialize() + '&reset_password=true',
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
        });
    </script>
</body>
</html>
