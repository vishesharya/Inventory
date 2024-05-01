<?php
session_start();
include_once 'connection.php';
//include('sessiontrack.php');

$del_loan=mysqli_query($con,"delete from contact where id='$_REQUEST[id]'");

?>
<script>window.location='../customer_query_dtls.php'</script>

