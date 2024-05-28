<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Fetch product names
$product_query = "SELECT DISTINCT product_name FROM sheets_product ORDER BY product_name ASC";
$product_result = mysqli_query($con, $product_query);

// Logic to fetch product bases and colors based on selected product
$selected_product = isset($_POST['product_name1']) ? $_POST['product_name1'] : null;
if ($selected_product) {
    $product_base_query = "SELECT DISTINCT product_base FROM sheets_product WHERE product_name = '$selected_product' ORDER BY product_base ASC";
    $product_color_query = "SELECT DISTINCT product_color FROM sheets_product WHERE product_name = '$selected_product' ORDER BY product_color ASC";
    $product_base_result = mysqli_query($con, $product_base_query);
    $product_color_result = mysqli_query($con, $product_color_query);
}
 
// Function to fetch current number from the database
function getCurrentNumber($con) {
    $result = mysqli_query($con, "SELECT football_received_temp FROM challan_temp LIMIT 1");
    $row = mysqli_fetch_assoc($result);
    return $row['football_received_temp'];
}

// Function to update the current number in the database
function updateCurrentNumber($con, $newNumber) {
    mysqli_query($con, "UPDATE challan_temp SET football_received_temp = $newNumber");
}

// Function to generate the code prefix
function generateCodePrefix($number) {
    return "KSI-FR-" . $number;
}

// Function to generate the Challan number
function generateChallanNumber($con) {
    $currentNumber = getCurrentNumber($con);
    $codePrefix = generateCodePrefix($currentNumber);
    // Increment current number for the next time
    updateCurrentNumber($con, $currentNumber + 1);
    return $codePrefix;
}

function viewChallanNumber($con) {
    $currentNumber = getCurrentNumber($con);
    $codePrefix = generateCodePrefix($currentNumber);
    return $codePrefix; 
}

$challan_no = viewChallanNumber($con); 

// Fetch stitcher names from the database
$stitcher_query = "SELECT DISTINCT stitcher_name FROM print_job_work WHERE status = 0 ORDER BY stitcher_name ASC";
$stitcher_result = mysqli_query($con, $stitcher_query);

// Fetch associated challan numbers for selected stitcher
if (isset($_POST['stitcher_name'])) {
    $selected_stitcher = mysqli_real_escape_string($con, $_POST['stitcher_name']);
    $challan_query_issue = "SELECT DISTINCT challan_no_issue FROM print_job_work WHERE stitcher_name = '$selected_stitcher' AND status = 0";
    $challan_result_issue = mysqli_query($con, $challan_query_issue);
}

// Fetch product names based on selected stitcher and challan number
if (isset($_POST['challan_no_issue'])) {
    $selected_challan = mysqli_real_escape_string($con, $_POST['challan_no_issue']);
    $selected_stitcher = mysqli_real_escape_string($con, $_POST['stitcher_name']); // Added this line

    // Query to fetch products based on selected stitcher, challan number, and status = 0
    $product_query = "SELECT DISTINCT product_name,product_base,product_color FROM print_job_work WHERE stitcher_name = '$selected_stitcher' AND challan_no_issue = '$selected_challan' AND status = 0";
    $product_result = mysqli_query($con, $product_query);
}

if (isset($_POST['add_product'])) {
    // Validate input
    if (empty($_POST['product_name']) || empty($_POST['product_base']) || empty($_POST['product_color']) || empty($_POST['quantity']) || empty($_POST['product_name1']) || empty($_POST['product_base1']) || empty($_POST['product_color1'])) {
        $errors[] = "Please fill in all fields for the first product.";
    } else {
        // Sanitize input
        $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : "";
        $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
        $product_base = mysqli_real_escape_string($con, $_POST['product_base']);
        $product_color = mysqli_real_escape_string($con, $_POST['product_color']);
        $product_name1 = mysqli_real_escape_string($con, $_POST['product_name1']);
        $product_base1 = mysqli_real_escape_string($con, $_POST['product_base1']);
        $product_color1 = mysqli_real_escape_string($con, $_POST['product_color1']);
        $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
        
        // Fetch the available stock from print_job_work
        $available_stock_query = "SELECT issue_quantity FROM print_job_work WHERE challan_no_issue = '$selected_challan' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color' AND stitcher_name = '$stitcher_name'";
        $available_stock_result = mysqli_query($con, $available_stock_query);
        $available_stock_row = mysqli_fetch_assoc($available_stock_result);
        $available_stock = $available_stock_row['issue_quantity'];

        if ($available_stock < $quantity) {
            $errors[] = "Quantity exceeds available stock.";
        } else {
            // Update the issue_quantity in print_job_work
            $new_issue_quantity = $available_stock - $quantity;
            $update_issue_quantity_query = "UPDATE print_job_work SET issue_quantity = $new_issue_quantity WHERE challan_no_issue = '$selected_challan' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color' AND stitcher_name = '$stitcher_name'";
            mysqli_query($con, $update_issue_quantity_query);

            // Update the status if issue_quantity is 0
            if ($new_issue_quantity == 0) {
                $update_status_query = "UPDATE print_job_work SET status = 1 WHERE challan_no_issue = '$selected_challan' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color' AND stitcher_name = '$stitcher_name'";
                mysqli_query($con, $update_status_query);
            }

            // Update the remaining_quantity in kits_product
            $update_remaining_quantity_query = "UPDATE kits_product SET remaining_quantity = remaining_quantity + $quantity WHERE product_name = '$product_name1' AND product_base = '$product_base1' AND product_color = '$product_color1'";
            mysqli_query($con, $update_remaining_quantity_query);

            // Insert data into temporary session storage
            $temp_product = array(
                'challan_no' => $challan_no,
                'challan_no_issue' => $selected_challan,
                'stitcher_name' => $stitcher_name,
                'product_name' => $product_name,
                'product_base' => $product_base,
                'product_color' => $product_color,
                'product_name1' => $product_name1,
                'product_base1' => $product_base1,
                'product_color1' => $product_color1,
                'received_quantity' => $quantity,
                'date_and_time' => isset($_POST['date_and_time']) ? $_POST['date_and_time'] : date('Y-m-d H:i:s')
            );
            $_SESSION['temp_products'][] = $temp_product;
        }
    }
}

// Check if delete button is clicked
if (isset($_POST['delete_product'])) {
    // Get the index of the product to be deleted
    $delete_index = $_POST['delete_index'];

    // Get the product details to update remaining_quantity in kits_product table
    $deleted_product = $_SESSION['temp_products'][$delete_index];
    $product_name = mysqli_real_escape_string($con, $deleted_product['product_name']);
    $product_base = mysqli_real_escape_string($con, $deleted_product['product_base']);
    $product_color = mysqli_real_escape_string($con, $deleted_product['product_color']);
    $deleted_quantity = mysqli_real_escape_string($con, $deleted_product['received_quantity']);

    // Update the issue_quantity in print_job_work
    $update_issue_quantity_query = "UPDATE print_job_work SET issue_quantity = issue_quantity + $deleted_quantity WHERE challan_no_issue = '{$deleted_product['challan_no_issue']}' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color' AND stitcher_name = '{$deleted_product['stitcher_name']}'";
    mysqli_query($con, $update_issue_quantity_query);

    // Check if the status is 1 and update it to 0
    $check_status_query = "SELECT status FROM print_job_work WHERE challan_no_issue = '{$deleted_product['challan_no_issue']}' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color' AND stitcher_name = '{$deleted_product['stitcher_name']}'";
    $status_result = mysqli_query($con, $check_status_query);
    $status_row = mysqli_fetch_assoc($status_result);
    if ($status_row['status'] == 1) {
        $update_status_query = "UPDATE print_job_work SET status = 0 WHERE challan_no_issue = '{$deleted_product['challan_no_issue']}' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color' AND stitcher_name = '{$deleted_product['stitcher_name']}'";
        mysqli_query($con, $update_status_query);
    }

    // Update the remaining_quantity in kits_product
    $update_remaining_quantity_query = "UPDATE kits_product SET remaining_quantity = remaining_quantity - $deleted_quantity WHERE product_name = '{$deleted_product['product_name1']}' AND product_base = '{$deleted_product['product_base1']}' AND product_color = '{$deleted_product['product_color1']}'";
    mysqli_query($con, $update_remaining_quantity_query);
    

    // Remove the product from the session
    unset($_SESSION['temp_products'][$delete_index]);

    // Reset array keys to maintain consecutive numbering
    $_SESSION['temp_products'] = array_values($_SESSION['temp_products']);
}



// Store added products in the database when "Submit" button is clicked
if (isset($_POST['submit_products'])) {
    $temp_products = isset($_SESSION['temp_products']) ? $_SESSION['temp_products'] : [];

    if (empty($temp_products)) {
        $errors[] = "Please add at least one product.";
    } else {
        foreach ($temp_products as $product) {
            $challan_no = mysqli_real_escape_string($con, $product['challan_no']);
            $stitcher_name = mysqli_real_escape_string($con, $product['stitcher_name']);
            $product_name = mysqli_real_escape_string($con, $product['product_name']);
            $product_base = mysqli_real_escape_string($con, $product['product_base']);
            $product_color = mysqli_real_escape_string($con, $product['product_color']);
            $product_name1 = mysqli_real_escape_string($con, $product['product_name1']);
            $product_base1 = mysqli_real_escape_string($con, $product['product_base1']);
            $product_color1 = mysqli_real_escape_string($con, $product['product_color1']);
            $quantity = mysqli_real_escape_string($con, $product['received_quantity']);
            $date_and_time = mysqli_real_escape_string($con, $product['date_and_time']);
            
            // Insert product into the database
            $insert_query = "INSERT INTO print_received (challan_no, sticher_name, product_name1, product_base1, product_color1, product_name, product_base, product_color, received_quantity, date_and_time) 
                VALUES ('$challan_no', '$stitcher_name', '$product_name1', '$product_base1', '$product_color1','$product_name', '$product_base', '$product_color', '$quantity','$date_and_time')";
                
            $insert_result = mysqli_query($con, $insert_query);
            if (!$insert_result) {
                $errors[] = "Failed to store data in the database.";
            }
        }

        // If no errors, update the Challan Number and clear session storage
        if (empty($errors)) {
            // Update Challan Number
            $challan_no = generateChallanNumber($con);
            
            // Clear session storage after insertion
            unset($_SESSION['temp_products']);

            // Redirect to the same page to prevent form resubmission
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit();
        }
    }
}

   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kits Received</title>
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
            gap: 15px;
        }
        .btn-group {
            margin-top: 1.5rem;
            justify-content: center; 
            gap: 15px;
        }
        .table {
            margin-top: 2rem;
        }
    </style>
</head>
<body> 
<?php include('include/kits_nav.php'); ?>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-4">Kits Received</h1>
                        <?php if (!empty($errors)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php foreach ($errors as $error) : ?>
                                    <?php echo $error; ?><br>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" action="">
                        
                        <div class="row">
                        <div class="col-md-6">
                        <div class="form-group">
                                           <label for="product_name1"> Select Received Product:</label>
                                            <select class="form-select" id="product_name1" name="product_name1" onchange="this.form.submit()">
                                              <option value="" selected disabled>Select Product</option>
                                                   <?php while ($row = mysqli_fetch_assoc($product_result)) : ?>
                                      <option value="<?php echo $row['product_name']; ?>" <?php echo $selected_product == $row['product_name'] ? 'selected' : ''; ?>>
                                  <?php echo $row['product_name']; ?>
                             </option>
                                <?php endwhile; ?>
                          </select>
                         </div>
                        </div>
                                <div class="col-md-6">
    <div class="form-group">
        <label for="product_base1">Product Received Base:</label>
        <select class="form-select" id="product_base1" name="product_base1">
            <option value="" selected disabled>Select Product Base</option>
            <?php if ($selected_product) : ?>
                <?php while ($row = mysqli_fetch_assoc($product_base_result)) : ?>
                    <option value="<?php echo $row['product_base']; ?>">
                        <?php echo $row['product_base']; ?>
                    </option>
                <?php endwhile; ?>
            <?php endif; ?>
        </select>
    </div>
                        </div>
                    <div class="col-md-6">
                             <div class="form-group">
                      <label for="product_color1">Product Received Color:</label>
                         <select class="form-select" id="product_color1" name="product_color1">
                             <option value="" selected disabled>Select Product Color</option>
                             <?php if ($selected_product) : ?>
                                 <?php while ($row = mysqli_fetch_assoc($product_color_result)) : ?>
                                 <option value="<?php echo $row['product_color']; ?>">
                        <?php echo $row['product_color']; ?>
                           </option>
                            <?php endwhile; ?>
                       <?php endif; ?>
                          </select>
                             </div>
                            </div>

                       
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
                                        <select class="form-select" id="select_challan" name="challan_no_issue">
                                            <option value="" selected disabled>Select Issue Challan No</option>
                                            <?php if (isset($challan_result_issue)) : ?>
                                                <?php while ($row = mysqli_fetch_assoc($challan_result_issue)) : ?>
                                                    <option value="<?php echo $row['challan_no_issue']; ?>"><?php echo $row['challan_no_issue']; ?></option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            
                        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name">Select Product:</label>
                                        <select class="form-select" id="product_name" name="product_name">
                                         <option value="" selected disabled>Select Product</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_base">Product Base:</label>
                                        <select class="form-select" id="product_base" name="product_base">
                                         <option value="" selected disabled>Select Product Base</option>
                                        </select>
                                     </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_color">Product Color:</label>
                                        <select class="form-select" id="product_color" name="product_color">
                                         <option value="" selected disabled>Select Product Color</option>
                                        </select>
                                    </div>
                                </div>

                              
   
                            
                         </div>
                         
                            <div class="row">
                            
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Quantity:</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_and_time">Date and Time:</label>
                                        <input type="datetime-local" class="form-control" id="date_and_time" name="date_and_time">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary me-2" name="add_product">Add</button>
                                <button type="submit" class="btn btn-success" name="submit_products">Submit</button>
                            </div>
                        </form>
                        <hr>
                        <div class="added-products">
                            <h2 class="text-center mb-3">Added Products:</h2>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                             <th>Challan No</th>
                                            <th>Stitcher Name</th>
                                            <th>Received Product Name</th>
                                            <th>Received Product Base</th>
                                            <th>Received Product Color</th>
                                            
                                            <th>Product Name</th>
                                            <th>Product Base</th>
                                            <th>Product Color</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($_SESSION['temp_products'])) : ?>
                                            <?php foreach ($_SESSION['temp_products'] as $key => $product) : ?>
                                                <tr>
                                                     <td><?php echo $product['challan_no']; ?></td>
                                                     <td><?php echo $product['stitcher_name']; ?></td>
                                                    <td><?php echo $product['product_name1']; ?></td>
                                                    <td><?php echo $product['product_base1']; ?></td>
                                                    <td><?php echo $product['product_color1']; ?></td>
                                                    <td><?php echo $product['product_name']; ?></td>
                                                    <td><?php echo $product['product_base']; ?></td>
                                                    <td><?php echo $product['product_color']; ?></td>
                                                    <td><?php echo $product['received_quantity']; ?></td>
                                                   
                                                    <td>
                                                        <form method="post" action="">
                                                            <input type="hidden" name="delete_index" value="<?php echo $key; ?>">
                                                            <button type="submit" class="btn btn-danger" name="delete_product">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
          document.getElementById("select_stitcher").addEventListener("change", function() {
            var selectedStitcher = this.value;
            fetchChallanNumbers(selectedStitcher);
        });

        function fetchChallanNumbers(selectedStitcher) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var challanSelect = document.getElementById("select_challan");
                    var challanNumbers = JSON.parse(this.responseText);
                    challanSelect.innerHTML = "<option value='' selected disabled>Select Challan No</option>";
                    challanNumbers.forEach(function(challan) {
                        var option = document.createElement("option");
                        option.value = challan;
                        option.text = challan;
                        challanSelect.appendChild(option);
                    });
                }
            };
            xhttp.open("GET", "fetch_challan_no_for_kits_printing_received.php?stitcher=" + selectedStitcher, true);
            xhttp.send();
        }
    </script>

<script>
        document.getElementById("select_challan").addEventListener("change", function() {
    var selectedChallan = this.value;
    fetchProductNames(selectedChallan);
});

function fetchProductNames(selectedChallan) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var productSelect = document.getElementById("product_name");
            var productNames = JSON.parse(this.responseText);
            productSelect.innerHTML = "<option value='' selected disabled>Select Product Name</option>";
            productNames.forEach(function(product) {
                var option = document.createElement("option");
                option.value = product;
                option.text = product;
                productSelect.appendChild(option);
            });
        }
    };
    xhttp.open("GET", "product_name_kits_print.php?challan_no=" + selectedChallan, true);
    xhttp.send();
}

</script>


<script>
document.getElementById("product_name").addEventListener("change", function() {
    var productName = this.value;
    var selectedChallan = document.getElementById("select_challan").value;
    fetchProductBase(productName, selectedChallan);
});

function fetchProductBase(productName, selectedChallan) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var productBaseSelect = document.getElementById("product_base");
            productBaseSelect.innerHTML = "<option value='' selected disabled>Select Product Base</option>"; // Clear previous options
            var productBaseData = JSON.parse(this.responseText);
            productBaseData.forEach(function(productBase) {
                var option = document.createElement("option");
                option.value = productBase;
                option.text = productBase;
                productBaseSelect.appendChild(option);
            });
        }
    };
    xhttp.open("GET", "product_base_kits_print.php?product_name=" + encodeURIComponent(productName) + "&challan_no_issue=" + encodeURIComponent(selectedChallan), true);
    xhttp.send();
}

</script>

<script>
document.getElementById("product_base").addEventListener("change", function() {
    var selectedChallan = document.getElementById("select_challan").value;
    var productName = document.getElementById("product_name").value;
    var productBase = this.value;
    fetchProductColor(selectedChallan, productName, productBase);
});

function fetchProductColor(selectedChallan, productName, productBase) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var productColorSelect = document.getElementById("product_color");
            productColorSelect.innerHTML = "<option value='' selected disabled>Select Product Color</option>"; // Clear previous options
            var productColorData = JSON.parse(this.responseText);
            productColorData.forEach(function(productColor) {
                var option = document.createElement("option");
                option.value = productColor;
                option.text = productColor;
                productColorSelect.appendChild(option);
            });
        }
    };
    xhttp.open("GET", "product_color_kit_print.php?challan_no_issue=" + encodeURIComponent(selectedChallan) + "&product_name=" + encodeURIComponent(productName) + "&product_base=" + encodeURIComponent(productBase), true);
    xhttp.send();
}

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

<script>
    // Function to update product colors based on selected product name1 and base1
    function updateProductColors1() {
        var productName1 = document.getElementById('product_name1').value;
        var productBase1 = document.getElementById('product_base1').value;

        // Make an AJAX request to fetch product colors based on product name1 and base1
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                var colors = JSON.parse(this.responseText);
                var productColorSelect1 = document.getElementById('product_color1');
                // Clear existing options
                productColorSelect1.innerHTML = '<option value="" selected disabled>Select Product Color</option>';
                // Add fetched colors as options
                colors.forEach(function(color) {
                    var option = document.createElement('option');
                    option.value = color;
                    option.text = color;
                    productColorSelect1.appendChild(option);
                });
            }
        };
        xhr.open('GET', 'fetch_product_color1.php?product_name1=' + productName1 + '&product_base1=' + productBase1, true);
        xhr.send();
    }

    // Event listeners for product name1 and product base1 change
    document.getElementById('product_name1').addEventListener('change', updateProductColors1);
    document.getElementById('product_base1').addEventListener('change', updateProductColors1);
</script>



</html>