<html>
<body>
	<?php
   // error_reporting(4);
include_once 'include/connection.php';

	if(isset($_POST['addForm']))
	{
		
		$name = $_POST['name'];
		$mobile = $_POST['mobile'];
		$email = $_POST['email'];
		$pcode = $_POST['pcode'];

			$result=mysqli_query($con,"INSERT INTO `contact`(`name`,`mobile`, `email`, `pcode`) VALUES ('$name','$mobile', '$email'
            , '$pcode') ") or die(mysqli_connect_error());

            
    }    




?>

<script language="javascript">
alert("Thanks for Posting! We Will Get Back To You Soon...");
window.location="validation.html";

</script>


</body>
</html>