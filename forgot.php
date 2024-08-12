<?php
include_once 'include/connection.php';

// Reset Password via Security Questions
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $username = trim($_POST['username']);
    $answer1 = trim($_POST['security_answer1']);
    $answer2 = trim($_POST['security_answer2']);
    $new_password = password_hash(trim($_POST['new_password']), PASSWORD_DEFAULT);
    
    $stmt = $con->prepare("SELECT security_answer1, security_answer2 FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($db_answer1, $db_answer2);
    $stmt->fetch();
    
    if (password_verify($answer1, $db_answer1) && password_verify($answer2, $db_answer2)) {
        $stmt->close();
        $stmt = $con->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->bind_param("ss", $new_password, $username);
        if ($stmt->execute()) {
            echo "<div class='message success'>Password reset successfully!</div>";
        } else {
            echo "<div class='message error'>Failed to reset the password.</div>";
        }
    } else {
        echo "<div class='message error'>Security answers do not match!</div>";
    }
    
    $stmt->close();
}

// Change Security Questions and Answers
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_security'])) {
    $username = trim($_POST['username']);
    $current_answer1 = trim($_POST['current_security_answer1']);
    $current_answer2 = trim($_POST['current_security_answer2']);
    $new_question1 = trim($_POST['new_security_question1']);
    $new_answer1 = password_hash(trim($_POST['new_security_answer1']), PASSWORD_DEFAULT);
    $new_question2 = trim($_POST['new_security_question2']);
    $new_answer2 = password_hash(trim($_POST['new_security_answer2']), PASSWORD_DEFAULT);
    
    $stmt = $con->prepare("SELECT security_answer1, security_answer2 FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($db_answer1, $db_answer2);
    $stmt->fetch();
    
    if (password_verify($current_answer1, $db_answer1) && password_verify($current_answer2, $db_answer2)) {
        $stmt->close();
        $stmt = $con->prepare("UPDATE users SET security_question1 = ?, security_answer1 = ?, security_question2 = ?, security_answer2 = ? WHERE username = ?");
        $stmt->bind_param("sssss", $new_question1, $new_answer1, $new_question2, $new_answer2, $username);
        if ($stmt->execute()) {
            echo "<div class='message success'>Security questions and answers updated successfully!</div>";
        } else {
            echo "<div class='message error'>Failed to update security questions and answers.</div>";
        }
    } else {
        echo "<div class='message error'>Current security answers do not match!</div>";
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password & Security Questions</title>
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
    <h2>Reset Password</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Enter your username" required><br>
        <input type="text" name="security_answer1" placeholder="Answer to security question 1" required><br>
        <input type="text" name="security_answer2" placeholder="Answer to security question 2" required><br>
        <input type="password" name="new_password" placeholder="Enter new password" required><br>
        <input type="submit" name="reset_password" value="Reset Password">
    </form>
</div>

<div class="container">
    <h2>Change Security Questions</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Enter your username" required><br>
        <input type="text" name="current_security_answer1" placeholder="Current answer to security question 1" required><br>
        <input type="text" name="current_security_answer2" placeholder="Current answer to security question 2" required><br>
        <input type="text" name="new_security_question1" placeholder="New security question 1" required><br>
        <input type="text" name="new_security_answer1" placeholder="New answer to security question 1" required><br>
        <input type="text" name="new_security_question2" placeholder="New security question 2" required><br>
        <input type="text" name="new_security_answer2" placeholder="New answer to security question 2" required><br>
        <input type="submit" name="change_security" value="Change Security Questions">
    </form>
</div>

</body>
</html>
