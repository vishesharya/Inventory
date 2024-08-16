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
    $signature_file_path = null;
}

$supervisors_sql = "SELECT * FROM supervisors WHERE status = 1 LIMIT 1";
$supervisors_result = $con->query($supervisors_sql);

if ($supervisors_result->num_rows > 0) {
    $supervisors = $supervisors_result->fetch_assoc();
    $signature_supervisors_path = $supervisors['signature'];
} else {
    $signature_supervisors_path = null;
}

// Fetch all added products corresponding to the last submitted entry's challan_no
$challan_no = $entry['challan_no'];
$product_query = "SELECT * FROM football_received WHERE challan_no = '$challan_no'";
$product_result = mysqli_query($con, $product_query);

// Fetch the stitcher details
$stitcher_query = "SELECT * FROM stitcher WHERE stitcher_name = '{$entry['stitcher_name']}' LIMIT 1";
$stitcher_result = mysqli_query($con, $stitcher_query);
$stitcher_row = mysqli_fetch_assoc($stitcher_result);

// Fetch the date and time
$date_and_time_query = "SELECT date_and_time FROM football_received WHERE challan_no = '$challan_no' LIMIT 1";
$date_and_time_result = mysqli_query($con, $date_and_time_query);
$date_and_time_row = mysqli_fetch_assoc($date_and_time_result);
$date_and_time = $date_and_time_row['date_and_time'];

// Define the path to the signature
$signature_path = 'uploads/signatures/' . $stitcher_row['signature'];
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
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 1000px;
            background: #fff;
        }
        .heading {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            font-size: 24px;
        }
        .invoice-header {
            margin-bottom: 20px;
        }
        .table {
            margin-top: 0;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            color: #555;
        }
        .footer div {
            width: 30%;
            text-align: center;
        }
        .footer img {
            width: 180px;
            height: auto;
        }
        .print-btn {
            margin-top: 20px;
            text-align: center;
        }
        @media print {
            .print-btn {
                display: none !important;
            }
            .container {
                box-shadow: none;
                margin: 0;
                padding: 0;
            }
        }
        p {
            line-height: 1.5;
        }
        .stitcher_bold {
            font-weight: bold;
        }
        .issue_heading {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-header">
            <div>
                <p class="issue_heading">FOOTBALLS RECEIVING SLIP</p>
                <h2 class="heading">KHANNA SPORTS INDUSTRIES PVT. LTD</h2>
                <p class="heading">A-7, Sports Complex Delhi Road Meerut Uttar Pradesh 250002</p>
                <p class="heading">Contact: 8449441387, 98378427750 &nbsp; GST: 09AAACK9669A1ZD</p>
            </div>
            <div id="head_details" style="display: flex; justify-content: space-between; margin-top: 20px;">
                <div>
                    <p class="stitcher_bold">Stitcher: <?php echo htmlspecialchars($entry['stitcher_name']); ?></p>
                    <p>Stitcher Contact: <?php echo htmlspecialchars($stitcher_row['stitcher_contact']); ?></p>
                    <p>Stitcher Aadhaar: <?php echo htmlspecialchars($stitcher_row['stitcher_aadhar']); ?></p>
                    <p>Stitcher PAN: <?php echo htmlspecialchars($stitcher_row['stitcher_pan']); ?></p>
                    <p>Stitcher Address: <?php echo htmlspecialchars($stitcher_row['stitcher_address']); ?></p>
                </div>
                <div>
                    <p>Challan No: <?php echo htmlspecialchars($entry['challan_no']); ?></p>
                    <p>Date: <?php echo date('d-m-Y', strtotime($date_and_time)); ?></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                // Initialize totals
                $total_S_Ist_C_Ist = 0;
                $total_S_Ist_C_IInd = 0;
                $total_S_IInd_C_Ist = 0;
                $total_S_IInd_C_IInd = 0;
                $total_total = 0;
                ?>
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
                        <?php while ($product = mysqli_fetch_assoc($product_result)) : 
                            // Accumulate totals
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
                        <?php endwhile; ?>
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
            </div>
        </div>
        <div class="footer">
            <div class="receiver-signature">Supervisor Signature
                <br>
                <?php if ($signature_supervisors_path): ?>
                    <img src="<?= htmlspecialchars($signature_supervisors_path) ?>" alt="Signature">
                <?php else: ?>
                    <p>No signature available.</p>
                <?php endif; ?>
            </div>
            <div class="middle-signature">Guard Signature
                <br>
                <?php if ($signature_file_path): ?>
                    <img src="<?= htmlspecialchars($signature_file_path) ?>" alt="Signature">
                <?php else: ?>
                    <p>No signature available.</p>
                <?php endif; ?>
            </div>
            <div class="issuer-signature">Stitcher Signature
                <br>
                <?php if (!empty($signature_path)): ?>
                    <img src="<?= htmlspecialchars($signature_path) ?>" alt="Signature">
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
