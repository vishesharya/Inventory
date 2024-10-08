<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
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

// Fetch the last submitted entry
$query = "SELECT * FROM print_received ORDER BY id DESC LIMIT 1";
$result = mysqli_query($con, $query);
$entry = mysqli_fetch_assoc($result);

if (!$entry) {
    echo "No entries found.";
    exit();
} 

// Fetch all added products corresponding to the last submitted entry's challan_no
$challan_no = $entry['challan_no'];
$product_query = "SELECT * FROM print_received WHERE challan_no = '$challan_no'";
$product_result = mysqli_query($con, $product_query);

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

// Fetch the stitcher contact for the invoice
$stitcher_contact_query = "SELECT stitcher_contact FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
$stitcher_contact_result = mysqli_query($con, $stitcher_contact_query);
$stitcher_contact_row = mysqli_fetch_assoc($stitcher_contact_result);
$stitcher_contact = $stitcher_contact_row['stitcher_contact'];

?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khanna Sports Kits Received Slip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @page {
            size: A4;
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 30px;

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
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #555;
        }
        .signature-box {
            flex: 1;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .signature-box img {
            width: 150px;
            height: 50px;
            margin-top: 5px;
        }
           
        .print-btn {
            display: block;
            margin-top: 20px;
            text-align: center;
        }
        #head_details{
            display: flex;
            margin-top: 0px;
            padding-top: 0px;
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
        }
        @media print {
            .print-btn {
                display: none !important;
            }
        }
        p{
            line-height: 7px;
        }
        .stitcher_bold{
            font-weight: bold;
        }
        .issue_heading{
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-header">
        <div>
                <p class="issue_heading" >KITS RECEIVED SLIP (WITHOUT PRINT)</p>
                <hr>
                <h2 class="heading">KHANNA SPORTS INDUSTRIES PVT. LTD</h2>
                <p class="heading"> A-7, Sports Complex Delhi Road Meerut Uttar Pradesh 250002</p>
                <p class="heading">Contact : 8449441387,98378427750 &nbsp;  GST : 09AAACK9669A1ZD </p>
            </div>
            <div id="head_details">
            <div>
                    <p class="stitcher_bold" >Stitcher : <?php echo $stitcher_name; ?></p>
                    <p>Stitcher Contact : <?php echo $stitcher_contact; ?></p>
                    <p>Stitcher Aadhar : <?php echo $stitcher_aadhar; ?></p>
                    <p>Stitcher Pan : <?php echo $stitcher_pan; ?></p>
                    <p>Stitcher Address : <?php echo $stitcher_address; ?></p>
                </div>
                <div>
                <p><br/><br/>Challan No : <?php echo $entry['challan_no']; ?></p>
                <p>Date : <?php echo date("d-m-Y"); ?></p>
                </div>
               
                
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Product Base</th>
                            <th>Product Color</th>
                            <th>Received Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_quantity = 0;
                        
                        while ($product = mysqli_fetch_assoc($product_result)) : 
                            $total_quantity += $product['received_quantity'];
                          
                           
                            ?>
                            <tr>
                                <td><?php echo $product['product_name']; ?></td>
                                <td><?php echo $product['product_base']; ?></td>
                                <td><?php echo $product['product_color']; ?></td>
                                <td><?php echo $product['received_quantity']; ?></td>
                      
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total</td>
                            <td><?php echo $total_quantity; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="footer">
          <div class="signature-box">
                <span>Supervisor Signature</span>
                <?php if ($signature_supervisors_path): ?>
                    <img src="<?= htmlspecialchars($signature_supervisors_path) ?>" alt="Supervisor Signature">
                <?php else: ?>
                    <p>No signature available.</p>
                <?php endif; ?>
            </div>
            <div class="signature-box">
                <span>Guard Signature</span>
                <?php if ($signature_file_path): ?>
                    <img src="<?= htmlspecialchars($signature_file_path) ?>" class="gaurd" alt="Guard Signature" style="width: 200px; height: 75px;">
                <?php else: ?>
                    <p>No signature available.</p>
                <?php endif; ?>
            </div>
          
            <div class="signature-box">
                <span>Stitcher Signature</span>
                <?php if (!empty($signature_filename)): ?>
                    <img src="<?php echo htmlspecialchars($signature_path); ?>" alt="Stitcher Signature">
                <?php else: ?>
                    <p>No signature available</p>
                <?php endif; ?> 
            </div>
        </div>
        <div class="print-btn">
            <button onclick="window.print()" class="btn btn-primary">Print</button>
        </div>
    </div>
</body>
</html>
