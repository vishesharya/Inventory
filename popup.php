<?php
    
// Define the expected token
$expected_token = "12345";

// Check if the token is present and valid
if (isset($_GET['access_token']) && $_GET['access_token'] === $expected_token) {
    
} else {
    // Invalid or missing token, redirect to home page
    header("Location: https://khannasports.in");
    exit();
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags and title -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Verify Your Khanna Product">
    <meta name="author" content="Khanna Sports">

    <title>Product Verification</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="new/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="new/css/sb-admin-2.css" rel="stylesheet">

    <style>
       /* Popup Modal */
.popup {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
}

.popup-content {
    background-color: white;
    margin: 10% auto; /* Adjusted margin */
    padding: 30px; /* Increased padding */
    border-radius: 10px; /* Increased border radius */
    width: 90%; /* Set width to 90% to accommodate smaller screens */
    max-width: 800px; /* Increased max-width */
    display: flex;
    flex-wrap: wrap;
    position: relative;
    box-sizing: border-box; /* Ensure padding is included in width */
}

/* Close Button */
.close {
    position: absolute;
    top: 5px;
    right: 5px;
    font-size: 24px;
    cursor: pointer;
}

.popup-left,
.popup-right {
    flex: 1;
    min-width: 300px; /* Increased minimum width */
    box-sizing: border-box; /* Ensure padding is included in width */
}

/* Image Section */
.popup-left img {
    width: 100%;
    height: auto;
    border-radius: 5px;
}

/* Text Section */
.popup-right {
    padding: 30px; /* Increased padding */
}

h2 {
    margin-top: 0;
}

.action-btn {
    margin-top: 20px;
    padding: 10px 20px;
    font-size: 17px;
    cursor: pointer;
}

/* Responsive Design */
@media (max-width: 768px) {
    .popup-content {
        flex-direction: column;
        width: 95%; /* Increase width on smaller screens */
        padding: 20px; /* Reduce padding for smaller screens */
    }

    .popup-left,
    .popup-right {
        min-width: 100%;
        padding: 0; /* Remove padding for full-width layout on small screens */
    }

    .popup-left img {
        width: 100%;
        height: auto;
    }
}

@media (max-width: 480px) {
    .popup-content {
        margin: 5% auto; /* Reduce top margin for very small screens */
        padding: 15px; /* Further reduce padding */
    }

    .popup-left img {
        width: 100%;
        height: auto;
    }

    .popup-right {
        padding: 0px; /* Further reduce padding */
    }

    .action-btn {
        font-size: 14px; /* Adjust button font size for smaller screens */
        padding: 12px 20px; /* Adjust button padding */
    }
    
}

    </style>
</head>

<body class="bg-gradient-primary">

    <!-- Main content and form elements here -->
    <?php 
    $access_token = '12345';
    include('validation.php');    
    ?>
    <!-- Popup Modal -->
    <div id="popupModal" class="popup">
        <div class="popup-content">
        <span class="close" onclick="closePopup()">
                <img width="50px" src="https://cdn.shopify.com/s/files/1/0693/0399/2571/files/closeicon.png?v=1723538935" alt="Close">
            </span>
            <div class="popup-left">
                <img src="./assets/images/follow.jpg" alt="Popup Image">
            </div>
            <div class="popup-right">
                <h3 class="fw-semibold">Follow Us On Instagram</h3>
                <p>Follow us on Instagram <b>"khannasports"</b> and join our community today!</p>

               <ul>
                <li>
                    <p>
                     DM us on Instagram after you follow us.
                    </p>
                </li>
                <li>
                   <p>
                   We will provide you with a coupon code worth 250/-
                   </p>
                </li>
               </ul>
               <p class="fw-bold">ITS THAT SIMPLE!</p>
                <a href="https://www.instagram.com/khannasports" class="btn btn-outline-primary btn-lg action-btn">Follow Us</a>
            </div>
        </div>
    </div>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script>
    function showPopup() {
        document.getElementById('popupModal').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('popupModal').style.display = 'none';
        window.location.href = "https://khannasports.co.in/validation.php?access_token=12345";
    }

    // Automatically show the popup when the page loads
    window.onload = function() {
        showPopup();
        hideMessage(); // Call hideMessage here if you want to hide messages after a certain time
    };

    function hideMessage() {
        // Get the message element
        var messageElement = document.getElementById('message');

        // Hide the message after 3 seconds
        setTimeout(function() {
            messageElement.style.display = 'none';
        }, 3000);
    }
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>


</html>
