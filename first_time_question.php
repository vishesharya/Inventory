<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['set_security'])) {
    $username = trim($_POST['username']);
    $question1 = trim($_POST['security_question1']);
    $answer1 = password_hash(trim($_POST['security_answer1']), PASSWORD_DEFAULT);
    $question2 = trim($_POST['security_question2']);
    $answer2 = password_hash(trim($_POST['security_answer2']), PASSWORD_DEFAULT);

    // Check if the user has already set security questions
    $stmt = $con->prepare("SELECT security_question1 FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($db_question1);
    $stmt->fetch();
    $stmt->close();

    if (empty($db_question1)) {
        // User has not set security questions yet
        $stmt = $con->prepare("UPDATE users SET security_question1 = ?, security_answer1 = ?, security_question2 = ?, security_answer2 = ? WHERE username = ?");
        $stmt->bind_param("sssss", $question1, $answer1, $question2, $answer2, $username);
        if ($stmt->execute()) {
            echo "<div class='message success'>Security questions and answers set successfully!</div>";
        } else {
            echo "<div class='message error'>Failed to set security questions and answers.</div>";
        }
        $stmt->close();
    } else {
        echo "<div class='message error'>Security questions have already been set.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Security Questions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        input[type="text"], input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Set Security Questions</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Enter your username" required><br>
        <input type="text" name="security_question1" placeholder="Enter security question 1" required><br>
        <input type="text" name="security_answer1" placeholder="Answer to security question 1" required><br>
        <input type="text" name="security_question2" placeholder="Enter security question 2" required><br>
        <input type="text" name="security_answer2" placeholder="Answer to security question 2" required><br>
        <input type="submit" name="set_security" value="Set Security Questions">
    </form>
</div>

</body>
</html>
