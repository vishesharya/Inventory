<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';


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
$stitcher_query = "SELECT DISTINCT stitcher_name FROM kits_job_work WHERE status = 0 ORDER BY stitcher_name ASC";
$stitcher_result = mysqli_query($con, $stitcher_query);

// Fetch associated challan numbers for selected stitcher
if (isset($_POST['stitcher_name'])) {
    $selected_stitcher = mysqli_real_escape_string($con, $_POST['stitcher_name']);
    $challan_query_issue = "SELECT DISTINCT  challan_no_issue FROM kits_job_work WHERE stitcher_name = '$selected_stitcher' AND status = 0";
    $challan_result_issue = mysqli_query($con, $challan_query_issue);
}

// Fetch product names based on selected stitcher and challan number
if (isset($_POST['challan_no_issue'])) {
    $selected_challan = mysqli_real_escape_string($con, $_POST['challan_no_issue']);
    $selected_stitcher = mysqli_real_escape_string($con, $_POST['stitcher_name']); // Added this line

    // Query to fetch products based on selected stitcher, challan number, and status = 0
    $product_query = "SELECT DISTINCT product_name,product_base,product_color FROM kits_job_work WHERE stitcher_name = '$selected_stitcher' AND challan_no_issue = '$selected_challan' AND status = 0";
    $product_result = mysqli_query($con, $product_query);
}





if (isset($_POST['add_product'])) {
    // Validate input
    if (empty($_POST['product_name']) || empty($_POST['product_base']) || empty($_POST['product_color']) || empty($_POST['date_and_time'])) {
        $errors[] = "Please fill in all fields.";
    } else {
        $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : "";
        $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
        $product_base = mysqli_real_escape_string($con, $_POST['product_base']);
        $product_color = mysqli_real_escape_string($con, $_POST['product_color']);
        $quantity = mysqli_real_escape_string($con, $_POST['quantity']);
      
    

        // Fetch existing issue quantity
        $issue_quantity_query = "SELECT issue_quantity FROM kits_job_work WHERE challan_no_issue = '$selected_challan' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
        $issue_quantity_result = mysqli_query($con, $issue_quantity_query);
        if ($issue_quantity_result && mysqli_num_rows($issue_quantity_result) > 0) {
            $issue_quantity_row = mysqli_fetch_assoc($issue_quantity_result);
            $existing_issue_quantity = $issue_quantity_row['issue_quantity'];

            // Check if total exceeds existing issue quantity
            if ($quantity > $existing_issue_quantity) {
                $errors[] = "The entered quantity exceeds the balance quantity.";
            } else {

             // Fetch existing remaining quantity 
            $existing_remaining_quantity_query = "SELECT remaining_quantity FROM kits_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
            $existing_remaining_quantity_result = mysqli_query($con, $existing_remaining_quantity_query);
            $row_quantity = mysqli_fetch_assoc($existing_remaining_quantity_result);
            $existing_remaining_quantity = $row_quantity['remaining_quantity'];

            // Calculate new remaining quantity
            $new_remaining_quantity = $existing_remaining_quantity;
            $new_remaining_quantity = $existing_remaining_quantity + $quantity;

            // Update remaining quantity in products table 
            $update_remaining_quantity_query = "UPDATE kits_product SET remaining_quantity = '$new_remaining_quantity' WHERE product_name = '$product_name' AND product_base = '$product_base' AND  product_color = '$product_color'";
            $update_remaining_quantity_result = mysqli_query($con, $update_remaining_quantity_query);

                // Update issue quantity in the database
                $updated_issue_quantity = max(0, $existing_issue_quantity - $quanitty); // Ensure issue quantity doesn't go negative
                $update_issue_quantity_query = "UPDATE kits_job_work SET issue_quantity = '$updated_issue_quantity' WHERE challan_no_issue = '$selected_challan' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
                $update_issue_quantity_result = mysqli_query($con, $update_issue_quantity_query);


                if ($update_issue_quantity_result) {
                    // Check if issue quantity became 0 and update status accordingly
                    if ($updated_issue_quantity == 0) {
                        $update_status_query = "UPDATE kits_job_work SET status = 1 WHERE challan_no_issue = '$selected_challan' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
                        $update_status_result = mysqli_query($con, $update_status_query);

                        if (!$update_status_result) {
                            $errors[] = "Failed to update status in the database.";
                        }
                    }


            if (!$update_remaining_quantity_result) {
                $errors[] = "Failed to update remaining quantity in the database.";
            }

                    // Insert data into temporary session storage
                    $temp_product = array(
                        'challan_no' => $challan_no,
                        'stitcher_name' => $stitcher_name,
                        'challan_no_issue' => $selected_challan,
                        'product_name' => $product_name,
                        'product_base' => $product_base,
                        'product_color' => $product_color,
                        'quantity' => $quantity,
                        'date_and_time' => isset($_POST['date_and_time']) ? $_POST['date_and_time'] : date('Y-m-d H:i:s')
                        
                        
                    );

                    $_SESSION['temp_products'][] = $temp_product;

                    // Redirect to the same page to reset form fields
                    header('Location: ' . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    $errors[] = "Failed to update issue quantity in the database.";
                 }
             }
         }
     }
  }


     

if (isset($_POST['delete_product'])) {
    $delete_index = $_POST['delete_index']; 

    if (isset($_SESSION['temp_products'][$delete_index])) {
        $temp_product = $_SESSION['temp_products'][$delete_index];
        $stitcher_name = mysqli_real_escape_string($con, $temp_product['stitcher_name']);
        $product_name = mysqli_real_escape_string($con, $temp_product['product_name']);
        $product_base = mysqli_real_escape_string($con, $temp_product['product_base']);
        $product_color = mysqli_real_escape_string($con, $temp_product['product_color']);
        $quantity = intval($temp_product['quantity']);

       
        // Fetch existing remaining quantity for Ist Company Ist
        $existing_remaining_quantity_query = "SELECT remaining_quantity FROM kits_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
        $existing_remaining_quantity_result = mysqli_query($con, $existing_remaining_quantity_query);
        $row_quantity = mysqli_fetch_assoc($existing_remaining_quantity_result);
        $existing_remaining_quantity = $row_quantity['remaining_quantity'];

        // Calculate new remaining quantity
        $new_remaining_quantity = max(0, $existing_remaining_quantity - $quantity);
       
        // Update remaining quantity in products table for Ist Company Ist
        $update_remaining_quantity = "UPDATE kits_product SET remaining_quantity = '$new_remaining_quantity' WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
        $update_remaining_quantity_result = mysqli_query($con, $update_remaining_quantity_query);


        if ($update_remaining_quantity_result) {
            // Fetch existing issue quantity
            $issue_quantity_query = "SELECT issue_quantity FROM kits_job_work WHERE challan_no_issue = '{$temp_product['challan_no_issue']}' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
            $issue_quantity_result = mysqli_query($con, $issue_quantity_query);
            if ($issue_quantity_result && mysqli_num_rows($issue_quantity_result) > 0) {
                $issue_quantity_row = mysqli_fetch_assoc($issue_quantity_result);
                $existing_issue_quantity = $issue_quantity_row['issue_quantity'];

                // Update issue quantity in the database
                $updated_issue_quantity = $existing_issue_quantity + $quantity;
                $update_issue_quantity_query = "UPDATE kits_job_work SET issue_quantity = '$updated_issue_quantity' WHERE challan_no_issue = '{$temp_product['challan_no_issue']}' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
                $update_issue_quantity_result = mysqli_query($con, $update_issue_quantity_query);

                if ($update_issue_quantity_result) {
                    // Check if issue quantity is now more than 0 and update status accordingly
                    if ($updated_issue_quantity > 0) {
                        $update_status_query = "UPDATE kits_job_work SET status = 0 WHERE challan_no_issue = '{$temp_product['challan_no_issue']}' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
                        $update_status_result = mysqli_query($con, $update_status_query);

                        if (!$update_status_result) {
                            $errors[] = "Failed to update status in the database.";
                        }
                    }

                    // Remove the product from the session
                    unset($_SESSION['temp_products'][$delete_index]);
                    // Reset array keys to maintain consecutive numbering
                    $_SESSION['temp_products'] = array_values($_SESSION['temp_products']);

                    // Redirect to prevent form resubmission
                    header("Location: {$_SERVER['REQUEST_URI']}");
                    exit();
                } else {
                    $errors[] = "Failed to update issue quantity in the database.";
                }
            }
        } else {
            $errors[] = "Failed to update remaining quantity in the database.";
        }
    }
}


// Store added products in the database when "Submit" button is clicked
if (isset($_POST['submit_form'])) {
    $temp_products = isset($_SESSION['temp_products']) ? $_SESSION['temp_products'] : [];

    if (empty($temp_products)) {
        $errors[] = "Please add at least one product.";
    } else {
        foreach ($temp_products as $product) {
            
            $stitcher_name = mysqli_real_escape_string($con, $product['stitcher_name']);
            $product_name = mysqli_real_escape_string($con, $product['product_name']);
            $product_base = mysqli_real_escape_string($con, $product['product_base']);
            $product_color = mysqli_real_escape_string($con, $product['product_color']);
            $quantity = mysqli_real_escape_string($con, $product['quantity']);
            $date_and_time = mysqli_real_escape_string($con, $product['date_and_time']);

            // Insert product into the database
            $insert_query = "INSERT INTO kits_return ( challan_no, stitcher_name, product_name, product_base, product_color, quantity, date_and_time) 
            VALUES ( '$challan_no', '$stitcher_name', '$product_name', '$product_base', '$product_color', '$quantity', '$date_and_time')";
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
    <title>Football Receiving Form</title>
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
        }
        #printbtn{
            display:flex;
            justify-content:space-between;
            align-items: flex-end;
        }
        .error-input {
            border: 1px solid red;
        } 
        #btn_grp{
            display: flex;
            gap: 15px;
        }
        .btn-group{
            display: flex;
            gap: 1rem;
        }
    </style>
</head>
<body>
    <?php include('include/football_nav.php'); ?>
    <div class="container-fluid mt-5"> <!-- Changed container to container-fluid -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-4">Kits Returns Form</h1>
                        <?php if (!empty($errors)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?php foreach ($errors as $error) : ?>
                                    <?php echo $error; ?><br>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <!-- New form to select stitcher, associated challan number, and product details -->
                        <form method="post" action="">
                            <div class="row">
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
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_name">Product Name:</label>
                                        <select class="form-select" id="product_name" name="product_name">
                                            <option value="" selected disabled>Select Product Name</option>
                                            <?php if (isset($product_result)) : ?>
                                                <?php while ($row = mysqli_fetch_assoc($product_result)) : ?>
                                                    <option value="<?php echo $row['product_name']; ?>"><?php echo $row['product_name']; ?></option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="product_base">Product Base:</label>
                                        <select class="form-select" id="product_base" name="product_base" >
                                            <option value="" selected disabled>Select Product Base</option>
                                            <?php if (isset($product_base_result)) : ?>
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
                                        <select class="form-select" id="product_color" name="product_color" >
                                            <option value="" selected disabled>Select Product Color</option>
                                            <?php if (isset($product_color_result)) : ?>
                                                <?php while ($row = mysqli_fetch_assoc($product_color_result)) : ?>
                                                    <option value="<?php echo $row['product_color']; ?>"><?php echo $row['product_color']; ?></option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                          
                             
                              
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Enter Returns Quantity:</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" >
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date_and_time">Date and Time:</label>
                                        <input type="datetime-local" class="form-control" id="date_and_time" name="date_and_time">
                                    </div> 
                                </div>
                               
                               
                            </div>

                            <div id="printbtn" class="btn-group">
                                <div>
                                <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
                                <button type="submit" class="btn btn-primary" name="submit_form">Submit</button>
                                </div>
                               
                                  <div>
                                      <a  href="print_football_receive_slip.php" >Print Issue  Slip </a>
                                  </div>
                            
                            </div>
                           
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Display table with added products -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card mt-5">
                    <div class="added-products">
                        <h2 class="text-center mb-3">Added Products:</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Challan No</th>
                                        <th>Stitcher Name</th>
                                        <th>Issue Challan No</th>
                                        <th>Product Name</th>
                                        <th>Product Base</th>
                                        <th>Product Color</th>
                                        <th>Returns Quantity</th>
                                      
                                        <th>Date</th>
                                        <th>Delete Product</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($_SESSION['temp_products'])) : ?>
                                        <?php foreach ($_SESSION['temp_products'] as $key => $product) : ?>
                                            <tr>
                                                <td><?php echo $product['challan_no']; ?></td>
                                                <td><?php echo $product['stitcher_name']; ?></td>
                                                <td><?php echo $product['challan_no_issue']; ?></td>
                                                
                                                <td><?php echo $product['product_name']; ?></td>
                                                <td><?php echo $product['product_base']; ?></td>
                                                <td><?php echo $product['product_color']; ?></td>
                                                <td><?php echo $product['quantity']; ?></td>
                                                <td><?php echo $product['date_and_time']; ?></td>
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
            xhttp.open("GET", "fetch_challan_numbers.php?stitcher=" + selectedStitcher, true);
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
    xhttp.open("GET", "fetch_product_name_football.php?challan_no=" + selectedChallan, true);
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
    xhttp.open("GET", "fetch-product_base_football.php?product_name=" + encodeURIComponent(productName) + "&challan_no_issue=" + encodeURIComponent(selectedChallan), true);
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
    xhttp.open("GET", "fetch_product_color_football.php?challan_no_issue=" + encodeURIComponent(selectedChallan) + "&product_name=" + encodeURIComponent(productName) + "&product_base=" + encodeURIComponent(productBase), true);
    xhttp.send();
}

</script>

</body>
</html>