<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Fetch stitcher names from the database
$stitcher_query = "SELECT DISTINCT stitcher_name FROM kits_issue ORDER BY stitcher_name ASC"; 
$stitcher_result = mysqli_query($con, $stitcher_query);
$challan_no = isset($_POST['challan_no']) ? $_POST['challan_no'] : "";
$stitcher_contact = isset($_POST['stitcher_contact']) ? $_POST['stitcher_contact'] : "";
$stitcher_name = isset($_POST['stitcher_name']) ? $_POST['stitcher_name'] : "";
$date_and_time = isset($_POST['date_and_time']) ? $_POST['date_and_time'] : "";
$product_result = isset($_POST['product_result']) ? $_POST['product_result'] : "";

// Fetch the stitcher name for the invoice
if (!empty($stitcher_name)) {
    $stitcher_query = "SELECT * FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
    $stitcher_result = mysqli_query($con, $stitcher_query);
    $stitcher_row = mysqli_fetch_assoc($stitcher_result);
    $stitcher_address = $stitcher_row['stitcher_address'];
    $stitcher_aadhar = $stitcher_row['stitcher_aadhar'];
    $stitcher_pan = $stitcher_row['stitcher_pan'];
}

// Check if 'challan_no' is set in session
if (isset($_SESSION['challan_no'])) {
    $challan_no = $_SESSION['challan_no'];
}

// Initialize $result variable
$result = null;

// Check if 'View' button is clicked
if (isset($_POST['view_entries'])) {
    // Get selected stitcher
    $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : '';
   
    if (!empty($stitcher_name)) {
        $stitcher_contact_query = "SELECT stitcher_contact FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
        $stitcher_contact_result = mysqli_query($con, $stitcher_contact_query);
        $stitcher_contact_row = mysqli_fetch_assoc($stitcher_contact_result);
        $stitcher_contact = $stitcher_contact_row['stitcher_contact'];
    }
    // Initialize conditions
    $conditions = "";

    // Add stitcher condition
    if (!empty($stitcher_name)) {
        $conditions .= " WHERE stitcher_name = '$stitcher_name'";
    }

    // Add date range condition
    if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
        // Get selected date range
        $start_date = mysqli_real_escape_string($con, $_POST['from_date']);
        $end_date = mysqli_real_escape_string($con, $_POST['to_date']);

        // Add AND or WHERE depending on whether previous conditions exist
        $conditions .= ($conditions == "") ? " WHERE" : " AND";
        $conditions .= " date_and_time BETWEEN '$start_date' AND '$end_date'";
    }

    // Add challan number condition
    if (!empty($_POST['challan_no'])) {
        // Get selected challan number
        $challan_no = mysqli_real_escape_string($con, $_POST['challan_no']);
        
        // Add AND or WHERE depending on whether previous conditions exist
        $conditions .= ($conditions == "") ? " WHERE" : " AND";
        $conditions .= " challan_no = '$challan_no'";
    }

    // Construct the final query
    $query = "SELECT * FROM kits_issue $conditions";
    $result = mysqli_query($con, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KITS ISSUE DETAILS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Your CSS styles */
    </style>
</head>
<body>
    <?php include('include/nav.php'); ?>
    <div class="container-fluid mt-5">
        <div id="form" class="row justify-content-center">
            <h1 class="h4 text-center mb-4">KITS ISSUE SLIP</h1>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <!-- Your form -->
                        <form method="post" action="">
                            <!-- Your form elements -->
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($_POST['view_entries']) && mysqli_num_rows($result) > 0): ?>
            <div class="container_slip">
                <!-- Your HTML structure for displaying kits issue details -->
            </div>
        <?php endif; ?>

        <!-- JavaScript code for fetching challan numbers based on selected stitcher and date range -->
        <script>
            // Your JavaScript code
        </script>
    </div>
</body>
</html>
