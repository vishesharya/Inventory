<?php
include_once 'include/connection.php';
include './include/check_login.php';
include_once 'include/admin-main.php';

$users = $con->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permissions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9ecef;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 8px;
        }
        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        label {
            font-size: 16px;
            color: #495057;
        }
        select {
            font-size: 16px;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            width: 200px;
        }
        button {
            font-size: 16px;
            padding: 10px 20px;
            color: #fff;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }
        button:hover {
            background-color: #218838;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <form method="post" action="view_permissions.php">
            <label for="user">Select User:</label>
            <select name="user_id" id="user">
                <?php while($user = $users->fetch_assoc()): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">View Permissions</button>
        </form>
    </div>
</body>
</html>
