<?php
include_once 'connection.php';

class Vishesh_admin {
    private $con;
    
    public function __construct() {
        include('connection.php');
        $this->con = $con;
    }

    function insert_code($table) {
        $this->table_name = $table;
        $cr_date = date('d M y');
        $pid = "P-" . rand();
        $code = mysqli_real_escape_string($this->con, $_POST['code']);
        $status = mysqli_real_escape_string($this->con, $_POST['status']);
        $result = mysqli_query($this->con, "INSERT INTO " . $this->table_name . "(code, status, cr_date) VALUES ('$code', '$status', '$cr_date')") or die(mysqli_error($this->con));

        if ($result) {
            return "pass";
        } else {
            return "fail";
        }
    }

    function select_code($table) {
        $this->table_name = $table;
        $qry = mysqli_query($this->con, "SELECT * FROM " . $this->table_name . " ORDER BY id DESC") or die("select query fail" . mysqli_error($this->con));
        return $qry;
    }

    function select_code_dtls($table, $a) {
        $this->table_name = $table;
        $qry = mysqli_query($this->con, "SELECT * FROM " . $this->table_name . " WHERE id='$a'") or die("select query fail" . mysqli_error($this->con));
        return mysqli_fetch_array($qry);
    }

    function select_valid_user($table) {
        $this->table_name = $table;
        $user = mysqli_real_escape_string($this->con, $_POST['username']);
        $pass = mysqli_real_escape_string($this->con, $_POST['password']);
        
        $qry = mysqli_query($this->con, "SELECT * FROM $this->table_name WHERE username='$user'") or die('Select query fail' . mysqli_connect_error());
        $data = mysqli_fetch_array($qry);
        
        // Check if user exists and verify the password
        if (mysqli_num_rows($qry) == 1 && password_verify($pass, $data['password'])) {
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
    }
}
?>
