<?php
session_start();

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to your login page
    exit; // Stop further execution
}
include_once 'include/connection.php';
include_once 'include/admin-main.php';

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Bar Code Generator</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link href="assets/labels.css" rel="stylesheet" type="text/css" >

</head>
<body>
     <form class="form">

  <a href="bar_code.php"><input type="button" value="Back"></a>
  <input type="button"    onclick="openWin()"  value="Print"/>
</form>

	 <div class="book" id="print">
  
      	<?php 
         if(isset($_POST['submit'])) {

	$from_date =   $_POST['from_date'];
	$to_date   =   $_POST['to_date'];
	
	$fdate = date('Y-m-d', strtotime($_POST['from_date']));
	$tdate = date('Y-m-d', strtotime($_POST['to_date']));
	
	if(empty($_POST['from_date']) && empty($_POST['to_date'])){
	    
	       $minDate = mysqli_fetch_array(mysqli_query($con,"SELECT MIN(cr_date) as cr_date FROM `code` WHERE 1 ")); 
	       $maxDate = mysqli_fetch_array(mysqli_query($con,"SELECT MAX(cr_date) as cr_date FROM `code` WHERE 1 ")); 
	       
	    $fdate = date('Y-m-d', strtotime($minDate['cr_date'])); 
	    $tdate = date('Y-m-d', strtotime($maxDate['cr_date'])); 
	    
	}

$where = " AND status='0' AND cr_date BETWEEN '$fdate' AND '$tdate'";
}
  //echo $where;
  
 
	?> 

  <div class="page"  >
      <?php
      
       $show=mysqli_query($con,"SELECT * FROM code WHERE 1 $where ORDER BY cr_date ASC, id ASC");	
    while($row=mysqli_fetch_array($show)) {
    
   
    ?>
        
    <div class="label" ><?php echo ucfirst($row['code']); ?></div>
  <?php } ?>
</div>
 
<script type="text/javascript">
  function openWin()
  {
    var printContents = document.getElementById('print').innerHTML;
         var originalContents = document.body.innerHTML;
         document.body.innerHTML = printContents;
         window.print();
         document.body.innerHTML = originalContents;
  }
</script>
</body>
</html>