<?php
include_once 'connection.php';

class Vishesh_admin {
    function insert_code($table) {
        include('connection.php');
        $this->table_name = $table;
        $cr_date = date('d M y');
        $pid = "P-" . rand();
        $code = mysqli_real_escape_string($con, $_POST['code']);
        $status = mysqli_real_escape_string($con, $_POST['status']);
        $result = mysqli_query($con, "INSERT INTO " . $this->table_name . "(code, status, cr_date) VALUES ('$code', '$status', '$cr_date')") or die(mysqli_error($con));

        if ($result) {
            return $msg = "pass";
        } else {
            return $msg = "fail";
        }
    }

    function select_code($table) {
        include('connection.php');
        $this->table_name = $table;
        $qry = mysqli_query($con, "SELECT * FROM " . $this->table_name . " ORDER BY id DESC") or die("select query fail" . mysqli_error($con));
        return $qry;
    }

    function select_code_dtls($table, $a) {
        include('connection.php');
        $this->table_name = $table;
        $qry = mysqli_query($con, "SELECT * FROM " . $this->table_name . " WHERE id='$a'") or die("select query fail" . mysqli_error($con));
        return mysqli_fetch_array($qry);
    }

    function select_valid_user($table) {
        include('connection.php');
        $this->table_name = $table;
        $user = mysqli_real_escape_string($con, $_POST['username']);
        $pass = $_POST['password'];

        // Fetch the user data based on the username
        $qry = mysqli_query($con, "SELECT * FROM $this->table_name WHERE username='$user'") or die('Select query fail: ' . mysqli_error($con));
        $data = mysqli_fetch_array($qry);

        if ($data) {
            // Verify the password
            if (password_verify($pass, $data['password'])) {
                session_start();
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['username'] = $data['username'];
                $_SESSION['name'] = $data['name'];
                $_SESSION['id'] = $data['id'];
                $_SESSION['role_user'] = $data['role_user'];

                header('location:dashboard.php');
                exit;
            } else {
                echo "<script>alert('Username or password is not correct')</script>";
            }
        } else {
            echo "<script>alert('Username or password is not correct')</script>";
        }
    }
}
?>
