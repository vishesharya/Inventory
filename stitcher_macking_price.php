<?php
session_start();
error_reporting(0);
include('include/connection.php');
include_once 'include/admin-main.php';

// Fetch stitcher names from the database
$stitcher_query = "SELECT DISTINCT stitcher_name FROM football_received"; 
$stitcher_result = mysqli_query($con, $stitcher_query);

// Initialize $result variable
$result = null;

$total_ist_price = 0;
$total_iind_price = 0;


// Check if 'View' button is clicked
if (isset($_POST['view_entries'])) {
    // Get selected stitcher
    $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : '';

    // Get selected date range
    $start_date = isset($_POST['from_date']) ? mysqli_real_escape_string($con, $_POST['from_date']) : '';
    $end_date = isset($_POST['to_date']) ? mysqli_real_escape_string($con, $_POST['to_date']) : '';

    // Retrieve entries from database based on selected stitcher and/or date range
    if (!empty($stitcher_name) && !empty($start_date) && !empty($end_date)) {
        // Fetch entries within the selected date range for the selected stitcher
        $query = "SELECT * FROM football_received WHERE stitcher_name = '$stitcher_name' AND date_and_time BETWEEN '$start_date' AND '$end_date'";
    } elseif (!empty($stitcher_name)) {
        // Fetch all entries for the selected stitcher without considering date range
        $query = "SELECT * FROM football_received WHERE stitcher_name = '$stitcher_name'";
    } else {
        // If no stitcher is selected and no other filters are applied, fetch all entries from the database
        $query = "SELECT * FROM football_received";
    }

    $result = mysqli_query($con, $query);
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

            ?>
            <td><?php echo $data['challan_no']; ?></td>
            <td><?php echo $data['stitcher_name']; ?></td>
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
        <td colspan="7"></td> <!-- Adjust colspan according to your table structure -->
        <td>Total Ist Price: <?php echo $total_ist_price; ?></td>
        <td colspan="1"></td>
        <td>Total IInd Price: <?php echo $total_iind_price; ?></td>
        
    </tr>
</tfoot>


 </table>

            <h2>Thread Information</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Thread Name</th>
                <th>Thread Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($data = mysqli_fetch_array($result)): ?>
                <tr>
                    <?php
                    $thread_query = "SELECT thread_name, thread_quantity FROM kits_job_work WHERE stitcher_name = '{$data['stitcher_name']}' AND date_and_time BETWEEN '{$start_date}' AND '{$end_date}'";
                    $thread_result = mysqli_query($con, $thread_query);
                    $thread_data = mysqli_fetch_assoc($thread_result);
                    ?>
                    <td><?php echo $data['thread_name']; ?></td>
                    <td><?php echo $data['thread_quantity']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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
