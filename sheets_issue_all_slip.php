<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Fetch labour names from the database
$labour_query = "SELECT DISTINCT labour_name FROM sheets_issue ORDER BY labour_name ASC"; 
$labour_result = mysqli_query($con, $labour_query);
$challan_no = isset($_POST['challan_no']) ? $_POST['challan_no'] : "";


// Check if 'challan_no' is set in session
if (isset($_SESSION['challan_no'])) {
    $challan_no = $_SESSION['challan_no'];
}

// Initialize $result variable
$result = null; 

// Check if 'View' button is clicked
if (isset($_POST['view_entries'])) {
    // Get selected labour
    $labour_name = isset($_POST['labour_name']) ? mysqli_real_escape_string($con, $_POST['labour_name']) : '';
   
   
    // Initialize conditions
    $conditions = "";

    // Add labour condition
    if (!empty($labour_name)) {
        $conditions .= " WHERE labour_name = '$labour_name'";
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
    $query = "SELECT * FROM sheets_issue $conditions";
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
            }
        }
        .container_slip {
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
        .invoice-header{
            line-height: 6px;
        }
    </style>
</head>
<body>
    <?php include('include/nav.php'); ?>
    <div class="container-fluid mt-5">
          
        <div id="form" class="row justify-content-center">
        <h1 class="h4 text-center mb-4">SHEET ISSUE SLIP </h1> <!-- Changed container to container-fluid -->
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
                        <!-- New form to select labour, associated challan number, and product details -->
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="select_labour">Select labour:</label>
                                        <select class="form-select" id="select_labour" name="labour_name">
         
                                        <option value="">Select labour</option>
                                            <?php while ($row = mysqli_fetch_assoc($labour_result)) : ?>
                                                <option value="<?php echo $row['labour_name']; ?>"><?php echo $row['labour_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                       
                              
                            
                          
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="select_challan">Select Receive Challan No:</label>
                                        <select class="form-select" id="select_challan" name="challan_no">
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
            <p>SHEETS ISSUE SLIP</p>
            <hr>
            <h2 class="heading">KHANNA SPORTS INDUSTRIES PVT. LTD</h2>
            <p class="heading"> A-7, Sports Complex Delhi Road Meerut Uttar Pradesh 250002</p>
            <p class="heading">Contact : 8449441387,98378427750 &nbsp;  GST : 09AAACK9669A1ZD </p>
        </div>
      
       
        <div id="head_details">
            <div>
            <p>labour : <?php echo $labour_name; ?></p>
            </div>
            <div>
                    <p><br/><br/>Challan No: <?php echo $challan_no; ?></p>
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
                            <th>Big Panel</th>
                            <th>Plain Panel</th>
                            <th>Small Panel Color</th>
                            <th>Small Panel</th>
                           
                            
                        </tr>
                    </thead>
                    <tbody>
                    <?php
$total_quantity1 = 0;
$total_quantity2 = 0;
$total_quantity3 = 0;

while ($product = mysqli_fetch_assoc($result)) {
    $total_quantity1 += $product['quantity1'];
    $total_quantity2 += $product['quantity2'];
    $total_quantity3 += $product['quantity3'];
    ?>
    <tr>
        <td><?php echo $product['product_name']; ?></td>
        <td><?php echo ucfirst($product['product_base']); ?></td>
        <td><?php echo ucfirst($product['product_color']); ?></td>
        <td><?php echo $product['quantity1']; ?></td>
        <td><?php echo $product['quantity2']; ?></td>
        <td><?php echo $product['small_panel_color']; ?></td>
        <td><?php echo $product['quantity3']; ?></td>
    </tr>
<?php } ?>

<tr>
    <td colspan="3"><strong>Total:</strong></td>
    <td><?php echo $total_quantity1; ?></td>
    <td><?php echo $total_quantity2; ?></td>
    <td></td> <!-- Assuming you don't want a total for 'small_panel_color' -->
    <td><?php echo $total_quantity3; ?></td>
   
</tr>

                    </tbody>
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

   <!-- JavaScript code for fetching challan numbers based on selected labour and date range -->
 

   <script>
     // ajax_script.js

function fetchChallanNumbers(selectedLabour) {
    var fromDate = document.getElementById("from_date").value;
    var toDate = document.getElementById("to_date").value;
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
    xhttp.open("GET", "fatch_challan_no_for_sheet_issue.php?labour=" + selectedLabour + "&from_date=" + fromDate + "&to_date=" + toDate, true);
    xhttp.send();
}

function handleLabourChange() {
    var selectedLabour = document.getElementById("select_labour").value;
    if (selectedLabour) {
        fetchChallanNumbers(selectedLabour);
    }
}

document.getElementById("select_labour").addEventListener("change", handleLabourChange);
document.getElementById("from_date").addEventListener("change", handleLabourChange);
document.getElementById("to_date").addEventListener("change", handleLabourChange);

// Trigger initial fetch when page loads
handleLabourChange();

    </script>
</body>
</html>