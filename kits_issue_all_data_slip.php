<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';

// Fetch stitcher names from the database
$stitcher_query = "SELECT DISTINCT stitcher_name FROM kits_issue ORDER BY stitcher_name ASC"; 
$stitcher_result = mysqli_query($con, $stitcher_query);
$challan_no = isset($_POST['challan_no']) ? $_POST['challan_no'] : "";
$stitcher_contact = isset($_POST['stitcher_contact']) ? $_POST['stitcher_contact'] : "";
$stitcher_aadhar = isset($_POST['stitcher_aadhar']) ? $_POST['stitcher_aadhar'] : "";
$stitcher_pan = isset($_POST['stitcher_pan']) ? $_POST['stitcher_pan'] : "";
$stitcher_address = isset($_POST['stitcher_address']) ? $_POST['stitcher_address'] : "";
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
        $date_and_time_query = "SELECT date_and_time FROM kits_issue WHERE challan_no = '$challan_no' LIMIT 1";
        $date_and_time_result = mysqli_query($con, $date_and_time_query);
        $date_and_time_row = mysqli_fetch_assoc($date_and_time_result);
        $date_and_time = $date_and_time_row['date_and_time'];

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
            .container_slip{
                background-color: aqua;

            }
            }
        }
        .container_slip {
            margin-top: 50px;
            background-color: #f8f9fc;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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
        .receiver-signature{
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
        #head_details{
            display: flex;
            margin-top: 0px;
            padding-top: 0px;
            flex-direction: row;
            align-items: flex-end;
            justify-content: space-between;
            
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
        #stitcher_name{
            font-weight: bold;
        }
    </style> 
</head>
<body>
<?php include('include/kits_nav.php'); ?>
    <div class="container-fluid mt-5">
          
        <div id="form" class="row justify-content-center">
        <h1 class="h4 text-center mb-4">KITS ISSUE SLIP </h1> <!-- Changed container to container-fluid -->
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
                        <!-- New form to select stitcher, associated challan number, and product details -->
                        <form method="post" action="">


                         <div class="date_input">
                                      <!-- From date -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="from_date">From Date:</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date" required>
                                    </div>
                                </div>
                                <!-- To date -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="to_date">To Date:</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date" required>
                                    </div>
                                </div>


                                </div>
                            <div id="input_field" class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="select_stitcher">Select Stitcher:</label>
                                        <select class="form-select" id="select_stitcher" name="stitcher_name" required>
         
                                        <option value="">Select Stitcher</option>
                                            <?php while ($row = mysqli_fetch_assoc($stitcher_result)) : ?>
                                                <option value="<?php echo $row['stitcher_name']; ?>"><?php echo $row['stitcher_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                       
                              
                            
                          
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="select_challan">Select Issue Challan No:</label>
                                        <select class="form-select" id="select_challan" name="challan_no" required>
                                            <option value="" selected disabled>Select Issue Challan No</option>
                                            <?php if (isset($challan_result_issue)) : ?>
                                                <?php while ($row = mysqli_fetch_assoc($challan_result_issue)) : ?>
                                                    <option value="<?php echo $row['challan_no']; ?>"><?php echo $row['challan_no']; ?></option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
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
                            </div>
                           
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <?php if (isset($_POST['view_entries']) && mysqli_num_rows($result) > 0): ?>
    <div class="container_slip">
        <!-- Add your HTML structure here to display kits issue details -->
        <div class="invoice-header">
           <div>
                <p class="issue_heading" >KITS ISSUE SLIP</p>
                <hr>
                <h2 id="company_heading" class="heading">KHANNA SPORTS INDUSTRIES PVT. LTD</h2>
                <p class="heading"> A-7, Sports Complex Delhi Road Meerut Uttar Pradesh 250002</p>
                <p class="heading">Contact : 8449441387,98378427750 &nbsp;  GST : 09AAACK9669A1ZD </p>
            </div>
            <div id="head_details">
                <div>
                    <p id="stitcher_name" >Stitcher : <?php echo $stitcher_name; ?></p>
                     <p>Stitcher Contact : <?php echo $stitcher_contact; ?></p>
                     <p>Stitcher Aadhar : <?php echo $stitcher_aadhar; ?></p>
                     <p>Stitcher Pan : <?php echo $stitcher_pan; ?></p>
                     <p>Stitcher Address : <?php echo $stitcher_address; ?></p>
                    <!-- Add other details as needed -->
                </div>
                <div>
                    <p><br/><br/>Challan No: <?php echo $challan_no; ?></p>
                    <p>Date: <?php echo date('d-m-Y', strtotime($date_and_time)); ?></p>
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
                            <th>Product Quantity</th>
                            <th>Bladder Type</th>
                            <th>Bladder Quantity</th>
                            <th>Thread Type</th>
                            <th>Thread Quantity</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php 

                             $total_product_quantity = 0;
                             $total_bladder_quantity = 0;
                             $total_thread_quantity = 0;
                             while ($product = mysqli_fetch_assoc($result)) : 
                            // Update total quantities
                            $total_product_quantity += $product['issue_quantity'];
                            $total_bladder_quantity += $product['bladder_quantity'];
                            $total_thread_quantity += $product['thread_quantity'];
                            ?>
                            <tr>
                                <td><?php echo $product['product_name']; ?></td>
                                <td><?php echo ucfirst($product['product_base']); ?></td>
                                <td><?php echo ucfirst($product['product_color']); ?></td>
                                <td><?php echo $product['issue_quantity']; ?></td>
                                <td><?php echo $product['bladder_name']; ?></td>
                                <td><?php echo $product['bladder_quantity']; ?></td>
                                <td><?php echo $product['thread_name']; ?></td>
                                <td><?php echo $product['thread_quantity']; ?></td>
                             
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3">Total</td>
                            <td><?php echo $total_product_quantity; ?></td>
                            <td colspan="1"></td> <!-- Add empty cells for other columns -->
                            <td><?php echo $total_bladder_quantity; ?></td>
                            <td colspan="1"></td>
                            <td><?php echo $total_thread_quantity; ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="signature">
            <div class="receiver-signature">Receiver Signature</div>
            <div class="middle-signature">Guard Signature</div>
            <div class="issuer-signature">Issuer Signature</div>
        </div>
    </div>
<?php endif; ?>

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
            xhttp.open("GET", "fatch_challan_no_for_kits_issue.php?stitcher=" + selectedStitcher + "&from_date=" + fromDate + "&to_date=" + toDate, true);
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