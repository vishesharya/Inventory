<?php
include_once 'connection.php';

class Vishesh_admin{	

function insert_code($table){
include('connection.php');
$this->table_name=$table;

$cr_date=date('d M y');

$pid="P-".rand();


$code=mysqli_real_escape_string($con,$_POST['code']);
$status=mysqli_real_escape_string($con,$_POST['satus']); 

$result=mysqli_query($con,"INSERT INTO ".$this->table_name."(code,status,cr_date)
       value('$code','$status','$cr_date')") or die(mysqli_error());
	   
     			   if($result)
				   return $msg="pass";
				   else
				   return $msg="fail";
}	


//select blog details
function select_code($table){
//include('connection.php');
include('connection.php');
$this->table_name=$table;
$qry = mysqli_query($con,"SELECT * FROM ".$this->table_name." order by id desc ") or die("select query fail".mysqli_error());
  return $qry;
}

//select product details
function select_code_dtls($table,$a){
//include('connection.php');
include('connection.php');
$this->table_name=$table;
$qry = mysqli_query($con,"SELECT * FROM ".$this->table_name." where id='$a' ") or die("select query fail".mysqli_error());
  return mysqli_fetch_array($qry);
}

function slect_valid_user($table){
//include('connection.php');
include('connection.php');
$this->table_name=$table;
//$user=md5();
//$pass=md5();
$user=mysqli_real_escape_string($con,$_POST['username']);
$pass=mysqli_real_escape_string($con,$_POST['password']);

$qry=mysqli_query($con,"SELECT * FROM $this->table_name WHERE username='$user' AND password='$pass' ") 
or die('Select query fail'.mysqli_connect_error());	
$data=mysqli_fetch_array($qry);

if(mysqli_num_rows($qry)==1){ 
	session_start();
	$_SESSION['user']="userlogging";
$_SESSION['username']=$data['username'];
$_SESSION['name']=$data['name'];
$_SESSION['id']=$data['id'];
$_SESSION['role_user']=$data['role_user'];	

header('location:dashboard.php');
}
else{

echo "<script>alert('Username or password is not correct')</script>";
//header('location:index.php');
}
}


//----------------------useful END-------------------------//


}
//closing of class


?>