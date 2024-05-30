<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navbar with PHP</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
  <style>
    /* Custom styles for navbar */
    .navbar {
      background-color: #007bff; /* Blue background */
    }

    .navbar-brand,
    .navbar-nav .nav-link {
      color: #fff; /* White text */
    }

    .navbar-nav {
      margin: 0 auto; /* Center the navigation items */
    }

    .navbar-nav .dropdown-menu {
      background-color: #007bff; /* Blue background for dropdown */
    }

    .navbar-nav .dropdown-menu .dropdown-item {
      color: #fff; /* White text for dropdown items */
    }

    .navbar-nav .dropdown-menu .dropdown-item:hover {
      background-color: #0056b3; /* Darker blue on hover */
    }

    .navbar-nav .nav-link:hover {
      color: #f8f9fa; /* Light text on hover */
    }

    /* Responsive styles */
    @media (max-width: 991px) {
      .navbar-nav .nav-item {
        padding: 10px 0;
      }
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container">
    <a class="navbar-brand" href="inventory.php">Go Back</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
     
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
