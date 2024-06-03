<?php
session_start();
include_once 'include/connection.php';

// Fetch the last submitted entry
$query = "SELECT * FROM kits_received ORDER BY id DESC LIMIT 1";
$result = mysqli_query($con, $query);
$entry = mysqli_fetch_assoc($result);

if (!$entry) {
    echo "No entries found.";
    exit();
} 

// Fetch all added products corresponding to the last submitted entry's challan_no
$challan_no = $entry['challan_no'];
$product_query = "SELECT * FROM kits_received WHERE challan_no = '$challan_no'";
$product_result = mysqli_query($con, $product_query);

// Fetch the labour name for the invoice
$labour_query = "SELECT labour_name FROM kits_received WHERE challan_no = '$challan_no' LIMIT 1";
$labour_result = mysqli_query($con, $labour_query);
$labour_row = mysqli_fetch_assoc($labour_result);
$labour_name = $labour_row['labour_name'];

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
            margin-top: 50px;
            background-color: #f8f9fc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .heading {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        .table {
            margin-top: 0px;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            gap: 8.2rem;
            align-items: flex-end;
            color: #555;
        }
        .receiver-signature,
        .issuer-signature {
            flex: 1;
            text-align: left;
        }
        .middle-signature {
            flex: 1;
            text-align: center;
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
        .issue_heading{
            text-align: center;
        }
        .heading {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
            line-height: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-header">
        <div>
                <p class="issue_heading" >KITS ISSUE SLIP</p>
                <hr>
                <h2 class="heading">KHANNA SPORTS INDUSTRIES PVT. LTD</h2>
                <p class="heading"> A-7, Sports Complex Delhi Road Meerut Uttar Pradesh 250002</p>
                <p class="heading">Contact : 8449441387,98378427750 &nbsp;  GST : 09AAACK9669A1ZD </p>
            </div>
            <div id="head_details">
                <div>
                <p> <b>labour : <?php echo $labour_name; ?></b></p>
              
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
                            <th>Ist Quantity</th>
                            <th>IInd Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_quantity_ist = 0;
                        $total_quantity_iind = 0;
                        while ($product = mysqli_fetch_assoc($product_result)) : 
                            $total_quantity_ist += $product['ist_quantity'];
                            $total_quantity_iind += $product['iind_quantity'];
                           
                            ?>
                            <tr>
                                <td><?php echo $product['product_name']; ?></td>
                                <td><?php echo $product['product_base']; ?></td>
                                <td><?php echo $product['product_color']; ?></td>
                                <td><?php echo $product['ist_quantity']; ?></td>
                                <td><?php echo $product['iind_quantity']; ?></td>
                      
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total</td>
                            <td><?php echo $total_quantity_ist; ?></td>
                            <td><?php echo $total_quantity_iind; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="footer">
            <div class="receiver-signature">Receiver Signature</div>
            <div class="middle-signature">Guard Signature</div>
            <div class="issuer-signature">Issuer Signature</div>
        </div>
        <div class="print-btn">
            <button onclick="window.print()" class="btn btn-primary">Print</button>
        </div>
    </div>
</body>
</html>
