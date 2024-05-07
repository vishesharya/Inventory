<?php
session_start();
error_reporting(0);
include('include/connection.php');
include_once 'include/admin-main.php';

// Fetch stitcher names from the database
$stitcher_query = "SELECT DISTINCT stitcher_name FROM football_received"; 
$stitcher_result = mysqli_query($con, $stitcher_query);

// Get Challan No from session
$challan_no = $_SESSION['challan_no'];

// Initialize $result variable
$result = null;

// Check if 'View' button is clicked
if (isset($_POST['view_entries'])) {
    // Get selected stitcher
    $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : '';

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
          <h1 class="h4 text-center mb-4">FOOTBALL RECEIVING DETAILS </h1> <!-- Changed container to container-fluid -->
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
                    <th>Stitcher 1st Company 1st</th>
                    <th>Stitcher 1st Company 2nd</th>
                    <th>Stitcher 2nd Company 1st</th>
                    <th>Stitcher 2nd Company 2nd</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php $sn = 1; ?>
                <?php while ($data = mysqli_fetch_array($result)): ?>
                    <tr>
                        <td><?php echo $sn; ?>.</td>
                        <td><?php echo $data['challan_no']; ?></td>
                        <td><?php echo $data['stitcher_name']; ?></td>
                        <td><?php echo $data['product_name']; ?></td>
                        <td><?php echo ucfirst($data['product_base']); ?></td>
                        <td><?php echo ucfirst($data['product_color']); ?></td>
                        <td><?php echo $data['S_Ist_C_Ist']; ?></td>
                        <td><?php echo $data['S_Ist_C_IInd']; ?></td>
                        <td><?php echo $data['S_IInd_C_Ist']; ?></td>
                        <td><?php echo $data['S_IInd_C_IInd']; ?></td>
                        <td><?php echo $data['total']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($data['date_and_time'])); ?></td>
                    </tr>
                    <?php $sn++; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php elseif (isset($_POST['view_entries'])): ?>
        <p>No entries found.</p>
    <?php endif; ?>



<!-- JavaScript code for fetching  challan numbers based on selected stitcher and date range -->
 

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
            xhttp.open("GET", "football_receiving_print.php?stitcher=" + selectedStitcher + "&from_date=" + fromDate + "&to_date=" + toDate, true);
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