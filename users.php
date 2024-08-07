<?php
include './include/connection.php';

// Initialize response array
$response = [];

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Add User
    if (isset($_POST['add_user'])) {
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = $_POST['role'];
        $first_name = password_hash($_POST['first_name'], PASSWORD_DEFAULT);
        $dob = password_hash($_POST['dob'], PASSWORD_DEFAULT);
        $joining_date = password_hash($_POST['joining_date'], PASSWORD_DEFAULT);

        if ($con->query("INSERT INTO users (username, password, role, first_name, dob, joining_date) VALUES ('$username', '$password', '$role', '$first_name', '$dob', '$joining_date')")) {
            $response['success'] = true;
            $response['message'] = 'User added successfully.';
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to add user.';
        }
    }

    // Delete User
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

    // Reset Password
    if (isset($_POST['reset_password'])) {
        $username = $_POST['username'];
        $first_name = $_POST['first_name'];
        $dob = $_POST['dob'];
        $joining_date = $_POST['joining_date'];

        $result = $con->query("SELECT * FROM users WHERE username = '$username'");
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($first_name, $user['first_name']) && password_verify($dob, $user['dob']) && password_verify($joining_date, $user['joining_date'])) {
                $response['success'] = true;
                $response['message'] = 'Security questions matched.';
            } else {
                $response['success'] = false;
                $response['message'] = 'Security questions did not match.';
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'User not found.';
        }
    }

    // Set New Password
    if (isset($_POST['set_new_password'])) {
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
    <title>Add / Delete User</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background: #333;
            color: #fff;
            padding: 1rem 0;
            text-align: center;
        }
        .container {
            width: 90%;
            margin: auto;
            overflow: hidden;
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
            margin-bottom: 20px;
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
        .form-row {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .form-row .form-container {
            flex: 1;
            min-width: 300px;
        }
        @media (max-width: 767px) {
            .form-row {
                flex-direction: column;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>Add / Delete User</h1>
    </header>
    <div class="container">
        <section>
            <h2>Users</h2>
            <table id="users-table">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr data-id="<?php echo $row['id']; ?>">
                    <td><input type="checkbox" class="select-user"></td>
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
        
        <section class="form-row">
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

                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>

                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>

                    <label for="joining_date">Joining Date:</label>
                    <input type="date" id="joining_date" name="joining_date" required>
                    
                    <button type="submit">Add User</button>
                </form>
            </div>

            <div class="form-container">
                <h2>Reset Password</h2>
                <form id="reset-password-form">
                    <label for="reset_username">Username:</label>
                    <input type="text" id="reset_username" name="username" required>

                    <label for="reset_first_name">First Name:</label>
                    <input type="text" id="reset_first_name" name="first_name" required>

                    <label for="reset_dob">Date of Birth:</label>
                    <input type="date" id="reset_dob" name="dob" required>

                    <label for="reset_joining_date">Joining Date:</label>
                    <input type="date" id="reset_joining_date" name="joining_date" required>

                    <button type="submit">Verify</button>
                </form>
            </div>
        </section>

        <section class="form-container" id="reset-password-section" style="display:none;">
            <h2>Enter New Password</h2>
            <form id="set-new-password-form">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" required>
                <button type="submit">Reset Password</button>
            </form>
        </section>
    </div>
    <script>
        $(document).ready(function() {
            // Select/Deselect all users
            $('#select-all').click(function() {
                var isChecked = $(this).is(':checked');
                $('.select-user').prop('checked', isChecked);
            });

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

            // Handle password reset form submission
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
                            $('#reset-password-section').show();
                        } else {
                            alert(response.message);
                        }
                    }
                });
            });

            // Handle new password form submission
            $('#set-new-password-form').submit(function(event) {
                event.preventDefault();
                
                $.ajax({
                    type: 'POST',
                    url: '',
                    data: {
                        username: $('#reset_username').val(),
                        new_password: $('#new_password').val(),
                        set_new_password: true
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            location.reload(); // Refresh the page after password reset
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
