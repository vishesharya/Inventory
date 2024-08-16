<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');

// Fetch stitcher names from the database
$stitcher_query = "SELECT DISTINCT stitcher_name FROM football_received ORDER BY stitcher_name ASC"; 
$stitcher_result = mysqli_query($con, $stitcher_query);

$challan_no = isset($_POST['challan_no']) ? $_POST['challan_no'] : "";

// Check if 'challan_no' is set in session
if (isset($_SESSION['challan_no'])) { 
    $challan_no = $_SESSION['challan_no'];
}

// Initialize $result variable
$result = null;

$total_S_Ist_C_Ist = 0;
$total_S_Ist_C_IInd = 0;
$total_S_IInd_C_Ist = 0;
$total_S_IInd_C_IInd = 0;
$total_total = 0;

// Fetch guard signature
$guards_signature_query = "SELECT signature FROM guards WHERE status = 0 LIMIT 1";
$guards_signature_result = mysqli_query($con, $guards_signature_query);
$guards_signature = mysqli_fetch_assoc($guards_signature_result);
$guard_signature_filename = $guards_signature['signature']; 
$guard_signature_path = 'uploads/signatures/' . $guard_signature_filename;

// Check if 'View' button is clicked
if (isset($_POST['view_entries'])) {
    // Get selected stitcher
    $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : '';
    if (!empty($stitcher_name)) {

        $stitcher_signature_query = "SELECT signature FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
        $stitcher_signature_result = mysqli_query($con, $stitcher_signature_query);
        $stitcher_signature = mysqli_fetch_assoc($stitcher_signature_result);

        $stitcher_contact_query = "SELECT stitcher_contact FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
        $stitcher_contact_result = mysqli_query($con, $stitcher_contact_query);
        $stitcher_contact_row = mysqli_fetch_assoc($stitcher_contact_result);
        $stitcher_contact = $stitcher_contact_row['stitcher_contact'];

        $stitcher_aadhar_query = "SELECT stitcher_aadhar FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
        $stitcher_aadhar_result = mysqli_query($con, $stitcher_aadhar_query);
        $stitcher_aadhar_row = mysqli_fetch_assoc($stitcher_aadhar_result);
        $stitcher_aadhar = $stitcher_aadhar_row['stitcher_aadhar'];

        $stitcher_pan_query = "SELECT stitcher_pan FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
        $stitcher_pan_result = mysqli_query($con, $stitcher_pan_query);
        $stitcher_pan_row = mysqli_fetch_assoc($stitcher_pan_result);
        $stitcher_pan = $stitcher_pan_row['stitcher_pan'];

        $stitcher_address_query = "SELECT stitcher_address FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
        $stitcher_address_result = mysqli_query($con, $stitcher_address_query);
        $stitcher_address_row = mysqli_fetch_assoc($stitcher_address_result);
        $stitcher_address = $stitcher_address_row['stitcher_address'];

        // Fetch the date and time 
        $date_and_time_query = "SELECT date_and_time FROM football_received WHERE challan_no = '$challan_no' LIMIT 1";
        $date_and_time_result = mysqli_query($con, $date_and_time_query);
        $date_and_time_row = mysqli_fetch_assoc($date_and_time_result);
        $date_and_time = $date_and_time_row['date_and_time'];

        $stitcher_signature_filename = $stitcher_signature['signature']; // Get the stitcher signature filename
        $stitcher_signature_path = 'uploads/signatures/' . $stitcher_signature_filename;
    }
 
    // Get selected challan number
    $selected_challan = isset($_POST['challan_no']) ? mysqli_real_escape_string($con, $_POST['challan_no']) : '';

    // Retrieve entries from database based on selected stitcher and/or challan number
    // Retrieve entries from database based on selected stitcher and/or challan number and/or date range
    if (!empty($selected_challan)) {
        // Fetch entries for the selected challan number
        $query = "SELECT * FROM football_received WHERE challan_no = '$selected_challan'";
        $result = mysqli_query($con, $query);
    } elseif (!empty($stitcher_name)) {
        if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
            // Get selected date range
            $start_date = mysqli_real_escape_string($con, $_POST['from_date']);
            $end_date = mysqli_real_escape_string($con, $_POST['to_date']);
            // Fetch entries within the selected date range for the selected stitcher
            $query = "SELECT * FROM football_received WHERE stitcher_name = '$stitcher_name' AND date_and_time BETWEEN '$start_date' AND '$end_date'";
        } else {
            // Fetch all entries for the selected stitcher without considering date range
            $query = "SELECT * FROM football_received WHERE stitcher_name = '$stitcher_name'";
        }
        $result = mysqli_query($con, $query);
    } elseif (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
        // Get selected date range
        $start_date = mysqli_real_escape_string($con, $_POST['from_date']);
        $end_date = mysqli_real_escape_string($con, $_POST['to_date']);
        // Fetch entries within the selected date range
        $query = "SELECT * FROM football_received WHERE date_and_time BETWEEN '$start_date' AND '$end_date'";
        $result = mysqli_query($con, $query);
    } else {
        // If no stitcher is selected and no other filters are applied, fetch all entries from the database
        $query = "SELECT * FROM football_received";
        $result = mysqli_query($con, $query);
    }
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
        /* Your existing styles */
        body {
            background-color: #f8f9fc;
            font-family: Arial, sans-serif;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .btn-group {
            margin-top: 1.5rem;
            justify-content: center;
        }
        
        #printbtn {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .error-input {
            border: 1px solid red;
        }
        .date_input {
            display: flex;
        }
        #input_field {
            margin: 0.1rem;
        }
        @media print {
            #form {
                display: none;
            }
        }
        .container_slip {
            margin-top: 50px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .heading {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            line-height: 5px;
        }
        .table {
            margin-top: 0px;
        }
        .signature {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            gap: 9rem;
            align-items: flex-end;
            color: #555;
        }
        .receiver-signature {
            text-align: right;
        }
        .issuer-signature {
            text-align: left;
        }
        .middle-signature {
            text-align: center;
        }
        .print-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        #head_details {
            display: flex;
            margin-top: 0px;
            padding-top: 0px;
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
        }
        .invoice-header {
            line-height: 7px;
        }
        .invoice-item {
            text-align: left;
        }
        .row-head {
            text-align: left;
            font-weight: bold;
        }
        .row-data {
            text-align: left;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 8.2rem;
            align-items: flex-end;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <div id="head_details">
            <div class="invoice-header">
                <h2>KITS ISSUE DETAILS</h2>
            </div>
            <div class="invoice-header">
                <p>Date: <?php echo date("d-m-Y"); ?></p>
            </div>
        </div>

        <!-- Form for filter inputs -->
        <form method="POST" action="">
            <div class="form-group">
                <label for="stitcher_name">Select Stitcher:</label>
                <select id="stitcher_name" name="stitcher_name" class="form-control">
                    <option value="">Select Stitcher</option>
                    <?php while ($row = mysqli_fetch_assoc($stitcher_result)) { ?>
                        <option value="<?php echo $row['stitcher_name']; ?>"><?php echo $row['stitcher_name']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label for="challan_no">Challan Number:</label>
                <input type="text" id="challan_no" name="challan_no" class="form-control" value="<?php echo htmlspecialchars($challan_no); ?>">
            </div>
            <div class="form-group date_input">
                <label for="from_date">From Date:</label>
                <input type="date" id="from_date" name="from_date" class="form-control">
                <label for="to_date">To Date:</label>
                <input type="date" id="to_date" name="to_date" class="form-control">
            </div>
            <div class="btn-group">
                <button type="submit" name="view_entries" class="btn btn-primary">View Entries</button>
            </div>
        </form>

        <!-- Display entries -->
        <?php if ($result && mysqli_num_rows($result) > 0) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Stitcher Name</th>
                        <th>Challan Number</th>
                        <th>Date and Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['stitcher_name']; ?></td>
                            <td><?php echo $row['challan_no']; ?></td>
                            <td><?php echo $row['date_and_time']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <p>No entries found.</p>
        <?php } ?>

        <!-- Display guard signature -->
        <div class="signature">
            <div class="middle-signature">
                <p>Guard Signature:</p>
                <?php if (!empty($guard_signature_filename)) { ?>
                    <img src="<?php echo $guard_signature_path; ?>" alt="Guard Signature" width="175" height="50">
                <?php } else { ?>
                    <p>No signature available</p>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
