<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';
 
// Fetch labour names from the database
$labour_query = "SELECT DISTINCT labour_name FROM sheets_issue ORDER BY labour_name ASC"; 
$labour_result = mysqli_query($con, $labour_query);
$challan_no = isset($_POST['challan_no']) ? $_POST['challan_no'] : "";

$product_query = "SELECT DISTINCT product_name FROM sheets_product ORDER BY product_name ASC";
$product_result = mysqli_query($con, $product_query);

// Logic to fetch product bases and colors based on selected product
$selected_product = isset($_POST['product_name']) ? $_POST['product_name'] : null;
if ($selected_product) {
    $product_base_query = "SELECT DISTINCT product_base FROM sheets_product WHERE product_name = '$selected_product' ORDER BY product_base ASC";
    $product_color_query = "SELECT DISTINCT product_color FROM sheets_product WHERE product_name = '$selected_product' ORDER BY product_color ASC";
    $product_base_result = mysqli_query($con, $product_base_query);
    $product_color_result = mysqli_query($con, $product_color_query);
}

// Logic to fetch product bases and colors based on selected product
$selected_product = isset($_POST['product_name']) ? $_POST['product_name'] : null;
if ($selected_product) {
    $product_small_query = "SELECT DISTINCT small_sheet_color FROM sheets_small_stock";
    $product_small_result = mysqli_query($con, $product_small_query);
 
}



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

     // Add product condition
     if (!empty($selected_product)) {
        $conditions .= ($conditions == "") ? " WHERE" : " AND";
        $conditions .= " product_name = '$selected_product'";
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
<?php include('include/sheets_nav.php'); ?>
    <div class="container-fluid mt-5">
    <h1 class="h4 text-center mb-4">SHEETS ISSUE DETAILS </h1> 
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


        <?php 
        if (isset($_POST['view_entries']) && mysqli_num_rows($result) > 0): ?>
        <table class="table datatable-multi-sorting">
            <thead>
                <tr>
                    <th>Sn.</th>
                    <th>Challan No.</th>
                    <th>Labour Name</th>
                    <th>Product Name</th>
                    <th>Product Base</th>
                    <th>Product Color</th>
                    <th>Big Panel</th>
                    <th>Plain Panel</th>
                    <th>Small Panel Color</th>
                    <th>Small Panel</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $sn = 1;
            $total_quantity1 = 0;
            $total_quantity2 = 0;
            $total_quantity3 = 0;

            while ($data = mysqli_fetch_array($result)) {
                $total_quantity1 += $data['quantity1'];
                $total_quantity2 += $data['quantity2'];
                $total_quantity3 += $data['quantity3'];
                ?>
                <tr>
                    <td><?php echo $sn; ?>.</td>
                    <td><?php echo $data['challan_no']; ?></td>
                    <td><?php echo $data['labour_name']; ?></td>
                    <td><?php echo $data['product_name']; ?></td>
                    <td><?php echo ucfirst($data['product_base']); ?></td>
                    <td><?php echo ucfirst($data['product_color']); ?></td>
                    <td><?php echo $data['quantity1']; ?></td>
                    <td><?php echo $data['quantity2']; ?></td>
                    <td><?php echo $data['small_panel_color']; ?></td>
                    <td><?php echo $data['quantity3']; ?></td>
                    <td><?php echo date('d/m/Y', strtotime($data['date_and_time'])); ?></td>
                </tr>
                <?php $sn++; ?>
            <?php } ?>
        </tbody>
            <tfoot>
            <tr>
                <td colspan="6">Total:</td>
                <td><?php echo $total_quantity1; ?></td>
                <td><?php echo $total_quantity2; ?></td>
                <td></td> <!-- Assuming no total for small panel color -->
                <td><?php echo $total_quantity3; ?></td>
                <td></td> <!-- Assuming no total for date -->
            </tr>
        </tfoot>
        </table>
    <?php elseif (isset($_POST['view_entries'])): ?>
        <p>No entries found.</p>
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