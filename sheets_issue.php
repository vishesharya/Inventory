<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

// Default value for Labour Name
$labour_name = isset($_POST['labour_name']) ? $_POST['labour_name'] : "";
$small_sheet_color = isset($_POST['small_sheet_color']) ? $_POST['small_sheet_color'] : "";
// Logic to fetch product names from the database
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
    $product_small_query = "SELECT DISTINCT small_sheet_color FROM sheets_small_stock WHERE product_name = '$selected_product'";
    $product_small_result = mysqli_query($con, $product_small_query);
 
}


// Function to fetch current number from the database
function getCurrentNumber($con) {
    $result = mysqli_query($con, "SELECT sheets_received_temp FROM challan_temp LIMIT 1");
    $row = mysqli_fetch_assoc($result);
    return $row['sheets_received_temp'];
}

// Function to update the current number in the database
function updateCurrentNumber($con, $newNumber) {
    mysqli_query($con, "UPDATE challan_temp SET sheets_received_temp = $newNumber");
}

// Function to generate the code prefix
function generateCodePrefix($number) {
    return "KSI-SI-" . $number;
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

$errors = array();

if (isset($_POST['add_product'])) {
    // Validate input
    if (empty($_POST['product_name']) || empty($_POST['product_base']) || empty($_POST['product_color'])) {
        $errors[] = "Please fill in all fields.";
    } else {
        // Sanitize input
       
        $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
        $product_base = mysqli_real_escape_string($con, $_POST['product_base']);
        $product_color = mysqli_real_escape_string($con, $_POST['product_color']);
        
        $quantity1 = mysqli_real_escape_string($con, $_POST['quantity1']);
        $quantity2 = mysqli_real_escape_string($con, $_POST['quantity2']);
        $quantity3 = mysqli_real_escape_string($con, $_POST['quantity3']);


        
        // Fetch remaining_big_panel from sheets_product table
        $remaining_big_panel_query = "SELECT remaining_big_panel FROM sheets_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
        $remaining_big_panel_result = mysqli_query($con, $remaining_big_panel_query);
        $row = mysqli_fetch_assoc($remaining_big_panel_result);
        $remaining_big_panel = $row['remaining_big_panel'];

        if ($quantity1 > $remaining_big_panel) {
            $errors[] = "Requested quantity exceeds available stock for $product_name, $product_base, $product_color.";
        }else{
         // Update remaining_big_panel in sheets_product table
        $updated_remaining_big_panel = $remaining_big_panel - (int)$quantity1;
        $update_remaining_big_panel_query = "UPDATE sheets_product SET remaining_big_panel = $updated_remaining_big_panel WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
        mysqli_query($con, $update_remaining_big_panel_query);}

        // Fetch remaining_plain_panel from sheets_product table
        $remaining_plain_panel_query = "SELECT remaining_plain_panel FROM sheets_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
        $remaining_plain_panel_result = mysqli_query($con, $remaining_plain_panel_query);
        $row = mysqli_fetch_assoc($remaining_plain_panel_result);
        $remaining_plain_panel = $row['remaining_plain_panel'];

        if ($quantity2 > $remaining_plain_panel) {
            $errors[] = "Requested quantity exceeds available stock for $product_name, $product_base, $product_color.";
        }else{
         // Update remaining_plain_panel in sheets_product table
        $updated_remaining_plain_panel = $remaining_plain_panel - (int)$quantity2;
        $update_remaining_plain_panel_query = "UPDATE sheets_product SET remaining_plain_panel = $updated_remaining_plain_panel WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
        mysqli_query($con, $update_remaining_plain_panel_query);
        }
         
        
         if (empty($_POST['small_sheet_color'])) {
            
         $remaining_small_panel_query = "SELECT remaining_small_panel FROM sheets_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
         $remaining_small_panel_result = mysqli_query($con, $remaining_small_panel_query);
         $row = mysqli_fetch_assoc($remaining_small_panel_result);
         $remaining_small_panel = $row['remaining_small_panel'];
         if ($quantity3 > $remaining_small_panel) {
            $errors[] = "Requested quantity exceeds available stock for $product_name, $product_base, $product_color.";
        }else{
            // Update remaining_small_panel in sheets_product table
            $updated_remaining_small_panel = $remaining_small_panel - (int)$quantity3;
            $update_remaining_small_panel_query = "UPDATE sheets_product SET remaining_small_panel = $updated_remaining_small_panel WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
            mysqli_query($con, $update_remaining_small_panel_query); }
         } else {
            
         $remaining_small_panel_query = "SELECT small_sheet_balance FROM sheets_small_stock WHERE product_name = '$product_name'";
         $remaining_small_panel_result = mysqli_query($con, $remaining_small_panel_query);
         $row = mysqli_fetch_assoc($remaining_small_panel_result);
         $remaining_small_panel = $row['small_sheet_balance'];
         if ($quantity3 > $remaining_small_panel) {
            $errors[] = "Requested quantity exceeds available stock for $product_name, $product_base, $product_color.";
        }else{
        // Update remaining_small_panel in sheets_product table
        $updated_remaining_small_panel = $remaining_small_panel - (int)$quantity3;
        $update_remaining_small_panel_query = "UPDATE sheets_small_stock SET small_sheet_balance = $updated_remaining_small_panel WHERE product_name = '$product_name'";
        mysqli_query($con, $update_remaining_small_panel_query);}

      }
 

         // Check if requested quantity exceeds available stock
        if ($quantity1 > $remaining_big_panel || $quantity2 > $remaining_plain_panel || $quantity3 > $remaining_small_panel) {
            $errors[] = " ";
        } else {
            // Insert data into temporary session storage
            $temp_product = array(
                'challan_no' => $challan_no,
                'labour_name' => $labour_name,
                'small_sheet_color' => $small_sheet_color,
                'product_name' => $product_name,
                'product_base' => $product_base,
                'product_color' => $product_color,
                'quantity1' => $quantity1,
                'quantity2' => $quantity2,
                'quantity3' => $quantity3,
            );
            $_SESSION['temp_products'][] = $temp_product;
        }
    }
}

// Check if delete button is clicked
if (isset($_POST['delete_product'])) {
    // Get the index of the product to be deleted
    $delete_index = $_POST['delete_index'];

    // Get the product details to update remaining_quantity in sheets_product table
    $deleted_product = $_SESSION['temp_products'][$delete_index];
    $product_name = mysqli_real_escape_string($con, $deleted_product['product_name']);
    $product_base = mysqli_real_escape_string($con, $deleted_product['product_base']);
    $small_sheet_color = isset($deleted_product['small_sheet_color']) ? mysqli_real_escape_string($con, $deleted_product['small_sheet_color']) : '';

    $product_color= mysqli_real_escape_string($con, $deleted_product['product_color']);
    $deleted_quantity1 = mysqli_real_escape_string($con, $deleted_product['quantity1']);
    $deleted_quantity2 = mysqli_real_escape_string($con, $deleted_product['quantity2']);
    $deleted_quantity3 = mysqli_real_escape_string($con, $deleted_product['quantity3']);
   


     // Fetch remaining_big_panel from sheets_product table
     $remaining_big_panel_query = "SELECT remaining_big_panel FROM sheets_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
     $remaining_big_panel_result = mysqli_query($con, $remaining_big_panel_query);
     $row = mysqli_fetch_assoc($remaining_big_panel_result);
     $remaining_big_panel = $row['remaining_big_panel'];

    
      // Update remaining_big_panel in sheets_product table
     $updated_remaining_big_panel = $remaining_big_panel + (int)$deleted_quantity1;
     $update_remaining_big_panel_query = "UPDATE sheets_product SET remaining_big_panel = $updated_remaining_big_panel WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
     mysqli_query($con, $update_remaining_big_panel_query);

     // Fetch remaining_plain_panel from sheets_product table
     $remaining_plain_panel_query = "SELECT remaining_plain_panel FROM sheets_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
     $remaining_plain_panel_result = mysqli_query($con, $remaining_plain_panel_query);
     $row = mysqli_fetch_assoc($remaining_plain_panel_result);
     $remaining_plain_panel = $row['remaining_plain_panel'];

    
      // Update remaining_plain_panel in sheets_product table
     $updated_remaining_plain_panel = $remaining_plain_panel + (int)$deleted_quantity2;
     $update_remaining_plain_panel_query = "UPDATE sheets_product SET remaining_plain_panel = $updated_remaining_plain_panel WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
     mysqli_query($con, $update_remaining_plain_panel_query);

     

     if (empty($_POST['small_sheet_color'])) {
        $remaining_small_panel_query = "SELECT remaining_small_panel FROM sheets_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
        $remaining_small_panel_result = mysqli_query($con, $remaining_small_panel_query);
        $row = mysqli_fetch_assoc($remaining_small_panel_result);
        $remaining_small_panel = $row['remaining_small_panel'];
        if ($quantity3 > $remaining_small_panel) {
            $errors[] = "Requested quantity exceeds available stock for $product_name, $product_base, $product_color.";
        } else {
            // Update remaining_small_panel in sheets_product table
            $updated_remaining_small_panel = $remaining_small_panel - (int)$quantity3;
            $update_remaining_small_panel_query = "UPDATE sheets_product SET remaining_small_panel = $updated_remaining_small_panel WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
            mysqli_query($con, $update_remaining_small_panel_query);
        }
    } else {
        $remaining_small_panel_query = "SELECT small_sheet_balance FROM sheets_small_stock WHERE product_name = '$product_name'";
        $remaining_small_panel_result = mysqli_query($con, $remaining_small_panel_query);
        $row = mysqli_fetch_assoc($remaining_small_panel_result);
        $remaining_small_panel = $row['small_sheet_balance'];
        if ($quantity3 > $remaining_small_panel) {
            $errors[] = "Requested quantity exceeds available stock for $product_name.";
        } else {
            // Update remaining_small_panel in sheets_small_stock table
            $updated_remaining_small_panel = $remaining_small_panel - (int)$quantity3;
            $update_remaining_small_panel_query = "UPDATE sheets_small_stock SET small_sheet_balance = $updated_remaining_small_panel WHERE product_name = '$product_name'";
            mysqli_query($con, $update_remaining_small_panel_query);
        }
    }
    
    

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
            $labour_name = mysqli_real_escape_string($con, $product['labour_name']);
            $small_sheet_color = mysqli_real_escape_string($con, $product['small_sheet_color']);
            $product_name = mysqli_real_escape_string($con, $product['product_name']);
            $product_base = mysqli_real_escape_string($con, $product['product_base']);
            $product_color = mysqli_real_escape_string($con, $product['product_color']);
            $quantity1 = mysqli_real_escape_string($con, $product['quantity1']);
            $quantity2 = mysqli_real_escape_string($con, $product['quantity2']);
            $quantity3 = mysqli_real_escape_string($con, $product['quantity3']);
        

            // Insert product into the database
            $insert_query = "INSERT INTO sheets_issue (challan_no, labour_name , product_name, product_base, product_color, quantity1, quantity2, quantity3, small_panel_color) 
                            VALUES ('$challan_no', '$product_name','$labour_name', '$product_base', '$product_color', '$quantity1' , '$quantity2', '$quantity3', '$small_sheet_color')";
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
        }
        .btn-group {
            margin-top: 1.5rem;
            justify-content: center;
        }
        .table {
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-4">Sheets Issue</h1>
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
                                        <label for="challan_no">Challan No:</label>
                                        <input type="text" class="form-control" id="challan_no" name="challan_no" value="<?php echo $challan_no; ?>" readonly>
                                    </div>
                                </div>
                               
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="labour_name">Labour Name:</label>
                                        <input type="text" class="form-control" id="labour_name" name="labour_name" value="<?php echo $labour_name; ?>" placeholder="Enter Labour Name">
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
                            <div class="row">
                                
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Big Panel:</label>
                                        <input type="number" class="form-control" id="quantity1" name="quantity1" placeholder="Enter Quantity">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Plain Panel:</label>
                                        <input type="number" class="form-control" id="quantity2" name="quantity2" placeholder="Enter Quantity">
                                    </div>
                                </div>
                                
                                
                            
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="small_sheet_color">Small Panel Color:</label>
                                        <select class="form-select" id="small_sheet_color" name="small_sheet_color">
                                            <option value="" selected disabled>Select Panel Color</option>
                                            <?php if ($selected_product) : ?>
                                                <?php while ($row = mysqli_fetch_assoc($product_small_result)) : ?>
                                                    <option value="<?php echo $row['small_sheet_color']; ?>"><?php echo $row['small_sheet_color']; ?></option>
                                                <?php endwhile; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Small Panel:</label>
                                        <input type="number" class="form-control" id="quantity3" name="quantity3" placeholder="Enter Quantity">
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
                                            <th>Product Name</th>
                                            <th>Product Base</th>
                                            <th>Product Color</th>
                                            <th>Big Panel</th>
                                            <th>Plain Panel</th>
                                            <th>Small Panel Color </th>
                                            <th>Small Panel</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($_SESSION['temp_products'])) : ?>
                                            <?php foreach ($_SESSION['temp_products'] as $key => $product) : ?>
                                                <tr>
                                                    <td><?php echo $product['challan_no']; ?></td>
                                            
                                                    <td><?php echo $product['product_name']; ?></td>
                                                    <td><?php echo $product['product_base']; ?></td>
                                                    <td><?php echo $product['product_color']; ?></td>
                                                    <td><?php echo $product['quantity1']; ?></td>
                                                    <td><?php echo $product['quantity2']; ?></td>
                                                    <td><?php echo $product['small_sheet_color']; ?></td>
                                                    <td><?php echo $product['quantity3']; ?></td>
                                                   
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
