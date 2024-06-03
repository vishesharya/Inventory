<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Initialize variables
$errors = array();

// Fetch stitcher names from the database
$stitcher_query = "SELECT DISTINCT stitcher_name FROM football_received ORDER BY stitcher_name ASC"; 
$stitcher_result = mysqli_query($con, $stitcher_query);

// Check if 'challan_no' is set in session
$challan_no = isset($_SESSION['challan_no']) ? $_SESSION['challan_no'] : '';

// Fetch product names
$product_query = "SELECT DISTINCT product_name FROM football_received ORDER BY product_name ASC";
$product_result = mysqli_query($con, $product_query);

// Initialize selected product, base, and color variables
$selected_product = isset($_POST['product_name']) ? mysqli_real_escape_string($con, $_POST['product_name']) : '';
$selected_base = isset($_POST['product_base']) ? mysqli_real_escape_string($con, $_POST['product_base']) : '';
$selected_color = isset($_POST['product_color']) ? mysqli_real_escape_string($con, $_POST['product_color']) : '';

// Fetch product bases based on selected product
if ($selected_product) {
    $product_base_query = "SELECT DISTINCT product_base FROM football_received WHERE product_name = '$selected_product' ORDER BY product_base ASC";
    $product_base_result = mysqli_query($con, $product_base_query);

    // Fetch product colors based on selected product and base
    if ($selected_base) {
        $product_color_query = "SELECT DISTINCT product_color FROM football_received WHERE product_name = '$selected_product' AND product_base = '$selected_base' ORDER BY product_color ASC";
        $product_color_result = mysqli_query($con, $product_color_query);
    }
}

// Check if 'View' button is clicked
if (isset($_POST['view_entries'])) {
    // Retrieve form data
    $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : '';
    $selected_product = isset($_POST['product_name']) ? mysqli_real_escape_string($con, $_POST['product_name']) : '';
    $selected_base = isset($_POST['product_base']) ? mysqli_real_escape_string($con, $_POST['product_base']) : '';
    $selected_color = isset($_POST['product_color']) ? mysqli_real_escape_string($con, $_POST['product_color']) : '';

    // Construct conditions for SQL query
    $conditions = "1"; // Default condition

    // Add stitcher condition
    if (!empty($stitcher_name)) {
        $conditions .= " AND stitcher_name = '$stitcher_name'";
    }

    // Add product name filter if provided
    if (!empty($selected_product)) {
        $conditions .= " AND product_name = '$selected_product'";
    }

    // Add product base filter if provided
    if (!empty($selected_base)) {
        $conditions .= " AND product_base = '$selected_base'";
    }

    // Add product color filter if provided 
    if (!empty($selected_color)) {
        $conditions .= " AND product_color = '$selected_color'";
    }

    // Add date range condition
    if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
        $start_date = mysqli_real_escape_string($con, $_POST['from_date']);
        $end_date = mysqli_real_escape_string($con, $_POST['to_date']);
        $conditions .= " AND date_and_time BETWEEN '$start_date' AND '$end_date'";
    }

    // Add challan number condition
    if (!empty($_POST['challan_no'])) {
        $challan_no = mysqli_real_escape_string($con, $_POST['challan_no']);
        $conditions .= " AND challan_no = '$challan_no'";
    }

    // Construct the final query
    $query = "SELECT * FROM football_received WHERE $conditions";
    $result = mysqli_query($con, $query);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KITS RECEIVED DETAILS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
       
        body {
            background-color: #f8f9fc;
            font-family: Arial, sans-serif;
            overflow-y: scroll; /* Enable scrollbar on body */
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
        .table-container {
            max-height: 500px;
            overflow-y: auto;
            margin-top: 2rem;
        }
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table th, .table td {
            text-align: center;
            padding: 8px;
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
        /* Custom scrollbar styles */
        .table-container::-webkit-scrollbar {
            width: 12px;
        }
        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    
</head>
<body>
    <?php include('include/kits_nav.php'); ?>
    <div class="container-fluid mt-5">
          <h1 class="h4 text-center mb-4">KITS RECEIVED DETAILS (WITHOUT PRINT)</h1> <!-- Changed container to container-fluid -->
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name">Select Product:</label>
                                        <select class="form-select" id="product_name" name="product_name" onchange="this.form.submit()">
                                            <option value="" selected disabled>Select Product</option>
                                            <?php while ($row = mysqli_fetch_assoc($product_result)) : ?>
                                                <option value="<?php echo $row['product_name']; ?>" <?php echo $selected_product == $row['product_name'] ? 'selected' : ''; ?>><?php echo $row['product_name']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_base">Product Base:</label>
                                        <select class="form-select" id="product_base" name="product_base">
                                            <option value="" selected disabled>Select Product Base</option>
                                            <?php if ($selected_product) : ?>
                                                <?php while ($row = mysqli_fetch_assoc($product_base_result)) : ?>
                                                    <option value="<?php echo $row['product_base']; ?>"><?php echo $row['product_base']; ?></option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_color">Product Color:</label>
                                        <select class="form-select" id="product_color" name="product_color">
                                            <option value="" selected disabled>Select Product Color</option>
                                            <?php if ($selected_product) : ?>
                                                <?php while ($row = mysqli_fetch_assoc($product_color_result)) : ?>
                                                    <option value="<?php echo $row['product_color']; ?>"><?php echo $row['product_color']; ?></option>
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


        <?php


// Check if the form has been submitted
if (isset($_POST['view_entries'])) {

    // Check if the query was successful
    if ($result) {
        // Check if there are any rows returned
        if (mysqli_num_rows($result) > 0) {
            ?>
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
                <?php 
                $sn = 1;
                $total_S_Ist_C_Ist = 0;
                $total_S_Ist_C_IInd = 0;
                $total_S_IInd_C_Ist = 0;
                $total_S_IInd_C_IInd = 0;
                $total_all_quantity = 0;
                while ($data = mysqli_fetch_array($result)) {
                    
                    ?>
                    <tr>
                       
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
                    <?php 
                    $sn++;
                    $total_S_Ist_C_Ist += $data['S_Ist_C_Ist'];
                    $total_S_Ist_C_IInd += $data['S_Ist_C_IInd'];
                    $total_S_IInd_C_Ist += $data['S_IInd_C_Ist'];
                    $total_S_IInd_C_IInd += $data['S_IInd_C_IInd'];
                    $total_all_quantity += $data['total'];
                }
                ?>
                <tr>
                <td colspan="3"></td> <!-- Colspan to span across columns -->
                 <td><b>Total : </b></td>
                 <td><?php echo $total_S_Ist_C_Ist; ?></td>
                 <td><?php echo $total_S_Ist_C_IInd; ?></td>
                 <td><?php echo $total_S_IInd_C_Ist; ?></td>
                 <td><?php echo $total_S_IInd_C_IInd; ?></td>
                 <td><?php echo $total_all_quantity; ?></td>
                 <td></td>
                </tr>
                </tbody>
            </table>
            <?php
        } else {
            // No entries found
            echo '<p>No entries found.</p>';
        }
    } 
}
?>


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

<script>
    // Function to update product colors based on selected product name and base
    function updateProductColors() {
        var productName = document.getElementById('product_name').value;
        var productBase = document.getElementById('product_base').value;

        // Make an AJAX request to fetch product colors based on product name and base
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                var colors = JSON.parse(this.responseText);
                var productColorSelect = document.getElementById('product_color');
                // Clear existing options
                productColorSelect.innerHTML = '<option value="" selected disabled>Select Product Color</option>';
                // Add fetched colors as options
                colors.forEach(function(color) {
                    var option = document.createElement('option');
                    option.value = color;
                    option.text = color;
                    productColorSelect.appendChild(option);
                });
            }
        };
        xhr.open('GET', 'football_reciving_color_fetch.php?product_name=' + productName + '&product_base=' + productBase, true);
        xhr.send();
    }

    // Event listeners for product name and product base change
    document.getElementById('product_name').addEventListener('change', updateProductColors);
    document.getElementById('product_base').addEventListener('change', updateProductColors);
</script>

    
</body>
</html>