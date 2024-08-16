<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

// Fetch the last submitted entry
$query = "SELECT * FROM  football_received ORDER BY id DESC LIMIT 1";
$result = mysqli_query($con, $query);
$entry = mysqli_fetch_assoc($result);

if (!$entry) {
    echo "No entries found.";
    exit();
}

$guard_sql = "SELECT * FROM guards WHERE status = 1 LIMIT 1";
$guard_result = $con->query($guard_sql);

if ($guard_result->num_rows > 0) {
    $guard = $guard_result->fetch_assoc();
    $signature_file_path = $guard['signature'];
} else {
    // Handle case where no guard has status = 1
    $guard_name = "No default guard set";
    $signature_file_path = null;
}

$supervisors_sql = "SELECT * FROM supervisors WHERE status = 1 LIMIT 1";
$supervisors_result = $con->query($supervisors_sql);

if ($supervisors_result->num_rows > 0) {
    $supervisors = $supervisors_result->fetch_assoc();
    $signature_supervisors_path = $supervisors['signature'];
} else {
    // Handle case where no guard has status = 1
    $supervisors_name = "No default guard set";
    $signature_supervisors_path = null;
}

// Fetch all added products corresponding to the last submitted entry's challan_no
$challan_no = $entry['challan_no'];
$product_query = "SELECT * FROM  football_received WHERE challan_no = '$challan_no'";
$product_result = mysqli_query($con, $product_query);

// Fetch the stitcher name for the invoice
$stitcher_query = "SELECT stitcher_name FROM  football_received WHERE challan_no = '$challan_no' LIMIT 1";
$stitcher_result = mysqli_query($con, $stitcher_query);
$stitcher_row = mysqli_fetch_assoc($stitcher_result);
$stitcher_name = $stitcher_row['stitcher_name'];



// Fetch the date and time 
$date_and_time_query = "SELECT date_and_time FROM football_received WHERE challan_no = '$challan_no' LIMIT 1";
$date_and_time_result = mysqli_query($con, $date_and_time_query);
$date_and_time_row = mysqli_fetch_assoc($date_and_time_result);
$date_and_time = $date_and_time_row['date_and_time'];

// Fetch the stitcher contact for the invoice
$stitcher_contact_query = "SELECT stitcher_contact FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
$stitcher_contact_result = mysqli_query($con, $stitcher_contact_query);
$stitcher_contact_row = mysqli_fetch_assoc($stitcher_contact_result);
$stitcher_contact = $stitcher_contact_row['stitcher_contact'];

// Fetch the stitcher name for the invoice
$stitcher_name = $entry['stitcher_name']; // Fetching stitcher name from the last submitted entry
$stitcher_query = "SELECT * FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
$stitcher_result = mysqli_query($con, $stitcher_query);
$stitcher_row = mysqli_fetch_assoc($stitcher_result);
$stitcher_address = $stitcher_row['stitcher_address'];
$stitcher_aadhar = $stitcher_row['stitcher_aadhar'];
$stitcher_pan = $stitcher_row['stitcher_pan'];
$signature_filename = $stitcher_row['signature']; // Get the signature filename

// Define the path to the signature
$signature_path = 'uploads/signatures/' . $signature_filename;


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khanna Sports Football Receiving Slip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .heading, .sub-heading, .address, .contact-details {
            text-align: center;
        }
        .table {
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
        }
        .signature-section {
            text-align: center;
            flex: 1;
        }
        .signature-section img {
            width: 150px;
            height: 50px;
        }
        @media print {
            .print-btn {
                display: none;
            }
            .container {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="text-center mb-4">
            <h4 class="mb-0">FOOTBALL RECEIVING SLIP</h4>
            <h1 class="heading mb-1">KHANNA SPORTS INDUSTRIES PVT. LTD</h1>
            <p class="address">A-7, Sports Complex Delhi Road, Meerut, Uttar Pradesh 250002</p>
            <p class="contact-details">Contact: 8449441387, 98378427750 | GST: 09AAACK9669A1ZD</p>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Stitcher:</strong> <?php echo $stitcher_name; ?></p>
                <p><strong>Contact:</strong> <?php echo $stitcher_contact; ?></p>
                <p><strong>Aadhaar:</strong> <?php echo $stitcher_aadhar; ?></p>
                <p><strong>PAN:</strong> <?php echo $stitcher_pan; ?></p>
                <p><strong>Address:</strong> <?php echo $stitcher_address; ?></p>
            </div>
            <div class="col-md-6 text-end">
                <p><strong>Challan No:</strong> <?php echo $entry['challan_no']; ?></p>
                <p><strong>Date:</strong> <?php echo date('d-m-Y', strtotime($date_and_time)); ?></p>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mt-4">
                <thead class="table-dark">
                    <tr>
                        <th>Product Name</th>
                        <th>Base</th>
                        <th>Color</th>
                        <th>S-Ist C-Ist</th>
                        <th>S-Ist C-IInd</th>
                        <th>S-IInd C-Ist</th>
                        <th>S-IInd C-IInd</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total_S_Ist_C_Ist = 0;
                    $total_S_Ist_C_IInd = 0;
                    $total_S_IInd_C_Ist = 0;
                    $total_S_IInd_C_IInd = 0;
                    $total_total = 0;

                    while ($product = mysqli_fetch_assoc($product_result)) : 
                        $total_S_Ist_C_Ist += $product['S_Ist_C_Ist'];
                        $total_S_Ist_C_IInd += $product['S_Ist_C_IInd'];
                        $total_S_IInd_C_Ist += $product['S_IInd_C_Ist'];
                        $total_S_IInd_C_IInd += $product['S_IInd_C_IInd'];
                        $total_total += $product['total'];
                    ?>
                        <tr>
                            <td><?php echo $product['product_name']; ?></td>
                            <td><?php echo $product['product_base']; ?></td>
                            <td><?php echo $product['product_color']; ?></td>
                            <td><?php echo $product['S_Ist_C_Ist']; ?></td>
                            <td><?php echo $product['S_Ist_C_IInd']; ?></td>
                            <td><?php echo $product['S_IInd_C_Ist']; ?></td>
                            <td><?php echo $product['S_IInd_C_IInd']; ?></td>
                            <td><?php echo $product['total']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot>
                    <tr class="table-dark">
                        <td colspan="3">Total</td>
                        <td><?php echo $total_S_Ist_C_Ist; ?></td>
                        <td><?php echo $total_S_Ist_C_IInd; ?></td>
                        <td><?php echo $total_S_IInd_C_Ist; ?></td>
                        <td><?php echo $total_S_IInd_C_IInd; ?></td>
                        <td><?php echo $total_total; ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="footer row">
            <div class="col-md-4 text-center">
                <p><strong>Supervisor Signature</strong></p>
                <?php if ($signature_supervisors_path): ?>
                    <img src="<?= htmlspecialchars($signature_supervisors_path) ?>" alt="Supervisor Signature">
                <?php else: ?>
                    <p>No signature available.</p>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-center">
                <p><strong>Guard Signature</strong></p>
                <?php if ($signature_file_path): ?>
                    <img src="<?= htmlspecialchars($signature_file_path) ?>" alt="Guard Signature">
                <?php else: ?>
                    <p>No signature available.</p>
                <?php endif; ?>
            </div>
            <div class="col-md-4 text-center">
                <p><strong>Stitcher Signature</strong></p>
                <?php if (!empty($signature_filename)): ?>
                    <img src="<?php echo htmlspecialchars($signature_path); ?>" alt="Stitcher Signature">
                <?php else: ?>
                    <p>No signature available</p>
                <?php endif; ?> 
            </div>
        </div>
        <div class="text-center mt-4">
            <button onclick="window.print()" class="btn btn-primary print-btn">Print</button>
        </div>
    </div>
</body>
</html>
