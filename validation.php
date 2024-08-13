<?php
include_once 'include/connection.php';


$msg = '';
$code = '';


// Define the expected token
$expected_token = "12345";

// Check if the token is present and valid
if (isset($_GET['access_token']) && $_GET['access_token'] === $expected_token) {
    
} else {
    // Invalid or missing token, redirect to home page
    header("Location: https://khannasports.in");
    exit();
}

if (isset($_POST['AddCode'])) {
    $code = $_POST['code'];
    
    // Check the first two characters of the code
    $codePrefix = substr($code, 0, 2);

    if ($codePrefix === 'FB') {
        // If the first two characters are 'FB', check in the 'f_code' table
        $query = mysqli_query($con, "SELECT * FROM `f_code` WHERE `fcode` = '$code'");
        if ($query) {
            $rowCount = mysqli_num_rows($query);
        } else {
            $rowCount = 0;
        }
    } elseif ($codePrefix === 'TB') {
        // If the first two characters are 'TB', check in the 't_code' table
        $query = mysqli_query($con, "SELECT * FROM `t_code` WHERE `tcode` = '$code'");
        if ($query) {
            $rowCount = mysqli_num_rows($query);
        } else {
            $rowCount = 0;
        }
    } else {
        // Invalid code format
        $rowCount = 0;
    }

    if ($rowCount > 0) {
        // Code found, proceed with insertion

        // Check if the code is already verified
        $statusQueryFB = mysqli_query($con, "SELECT `status` FROM `f_code` WHERE `fcode` = '$code'");
        $statusQueryTB = mysqli_query($con, "SELECT `status` FROM `t_code` WHERE `tcode` = '$code'");
        
        if ($statusQueryFB && $statusQueryTB) {
            $statusDataFB = mysqli_fetch_assoc($statusQueryFB);
            $statusDataTB = mysqli_fetch_assoc($statusQueryTB);
            $statusFB = isset($statusDataFB['status']) ? $statusDataFB['status'] : null;
            $statusTB = isset($statusDataTB['status']) ? $statusDataTB['status'] : null;

            if ($statusFB == 1 || $statusTB == 1) {
                // Code is already verified
                $msg = "<p style='color: green;font-size: medium;text-align: center;'>Your product code is already verified</p>";
            } else {
                // Code is not verified yet, proceed with insertion
                $name = $_POST['name'];
                $mobile = $_POST['mobile'];
                $email = $_POST['email'];
                $city = $_POST['city'];
                $state = $_POST['state'];
                $country = $_POST['country'];

                $product = isset($_POST['product']) ? $_POST['product'] : '';
                $model = isset($_POST['model']) ? $_POST['model'] : '';

                $result = mysqli_query($con, "INSERT INTO `contact`(`name`, `mobile`, `email`, `city`, `state`, `country`, `product`, `model`, `pcode`) 
                               VALUES ('$name','$mobile', '$email', '$city', '$state', '$country', '$product', '$model', '$code') ") or die(mysqli_connect_error());

                if ($result) {
                    // Update the status of the code to 1 (used) in the appropriate table
                    if ($codePrefix === 'FB') {
                        mysqli_query($con, "UPDATE `f_code` SET `status` = '1' WHERE `fcode` = '$code'");
                    } elseif ($codePrefix === 'TB') {
                        mysqli_query($con, "UPDATE `t_code` SET `status` = '1' WHERE `tcode` = '$code'");
                    }
                    $msg = "<p style='color: green;font-size: medium;text-align: center;'>Congratulations! Your product code is activated successfully</p>";
                } else {
                    $msg = "<p style='color: red;font-size: medium;text-align: center;'>Failed to insert data into the database</p>";
                }
            }
        } else {
            $msg = "<p style='color: red;font-size: medium;text-align: center;'>Failed to fetch status from the database</p>";
        }
    } else {
        // Code not found in the appropriate table
        $msg = "<p style='color: red;font-size: medium;text-align: center;'>Your product code is not verified</p>";
    }
}
?>




<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Verify Your Khanna Product">
    <meta name="author" content="Khanna Sports">

    <title>Product Verification</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link href="new/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="new/css/sb-admin-2.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">


    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block ">
                        <img style="width: 500px;" src="./assets/images/Poster.jpg" alt="">
                    </div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Product Verification</h1>
                            </div>
                            <!-- start Varification form -->
                            <form action="" method="post"  enctype="multipart/form-data" accept-charset="utf-8" class="user">
                                 <!-- Instagram Note -->
                            <p style="color: #0056b3; font-size: medium; text-align: center;">
                                Get a voucher worth INR 250! Follow us on Instagram and enter your Instagram username below to verify.
                            </p>

                            <!-- Instagram Username Field -->
                            <div class="form-group">
                                <input type="text" name="instagram_username" value="" class="form-control form-control-user" 
                                    placeholder="Enter your Instagram Username" maxlength="30" required>
                            </div>
                            <div class="form-group">
                                    <input type="text" name="code" value="" class="form-control form-control-user" 
                                        placeholder="Enter 16 digit Code" maxlength="16" required>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type='text' id='name' name='name' class="form-control form-control-user" 
                                            placeholder="Name" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type='text' id='mobile' name='mobile' maxlength="10"
               onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control form-control-user" 
                                            placeholder="Phone Number" required>
                                    </div>
                                </div>

                              
        
                              
                                <div class="form-group">
                                    <input type='email' id='email' name='email' class="form-control form-control-user" 
                                        placeholder="Email Address" required>
                                </div>

                                <div class="form-group row">
                                    <div id="div_country" class="col-sm-6 mb-3 mb-sm-0">

                                    <select placeholder="country" id='country' name='country' class="form-select  form-control-user"  required>
                                        <option value='' disabled selected>Select Country</option>
                                         <option value='India'>India</option>
                                    </select>
                                    </div>

                                    <div class="col-sm-6">
                                    <select id='state' name='state' class="form-select  form-control-user"   required>
                                         <option value='' disabled selected>Select State</option>
                                         <option value='Andhra Pradesh'>Andhra Pradesh</option>
                                         <option value='Arunachal Pradesh'>Arunachal Pradesh</option>
                                         <option value='Assam'>Assam</option>
                                         <option value='Bihar'>Bihar</option>
                                         <option value='Chhattisgarh'>Chhattisgarh</option>
                                         <option value='Delhi'>Delhi</option>
                                         <option value='Goa'>Goa</option>
                                         <option value='Gujarat'>Gujarat</option>
                                         <option value='Haryana'>Haryana</option>
                                         <option value='Jammu & Kashmir'>Jammu & Kashmir</option>
                                         <option value='Jharkhand'>Jharkhand</option>
                                         <option value='Karnataka'>Karnataka</option>
                                         <option value='Kerala'>Kerala</option>
                                         <option value='Ladakh'>Ladakh</option>
                                         <option value='Madhya Pradesh'>Madhya Pradesh</option>
                                         <option value='Maharashtra'>Maharashtra</option>
                                         <option value='Manipur'>Manipur</option>
                                         <option value='Meghalaya'>Meghalaya</option>
                                         <option value='Mizoram'>Mizoram</option>
                                         <option value='Nagaland'>Nagaland</option>
                                         <option value='Odisha'>Odisha</option>
                                         <option value='Puducherry'>Puducherry</option>
                                         <option value='Punjab'>Punjab</option>
                                         <option value='Rajasthan'>Rajasthan</option>
                                         <option value='Sikkim'>Sikkim</option>
                                         <option value='Tamil Nadu'>Tamil Nadu</option>
                                         <option value='Telangana'>Telangana</option>
                                         <option value='Tripura'>Tripura</option>
                                         <option value='Uttarakhand'>Uttarakhand</option>
                                         <option value='Uttar Pradesh'>Uttar Pradesh</option>
                                         <option value='West Bengal'>West Bengal</option>
                                         <!-- Add other states here -->
                                     </select>
                                        
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <input type='text' id='city' name='city' class=" form-control form-control-user" 
                                        placeholder="Enter City" required>
                                </div>
                                
                                <div class="form-group row">
                                    <div id="div_country" class="col-sm-6 mb-3 mb-sm-0">
                                    <select class="form-select  form-control-user"  id='product' name='product' required onchange="updateModelDropdown()" >
                                          <option value='' disabled selected>Select Product</option>
                                          <option value='Tennis Ball'>Tennis Ball</option>
                                          <option value='Football'>Footballl / Volleyball</option>
                                          <!-- Add other products here -->
                                      </select>
                                    
                                    </div>

                                    <div class="col-sm-6">
                                    <select class="form-select  form-control-user "  id='model' name='model' required>
                                        <option value='' disabled selected>Select Model</option>

                                        <!-- Add other products here -->
                                    </select>
                                        
                                    </div>
                                </div>

                                <div class="btn-block">
                                 <button class="btn btn-primary btn-user btn-block" type="submit" name="AddCode">Validation</button>
                                </div>
                                <div id="message"><?php echo $msg; ?></div> 
                          </form>

                          <!-- End Varification form -->
                            <hr>

                            
                           

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script>
       function showPopup() {
    document.getElementById('popupModal').style.display = 'block';
}

function closePopup() {
    document.getElementById('popupModal').style.display = 'none';
}

// Automatically show the popup when the page loads
window.onload = function() {
    showPopup();
};

    </script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        function hideMessage() {
            // Get the message element
            var messageElement = document.getElementById('message');
            
            // Hide the message after 3 seconds
            setTimeout(function() {
                messageElement.style.display = 'none';
            }, 3000);
        }

        // Call the hideMessage function when the page loads
        window.onload = hideMessage;
    </script>
    <script>
    function updateModelDropdown() {
        var productDropdown = document.getElementById('product');
        var modelDropdown = document.getElementById('model');
        var selectedProduct = productDropdown.value;
        modelDropdown.innerHTML = ''; // Clear existing options
        if (selectedProduct === 'Tennis Ball') {
            // Add options for Tennis Balls
            var tennisModels = ['Aerospex','Bouncer', 'Famex Premium', 'Famex Solid (PL)', 'Famex Star', 'Glorex', 'Glorex Premium', 'Gold', 'Match', 'Playmaster', 'Practice', 'Practice Premium', 'Practice Star','Raunak', 'Super ','Super - E', 'Super Eleven', 'Swastik', 'Thrill', 'Tournament', 'Turf','Ultimate'];
            for (var i = 0; i < tennisModels.length; i++) {
                var option = document.createElement('option');
                option.text = tennisModels[i];
                option.value = tennisModels[i];
                modelDropdown.add(option);
            }
        } else if (selectedProduct === 'Football') {
            // Add options for Football
            var footballModels = ['Aerospex', 'Bullet', 'Famex', 'Famex Super', 'Five Star', 'Funball', 'Glorex(PU)', 'Jyoti', 'Kiwikshot', 'Lokpriya', 'Practice Top', 'Ruby', 'Ruby 18 Panel', 'Super', 'Winsrex'];
            for (var j = 0; j < footballModels.length; j++) {
                var option2 = document.createElement('option');
                option2.text = footballModels[j];
                option2.value = footballModels[j];
                modelDropdown.add(option2);
            }
            
        }
       
        
        
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>