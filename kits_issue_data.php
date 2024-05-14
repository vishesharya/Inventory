<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Fetch stitcher names from the database
$stitcher_query = "SELECT DISTINCT stitcher_name FROM kits_issue ORDER BY stitcher_name ASC"; 
$stitcher_result = mysqli_query($con, $stitcher_query);

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
        @media print {
            #form {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php include('include/nav.php'); ?>
    <div class="container-fluid mt-5">
          <h1 class="h4 text-center mb-4">KITS ISSUE DETAILS </h1> <!-- Changed container to container-fluid -->
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
                        <!-- New form to select stitcher, associated challan number, and product details -->
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
                                        <label for="select_stitcher">Select Stitcher:</label>
                                        <select class="form-select" id="select_stitcher" name="stitcher_name">
         
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
        <table class="table datatable-multi-sorting">
            <thead>
                <tr>
                    <th>Sn.</th>
                    <th>Challan No.</th>
                    <th>Stitcher Name</th>
                    <th>Product Name</th>
                    <th>Product Base</th>
                    <th>Product Color</th>
                    <th>Issue Quantity</th>
                    <th>Bladder Name</th>
                    <th>Bladder Quantity</th>
                    <th>Thread Name</th>
                    <th>Thread Quantity</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php 
// Initialize variables to hold totals
$total_issue_quantity = 0;
$total_bladder_quantity = 0;
$total_thread_quantity = 0;
?>
                <?php $sn = 1; ?>
                <?php while ($data = mysqli_fetch_array($result)): ?>
                    <tr>
                        <td><?php echo $sn; ?>.</td>
                        <td><?php echo $data['challan_no']; ?></td>
                        <td><?php echo $data['stitcher_name']; ?></td>
                        <td><?php echo $data['product_name']; ?></td>
                        <td><?php echo ucfirst($data['product_base']); ?></td>
                        <td><?php echo ucfirst($data['product_color']); ?></td>
                        <td><?php echo $data['issue_quantity']; ?></td>
                        <td><?php echo $data['bladder_name']; ?></td>
                        <td><?php echo $data['bladder_quantity']; ?></td>
                        <td><?php echo $data['thread_name']; ?></td>
                        <td><?php echo $data['thread_quantity']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($data['date_and_time'])); ?></td>

                    </tr>
                    <?php $sn++; ?>
                    <?php 
                  $total_issue_quantity += $data['issue_quantity'];
                  $total_bladder_quantity += $data['bladder_quantity'];
                  $total_thread_quantity += $data['thread_quantity'];
    ?>
                <?php endwhile; ?>
                <tr>
                    <td><b>Total : </b></td>
    <td colspan="5"></td> <!-- Colspan to span across columns -->
    <td><?php echo $total_issue_quantity; ?></td>
    <td></td> <!-- Empty cell for bladder name -->
    <td><?php echo $total_bladder_quantity; ?></td>
    <td></td> <!-- Empty cell for thread name -->
    <td><?php echo $total_thread_quantity; ?></td>
    <td colspan="1"></td> <!-- Colspan to span across date column -->
</tr>
            </tbody>
        </table>
    <?php elseif (isset($_POST['view_entries'])): ?>
        <p>No entries found.</p>
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