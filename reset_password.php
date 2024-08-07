<!-- reset_password.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<body>
    <form id="reset-password-form">
        <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
        <label for="password">New Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Reset Password</button>
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#reset-password-form').submit(function(event) {
                event.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: 'handle_reset_password.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        alert(response.message);
                    }
                });
            });
        });
    </script>
</body>
</html>
