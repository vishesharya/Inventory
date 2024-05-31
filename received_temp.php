<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

$stitcher_query = "SELECT DISTINCT stitcher_name FROM kits_issue ORDER BY stitcher_name ASC"; 
$stitcher_result = mysqli_query($con, $stitcher_query);
$challan_no = isset($_POST['challan_no']) ? $_POST['challan_no'] : "";

// Check if 'challan_no' is set in session
if (isset($_SESSION['challan_no'])) {
    $challan_no = $_SESSION['challan_no'];
}

// Initialize $result variable
$result = null; 

// Fetch product names
$product_query = "SELECT DISTINCT product_name FROM kits_product ORDER BY product_name ASC";
$product_result = mysqli_query($con, $product_query);

// Initialize selected product, base, and color variables
$selected_product = isset($_POST['product_name']) ? mysqli_real_escape_string($con, $_POST['product_name']) : null;
$selected_base = isset($_POST['product_base']) ? mysqli_real_escape_string($con, $_POST['product_base']) : null;
$selected_color = isset($_POST['product_color']) ? mysqli_real_escape_string($con, $_POST['product_color']) : null;

// Fetch product bases based on selected product
if ($selected_product) {
    $product_base_query = "SELECT DISTINCT product_base FROM kits_product WHERE product_name = '$selected_product' ORDER BY product_base ASC";
    $product_base_result = mysqli_query($con, $product_base_query);

    // Fetch product colors based on selected product and base
    if ($selected_base) {
        $product_color_query = "SELECT DISTINCT product_color FROM kits_product WHERE product_name = '$selected_product' AND product_base = '$selected_base' ORDER BY product_color ASC";
        $product_color_result = mysqli_query($con, $product_color_query);
    }
}

// Check if 'View' button is clicked
if (isset($_POST['view_entries'])) {
    // Get selected labour
    $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : '';

    // Initialize conditions
    $conditions = "";

    // Add labour condition
    if (!empty($stitcher_name)) {
        $conditions .= " WHERE stitcher_name = '$stitcher_name'";
    }

    // Add date range condition
    if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
        // Get selected date range
        $start_date = mysqli_real_escape_string($con, $_POST['from_date']);
        $end_date = mysqli_real_escape_string($con, $_POST['to_date']);

        // Add date range condition
        $conditions[] = "date_and_time BETWEEN '$start_date' AND '$end_date'";
    }

    // Add challan number condition
    if (!empty($_POST['challan_no'])) {
        // Get selected challan number
        $challan_no = mysqli_real_escape_string($con, $_POST['challan_no']);
        
        // Add challan number condition
        $conditions[] = "challan_no = '$challan_no'";
    }

    // Add product name filter if provided
    if (!empty($selected_product)) {
        $conditions[] = "product_name = '$selected_product'";
    }

    // Add product base filter if provided
    if (!empty($selected_base)) {
        $conditions[] = "product_base = '$selected_base'";
    }

    // Add product color filter if provided
    if (!empty($selected_color)) {
        $conditions[] = "product_color = '$selected_color'";
    }

    // Construct the final query
    $query = "SELECT * FROM kits_issue";
    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }
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
            gap: 15px;
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
 <?php include('include/kits_nav.php'); ?>
    <div class="container-fluid mt-5">
    <h1 class="h4 text-center mb-4">KITS RECEIVE DETAILS </h1> 
        <div id="form" class="row justify-content-center">
    <!-- Changed container to container-fluid -->
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
                                        <label for="select_challan">Select Received Challan No:</label>
                                        <select class="form-select" id="select_challan" name="challan_no">
                                            <option value="" selected disabled>Select Received Challan No</option>
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
                    $sn = 1; 
                    $total_issue_quantity = 0;
                    $total_bladder_quantity = 0;
                    $total_thread_quantity = 0;
                    while ($data = mysqli_fetch_array($result)): 
                        $total_issue_quantity += $data['issue_quantity'];
                         $total_bladder_quantity += $data['bladder_quantity'];
                         $total_thread_quantity += $data['thread_quantity'];
                    ?>
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
                    <?php 
                    $sn++;
                    
                
                ?>
                <?php endwhile; ?>
            </tbody>
            <tfoot>
            <tr>
                    <td colspan="5"></td> <!-- Colspan to span across columns -->
                    <td><b>Total : </b></td>
                    <td><?php echo $total_issue_quantity; ?></td>
                    <td></td> <!-- Empty cell for bladder name -->
                    <td><?php echo $total_bladder_quantity; ?></td>
                    <td></td> <!-- Empty cell for thread name -->
                    <td><?php echo $total_thread_quantity; ?></td>
                    <td colspan="1"></td> <!-- Colspan to span across date column -->
                </tr>
                </tfoot>
        </table>
    <?php elseif (isset($_POST['view_entries'])): ?>
        <p>No entries found.</p>
    <?php endif; ?>


   <!-- JavaScript code for fetching challan numbers based on selected labour and date range -->
 

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
        xhr.open('GET', 'fetch_product_color.php?product_name=' + productName + '&product_base=' + productBase, true);
        xhr.send();
    }

    // Event listeners for product name and product base change
    document.getElementById('product_name').addEventListener('change', updateProductColors);
    document.getElementById('product_base').addEventListener('change', updateProductColors);
</script>
</body>
</html> 