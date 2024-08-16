<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

// Fetch the last submitted entry
$query = "SELECT * FROM football_received ORDER BY id DESC LIMIT 1";
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
    $guard_name = "No default guard set";
    $signature_file_path = null;
}

$supervisors_sql = "SELECT * FROM supervisors WHERE status = 1 LIMIT 1";
$supervisors_result = $con->query($supervisors_sql);

if ($supervisors_result->num_rows > 0) {
    $supervisors = $supervisors_result->fetch_assoc();
    $signature_supervisors_path = $supervisors['signature'];
} else {
    $supervisors_name = "No default guard set";
    $signature_supervisors_path = null;
}

$challan_no = $entry['challan_no'];
$product_query = "SELECT * FROM football_received WHERE challan_no = '$challan_no'";
$product_result = mysqli_query($con, $product_query);

$stitcher_query = "SELECT stitcher_name FROM football_received WHERE challan_no = '$challan_no' LIMIT 1";
$stitcher_result = mysqli_query($con, $stitcher_query);
$stitcher_row = mysqli_fetch_assoc($stitcher_result);
$stitcher_name = $stitcher_row['stitcher_name'];

$date_and_time_query = "SELECT date_and_time FROM football_received WHERE challan_no = '$challan_no' LIMIT 1";
$date_and_time_result = mysqli_query($con, $date_and_time_query);
$date_and_time_row = mysqli_fetch_assoc($date_and_time_result);
$date_and_time = $date_and_time_row['date_and_time'];

$stitcher_contact_query = "SELECT stitcher_contact FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
$stitcher_contact_result = mysqli_query($con, $stitcher_contact_query);
$stitcher_contact_row = mysqli_fetch_assoc($stitcher_contact_result);
$stitcher_contact = $stitcher_contact_row['stitcher_contact'];

$stitcher_query = "SELECT * FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
$stitcher_result = mysqli_query($con, $stitcher_query);
$stitcher_row = mysqli_fetch_assoc($stitcher_result);
$stitcher_address = $stitcher_row['stitcher_address'];
$stitcher_aadhar = $stitcher_row['stitcher_aadhar'];
$stitcher_pan = $stitcher_row['stitcher_pan'];
$signature_filename = $stitcher_row['signature'];

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
            color: #333;
        }
        .container {
            margin-top: 20px;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 0px 12px rgba(0, 0, 0, 0.2);
            background-color: #f9f9f9;
        }
        .heading {
            text-align: center;
            margin-bottom: 20px;
            color: #007bff;
        }
        .invoice-header {
            margin-bottom: 20px;
        }
        .table {
            margin-top: 20px;
        }
        .footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #555;
        }
        .signature-section {
            text-align: center;
            margin-top: 20px;
        }
        .signature-section img {
            max-width: 200px;
            height: auto;
            margin: 5px 0;
        }
        .signature-title {
            font-weight: bold;
            margin-top: 10px;
        }
        .print-btn {
            display: block;
            margin-top: 30px;
            text-align: center;
        }
        @media print {
            .print-btn {
                display: none !important;
            }
        }
        .stitcher_bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-header">
            <h1 class="heading">FOOTBALL RECEIVING SLIP</h1>
            <h2 class="heading">KHANNA SPORTS INDUSTRIES PVT. LTD</h2>
            <p class="text-center">A-7, Sports Complex Delhi Road Meerut Uttar Pradesh 250002</p>
            <p class="text-center">Contact : 8449441387, 98378427750 | GST : 09AAACK9669A1ZD</p>
        </div>
        <div id="head_details" class="d-flex justify-content-between mb-4">
            <div>
                <p class="stitcher_bold">Stitcher: <?php echo $stitcher_name; ?></p>
                <p>Stitcher Contact: <?php echo $stitcher_contact; ?></p>
                <p>Stitcher Aadhaar: <?php echo $stitcher_aadhar; ?></p>
                <p>Stitcher PAN: <?php echo $stitcher_pan; ?></p>
                <p>Stitcher Address: <?php echo $stitcher_address; ?></p>
            </div>
            <div>
                <p><br><br>Challan No: <?php echo $entry['challan_no']; ?></p>
                <p>Date: <?php echo date('d-m-Y', strtotime($date_and_time)); ?></p>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product Base</th>
                    <th>Product Color</th>
                    <th>Stitcher Ist Company Ist</th>
                    <th>Stitcher Ist Company IInd</th>
                    <th>Stitcher IInd Company Ist</th>
                    <th>Stitcher IInd Company IInd</th>
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

                while ($product = mysqli_fetch_assoc($product_result)) {
                    $total_S_Ist_C_Ist += $product['S_Ist_C_Ist'];
                    $total_S_Ist_C_IInd += $product['S_Ist_C_IInd'];
                    $total_S_IInd_C_Ist += $product['S_IInd_C_Ist'];
                    $total_S_IInd_C_IInd += $product['S_IInd_C_IInd'];
                    $total_total += $product['total'];
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_base']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_color']); ?></td>
                        <td><?php echo htmlspecialchars($product['S_Ist_C_Ist']); ?></td>
                        <td><?php echo htmlspecialchars($product['S_Ist_C_IInd']); ?></td>
                        <td><?php echo htmlspecialchars($product['S_IInd_C_Ist']); ?></td>
                        <td><?php echo htmlspecialchars($product['S_IInd_C_IInd']); ?></td>
                        <td><?php echo htmlspecialchars($product['total']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total</td>
                    <td><?php echo htmlspecialchars($total_S_Ist_C_Ist); ?></td>
                    <td><?php echo htmlspecialchars($total_S_Ist_C_IInd); ?></td>
                    <td><?php echo htmlspecialchars($total_S_IInd_C_Ist); ?></td>
                    <td><?php echo htmlspecialchars($total_S_IInd_C_IInd); ?></td>
                    <td><?php echo htmlspecialchars($total_total); ?></td>
                </tr>
            </tfoot>
        </table>
        <div class="signature-section">
            <div>
                <p class="signature-title">Supervisor Signature</p>
                <?php if ($signature_supervisors_path): ?>
                    <img src="<?= htmlspecialchars($signature_supervisors_path) ?>" alt="Signature">
                <?php else: ?>
                    <p>No signature available.</p>
                <?php endif; ?>
            </div>
            <div>
                <p class="signature-title">Guard Signature</p>
                <?php if ($signature_file_path): ?>
                    <img src="<?= htmlspecialchars($signature_file_path) ?>" alt="Signature">
                <?php else: ?>
                    <p>No signature available.</p>
                <?php endif; ?>
            </div>
            <div>
                <p class="signature-title">Stitcher Signature</p>
                <?php if (!empty($signature_filename)): ?>
                    <img src="<?php echo htmlspecialchars($signature_path); ?>" alt="Signature">
                <?php else: ?>
                    No signature available
                <?php endif; ?>
            </div>
        </div>
        <div class="print-btn">
            <button onclick="window.print()" class="btn btn-primary">Print</button>
        </div>
    </div>
</body>
</html>
