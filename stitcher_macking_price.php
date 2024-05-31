<?php
session_start();
include './include/connection.php';
include_once 'include/admin-main.php';
// Fetch stitcher names from the database
$stitcher_query = "SELECT DISTINCT stitcher_name FROM football_received ORDER BY stitcher_name ASC "; 
$stitcher_result = mysqli_query($con, $stitcher_query);

// Initialize $result variable
$result = null;

$total_ist_price = 0;
$total_iind_price = 0;
$total_thread_price = 0;
$total_ist_stitches = 0;
$total_iind_stitches = 0;
$total_all_quantity = 0;

// Check if 'View' button is clicked
if (isset($_POST['view_entries'])) { 
    // Get selected stitcher
    $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : '';

    // Get selected date range
    $start_date = isset($_POST['from_date']) ? mysqli_real_escape_string($con, $_POST['from_date']) : '';
    $end_date = isset($_POST['to_date']) ? mysqli_real_escape_string($con, $_POST['to_date']) : '';

    // Check if both stitcher and date range are selected
    if (!empty($stitcher_name) && !empty($start_date) && !empty($end_date)) {
        // Fetch entries within the selected date range for the selected stitcher
        $query = "SELECT * FROM football_received WHERE stitcher_name = '$stitcher_name' AND date_and_time BETWEEN '$start_date' AND '$end_date'";
        $result = mysqli_query($con, $query);

        
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

            // Fetch stitcher details including bank details
        $stitcher_details_query = "SELECT bank_name, bank_no, ifsc_code FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
        $stitcher_details_result = mysqli_query($con, $stitcher_details_query);
        $stitcher_details = mysqli_fetch_assoc($stitcher_details_result);


    
        
    } else {
        // If neither stitcher nor date range is selected, do not fetch any entries
        $result = null;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Receiving Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
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
        .table {
            margin-top: 2rem;
            border-collapse:collapse;
           
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
        .tablefoot{
            width: 300px;
        }
        tfoot {
               background-color: #f8f9fc; /* Light gray background */
               font-weight: bold; /* Make the text bold */
               color: #333; /* Dark text color */
        }

        tfoot td {
         padding: 10px; /* Add padding for better spacing */
        }
        @media print {
            #form {
                display: none;
            }
            .tablefoot{
            width: 400px;
        }
        }
        .heading {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
          
        }
        .issue_heading{
            text-align: center;
            justify-content: center;
        }
        .invoice-header{
            line-height: 7px;
        }
        #company_heading{
            line-height: 5px;
        }
        #company_heading1{
            line-height: 5px;
        }
        #stitcher_name{
            font-weight: bold;
        }
        hr{
            line-height: 1px;
            color: black;
        }
        #stitcher_name{
            font-weight: bold;
        }
        .bank_details{
            border: 2px solid black;
            padding: auto;
            width: fit-content;
            
        }
        .bank_main_details{
            padding: 5px;
            margin: 10px;
        }
        .bill_details {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 100%;
           
        }
    </style>
</head>
<body>
    <?php include('include/nav.php'); ?>
    <div class="container-fluid mt-5">
        <hr>
          <h1 class="h4 text-center">STITCHING PAYMENT SLIP </h1> <!-- Changed container to container-fluid -->
        <div id="form" class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                      
                        <?php if (!empty($errors)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php foreach ($errors as $error) : ?>
                                    <?php echo $error; ?><br>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <!-- New form to select stitcher and date range -->
                        <form method="post" action="">
                            <div class="date_input">
                                <!-- From date -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="from_date">From Date:</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date">
                                    </div>
                                </div>
                                <!-- To date -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="to_date">To Date:</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date">
                                    </div>
                                </div>
                            </div>
                            <div id="input_field" class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="select_stitcher">Select Stitcher:</label>
                                        <select class="form-select" id="select_stitcher" name="stitcher_name">
                                            <option value="">Select Stitcher</option>
                                            <?php while ($row = mysqli_fetch_assoc($stitcher_result)) : ?>
                                                <option value="<?php echo $row['stitcher_name']; ?>"><?php echo $row['stitcher_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="printbtn" class="btn-group">
                                <div>
                                    <button type="submit" class="btn btn-primary" name="view_entries">View</button>
                                    <button type="button" class="btn btn-primary" onclick="window.print()">Print</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($_POST['view_entries']) && !empty($stitcher_name) && !empty($start_date) && !empty($end_date) && $result !== null && mysqli_num_rows($result) > 0): ?>
            <div class="invoice-header">
            <div>
                <hr>
                <h2 id="company_heading1" class="heading"><?php echo $stitcher_name; ?> AND COMPANY</h2>
                <p class="heading"><?php echo $stitcher_address; ?></p>
                <p class="heading"> <b>Contact :</b> <?php echo $stitcher_contact; ?> &nbsp; <b>Aadhar No : </b>  <?php echo $stitcher_aadhar; ?> &nbsp; <b>Pan No :</b>  <?php echo $stitcher_pan; ?></p>
            </div>
            <div id="head_details">
                <div class="bill_details" >
                <div class="bank_details" >
                    <div class="bank_main_details">
                     <p>Bank Name : <?php echo $stitcher_details['bank_name']; ?></p>
                     <p>Bank Account Number : <?php echo $stitcher_details['bank_no']; ?></p>
                     <p>IFSC Code : <?php echo $stitcher_details['ifsc_code']; ?></p>
                    </div>
                </div>
                    <div>
                     <p>Bill No : _ _ _ _ _ _</p>
                     <p>Bill Date : ___ /____ /______</p>
                    </div>
                <div>
                </div>
                   </div>
                <hr>
                <h2 id="company_heading" class="heading">KHANNA SPORTS INDUSTRIES PVT. LTD</h2>
                <p class="heading"> A-7, Sports Complex Delhi Road Meerut Uttar Pradesh 250002</p>
                <p class="heading">Contact : 8449441387,98378427750 &nbsp;  GST : 09AAACK9669A1ZD </p>
            </div>
            </div>
        </div>
            <table class="table datatable-multi-sorting">
                <thead>
    <tr>
        <th>Sn.</th>
        <th>Challan No.</th>
    
        <th>Product Name</th>
        <th>Product Base</th>
        <th>Product Color</th>
        <th>Ist Quality Stitches</th>
        <th>Ist Price</th>
        <th>IInd Quality Stitches</th>
        <th>IInd Price</th>
        <th>Total</th>
        
        
        
        <th>Date</th>
    </tr>
</thead>
<tbody>
    <?php $sn = 1; ?>
    <?php while ($data = mysqli_fetch_array($result)): ?>
        <tr>
            <td><?php echo $sn; ?>.</td>
            <?php
                    $ist_price = 0;
                    $iind_price = 0;
            // Calculate Ist Price
            $ist_price_query = "SELECT per_pice_price FROM kits_product WHERE product_name = '" . $data['product_name'] . "' AND product_base = '" . $data['product_base'] . "' AND product_color = '" . $data['product_color'] . "'";
            $ist_price_result = mysqli_query($con, $ist_price_query);
            $ist_price_row = mysqli_fetch_assoc($ist_price_result);
            $ist_price = ($data['S_Ist_C_Ist'] + $data['S_Ist_C_IInd']) * $ist_price_row['per_pice_price'];

            // Calculate IInd Price
            $iind_price_query = "SELECT 2nd_price FROM kits_product WHERE product_name = '" . $data['product_name'] . "' AND product_base = '" . $data['product_base'] . "' AND product_color = '" . $data['product_color'] . "'";
            $iind_price_result = mysqli_query($con, $iind_price_query);
            $iind_price_row = mysqli_fetch_assoc($iind_price_result);
            $iind_price = ($data['S_IInd_C_Ist'] + $data['S_IInd_C_IInd']) * $iind_price_row['2nd_price'];

            $total_ist_price += $ist_price;
            $total_iind_price += $iind_price;
            $total_ist_stitches += $data['S_Ist_C_Ist'] + $data['S_Ist_C_IInd']; 
            $total_iind_stitches += $data['S_IInd_C_Ist'] + $data['S_IInd_C_IInd'];
            $total_all_quantity +=  $data['total'];
            // Reset total thread price for each row
            $total_thread_price = 0;
           
           
                // Fetch thread data for the selected stitcher and date range
                $thread_query = "SELECT thread_name, thread_quantity FROM kits_issue WHERE stitcher_name = '$stitcher_name' AND date_and_time BETWEEN '$start_date' AND '$end_date'";
                $thread_result = mysqli_query($con, $thread_query);
        
                // Calculate total thread price for each row
              // Calculate total thread price for each row
                while ($thread_data = mysqli_fetch_array($thread_result)) {
                  // Fetch thread price from 'threads' table
                 $thread_name = $thread_data['thread_name'];
                $thread_quantity = $thread_data['thread_quantity'];
                 $thread_price_query = "SELECT thread_price FROM threads WHERE thread_name = '$thread_name'";
                 $thread_price_result = mysqli_query($con, $thread_price_query);
    
                  // Check if the query returned any rows
                  if ($thread_price_result && mysqli_num_rows($thread_price_result) > 0) {
                       $thread_price_row = mysqli_fetch_assoc($thread_price_result);
                      $thread_price = $thread_price_row['thread_price'];
                 } else {
                      // Set $thread_price to 0 if $thread_price_row is null
                     $thread_price = 0;
                      }

                     // Calculate total thread price
                 $total_thread_price += ($thread_quantity * $thread_price);
                }

            

            ?>
            <td><?php echo $data['challan_no']; ?></td>
            
            <td><?php echo $data['product_name']; ?></td>
            <td><?php echo ucfirst($data['product_base']); ?></td>
            <td><?php echo ucfirst($data['product_color']); ?></td>
            <td><?php echo $data['S_Ist_C_Ist'] + $data['S_Ist_C_IInd']; ?></td>
            <td><?php echo $ist_price; ?></td>
            <td><?php echo $data['S_IInd_C_Ist'] + $data['S_IInd_C_IInd']; ?></td>
            <td><?php echo $iind_price; ?></td>
            <td><?php echo $data['total']; ?></td>
            
            <td><?php echo date('d/m/Y', strtotime($data['date_and_time'])); ?></td>
        </tr>
        <?php $sn++; ?>
    <?php endwhile; ?>
</tbody>
  
<tfoot>
    <tr>
       
        <td class="tablefoot">Total Ist Price: <?php echo $total_ist_price; ?></td>
        <td class="tablefoot">Total IInd Price: <?php echo $total_iind_price; ?></td>
        <td class="tablefoot">Total Thread Price: <?php echo $total_thread_price; ?></td>
        <td class="tablefoot">Total Stitching Amount: <?php echo $total_ist_price + $total_iind_price; ?></td>
        <td class="tablefoot">Total Payable Amount: <?php echo ($total_ist_price + $total_iind_price) - $total_thread_price; ?></td>
        <td><?php echo $total_ist_stitches; ?></td>
        <td colspan="1"></td>
        <td><?php echo $total_iind_stitches; ?></td>
        <td colspan="1"></td>
        <td><?php echo $total_all_quantity; ?></td>
        
        <td colspan="7"></td>
    </tr>
</tfoot>


</table>

        <div class="text-center mt-5">
            <p class="mb-1">I have received all stitching payments from <?php echo date('d/m/Y', strtotime($start_date)); ?> to <?php echo date('d/m/Y', strtotime($end_date)); ?>.</p>
            <p class="mb-1">मैंने <?php echo date('d/m/Y', strtotime($start_date)); ?> से <?php echo date('d/m/Y', strtotime($end_date)); ?> तक के सभी सिलाई भुगतान प्राप्त कर लिए हैं।</p>
            <p class="mb-5">Stitcher Signature / सिलाईदार हस्ताक्षर: _____________________</p>
           
        </div>

        <?php elseif (isset($_POST['view_entries'])): ?>
            <p>No entries found.</p>
        <?php endif; ?>
    </div>

    <!-- JavaScript code for fetching challan numbers based on selected stitcher and date range -->
    <script>
        function fetchChallanNumbers(selectedStitcher, fromDate, toDate) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var challanSelect = document.getElementById("select_challan");
                    var challanNumbers = JSON.parse(this.responseText);
                    challanSelect.innerHTML = "<option value='' selected disabled>Select Issue Challan No</option>";
                    challanNumbers.forEach(function(challan) {
                        var option = document.createElement("option");
                        option.value = challan;
                        option.text = challan;
                        challanSelect.appendChild(option);
                    });
                }
            };
            xhttp.open("GET", "stitcher_macking_price_data_fatch.php?stitcher=" + selectedStitcher + "&from_date=" + fromDate + "&to_date=" + toDate, true);
            xhttp.send();
        }

        function handleDateRangeChange() {
            var selectedStitcher = document.getElementById("select_stitcher").value;
            var fromDate = document.getElementById("from_date").value;
            var toDate = document.getElementById("to_date").value;
            if (selectedStitcher && fromDate && toDate) {
                fetchChallanNumbers(selectedStitcher, fromDate, toDate);
            }
        }

        document.getElementById("from_date").addEventListener("change", handleDateRangeChange);
        document.getElementById("to_date").addEventListener("change", handleDateRangeChange);

        document.getElementById("select_stitcher").addEventListener("change", function() {
            handleDateRangeChange();
        });
    </script>
</body>
</html>
