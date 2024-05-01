<?php
include_once 'include/connection.php';
include_once 'include/admin-main.php';

$msg ='';

if(isset($_POST['addForm'])) {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $pcode = $_POST['pcode'];

    $result = mysqli_query($con, "INSERT INTO `contact`(`name`, `mobile`, `email`, `pcode`) VALUES ('$name','$mobile', '$email', '$pcode') ") or die(mysqli_connect_error());

    if($result) {
        $msg = "<p style='color: green;font-size: medium;text-align: center;'>Thank you, your complaint has been accepted.... <a href='validation.php'>home</a></p>";
    } else {
        echo '';
    }  
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Validation</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css">
    <link rel="stylesheet" href="assets/validation.css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    
    <div class="main-block">
        <h1 style="color: #1c87c9;">Contact us</h1>
        <form action="" method="post" class="form-horizontal" enctype="multipart/form-data" accept-charset="utf-8">
            <label id='icon' for='name'><i class='fa fa-user-circle-o'></i></label>
            <input type='text' id='name' name='name' placeholder='Your name..' required>
            
            <label id='icon' for='email'><i class='fa fa-envelope-open-o'></i></label>
            <input type='email' id='email' name='email' placeholder='Your email..' required>
            
            <label id='icon' for='mobile'><i class='fa fa-phone'></i></label>
            <input type='text' id='mobile' name='mobile' maxlength="10" onkeyup="this.value=this.value.replace(/[^\d]/,'')" placeholder='Your mobile..' required>
            
            <label id='icon' for='pcode'><i class='fa fa-product-hunt'></i></label>
            <input type='text' id='pcode' name='pcode' maxlength="16" placeholder='Your product no...' required>
            
            <br>
            <button type='submit' name='addForm'>Submit</button>
            <br/>
            <?php echo $msg; ?>
        </form>
    </div>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-QCJH6HJ090"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-QCJH6HJ090');
    </script>
</body>
</html>
