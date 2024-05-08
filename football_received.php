<?php
session_start();
include_once 'include/connection.php';
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
    $product_query = "SELECT DISTINCT product_name FROM kits_job_work WHERE stitcher_name = '$selected_stitcher' AND challan_no_issue = '$selected_challan'";
    $product_result = mysqli_query($con, $product_query);
}

// Fetch product bases based on selected stitcher, challan number, and product name
if (isset($_POST['product_name'])) {
    $selected_product = mysqli_real_escape_string($con, $_POST['product_name']);
    $product_base_query = "SELECT DISTINCT product_base FROM kits_job_work WHERE stitcher_name = '$selected_stitcher' AND challan_no_issue = '$selected_challan' AND product_name = '$selected_product'";
    $product_base_result = mysqli_query($con, $product_base_query);
}

// Fetch product colors based on selected stitcher, challan number, product name, and product base
if (isset($_POST['product_base'])) {
    $selected_product_base = mysqli_real_escape_string($con, $_POST['product_base']);
    $product_color_query = "SELECT DISTINCT product_color FROM kits_job_work WHERE stitcher_name = '$selected_stitcher' AND challan_no_issue = '$selected_challan' AND product_name = '$selected_product' AND product_base = '$selected_product_base'";
    $product_color_result = mysqli_query($con, $product_color_query);
}



if (isset($_POST['add_product'])) {
    // Validate input
    if (empty($_POST['product_name']) || empty($_POST['product_base']) || empty($_POST['product_color'])) {
        $errors[] = "Please fill in all fields.";
    } else {
        $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : "";
        $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
        $product_base = mysqli_real_escape_string($con, $_POST['product_base']);
        $product_color = mysqli_real_escape_string($con, $_POST['product_color']);
        $stitcher_ist_company_ist = mysqli_real_escape_string($con, $_POST['stitcher_ist_company_ist']);
        $stitcher_iind_company_iind = mysqli_real_escape_string($con, $_POST['stitcher_iind_company_iind']);
        $stitcher_iind_company_ist = mysqli_real_escape_string($con, $_POST['stitcher_iind_company_ist']);
        $stitcher_ist_company_iind = mysqli_real_escape_string($con, $_POST['stitcher_ist_company_iind']);

        
        $is_duplicate = false;
        foreach ($_SESSION['temp_products'] as $temp_product) {
            if ($temp_product['product_name'] === $product_name && 
            $temp_product['product_base'] === $product_base && 
            $temp_product['product_color'] === $product_color) {
                $is_duplicate = true;
                break;
            }
        }
        // Check for duplicate product

        if ($is_duplicate) {
            $errors[] = "This product already exists in the list.";
        } else {

        $stitcher_ist_company_ist = isset($_POST['stitcher_ist_company_ist']) ? intval($_POST['stitcher_ist_company_ist']) : 0;
        $stitcher_iind_company_iind = isset($_POST['stitcher_iind_company_iind']) ? intval($_POST['stitcher_iind_company_iind']) : 0;
        $stitcher_iind_company_ist = isset($_POST['stitcher_iind_company_ist']) ? intval($_POST['stitcher_iind_company_ist']) : 0;
        $stitcher_ist_company_iind = isset($_POST['stitcher_ist_company_iind']) ? intval($_POST['stitcher_ist_company_iind']) : 0;
        
        
        // Calculate total
        $total = $stitcher_ist_company_ist + $stitcher_iind_company_iind + $stitcher_iind_company_ist + $stitcher_ist_company_iind;
        
        // If input is empty, default to zero
        if ($_POST['stitcher_ist_company_ist'] === '' && isset($_POST['stitcher_ist_company_ist'])) {
            $stitcher_ist_company_ist = 0;
        }
        if ($_POST['stitcher_iind_company_iind'] === '' && isset($_POST['stitcher_iind_company_iind'])) {
            $stitcher_iind_company_iind = 0;
        }
        if ($_POST['stitcher_iind_company_ist'] === '' && isset($_POST['stitcher_iind_company_ist'])) {
            $stitcher_iind_company_ist = 0;
        }
        if ($_POST['stitcher_ist_company_iind'] === '' && isset($_POST['stitcher_ist_company_iind'])) {
            $stitcher_ist_company_iind = 0;
        }


        // Fetch existing issue quantity
        $issue_quantity_query = "SELECT issue_quantity FROM kits_job_work WHERE challan_no_issue = '$selected_challan' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base'";
        $issue_quantity_result = mysqli_query($con, $issue_quantity_query);
        if ($issue_quantity_result && mysqli_num_rows($issue_quantity_result) > 0) {
            $issue_quantity_row = mysqli_fetch_assoc($issue_quantity_result);
            $existing_issue_quantity = $issue_quantity_row['issue_quantity'];

            // Check if total exceeds existing issue quantity
            if ($total > $existing_issue_quantity) {
                $errors[] = "The entered quantity exceeds the balance quantity.";
            } else {
                // Update issue quantity in the database
                $updated_issue_quantity = max(0, $existing_issue_quantity - $total); // Ensure issue quantity doesn't go negative
                $update_issue_quantity_query = "UPDATE kits_job_work SET issue_quantity = '$updated_issue_quantity' WHERE challan_no_issue = '$selected_challan' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base'";
                $update_issue_quantity_result = mysqli_query($con, $update_issue_quantity_query);

                if ($update_issue_quantity_result) {
                    // Check if issue quantity became 0 and update status accordingly
                    if ($updated_issue_quantity == 0) {
                        $update_status_query = "UPDATE kits_job_work SET status = 1 WHERE challan_no_issue = '$selected_challan' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base'";
                        $update_status_result = mysqli_query($con, $update_status_query);

                        if (!$update_status_result) {
                            $errors[] = "Failed to update status in the database.";
                        }
                    }

                    // Insert data into temporary session storage
                    $temp_product = array(
                        'challan_no' => $challan_no,
                        'stitcher_name' => $stitcher_name,
                        'challan_no_issue' => $selected_challan,
                        'product_name' => $product_name,
                        'product_base' => $product_base,
                        'product_color' => $product_color,
                        'stitcher_ist_company_ist' => $stitcher_ist_company_ist,
                        'stitcher_iind_company_iind' => $stitcher_iind_company_iind,
                        'stitcher_iind_company_ist' => $stitcher_iind_company_ist,
                        'stitcher_ist_company_iind' => $stitcher_ist_company_iind,
                        'total' => $total,
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

}
     

    if (isset($_POST['delete_product'])) {
        $delete_index = $_POST['delete_index']; // Index of the product in the session array
    
        // Fetch product details from the session
        if (isset($_SESSION['temp_products'][$delete_index])) {
            $product_to_delete = $_SESSION['temp_products'][$delete_index];
    
            // Variables for database query
            $challan_no_issue = $product_to_delete['challan_no_issue'];
            $stitcher_name = $product_to_delete['stitcher_name'];
            $product_name = $product_to_delete['product_name'];
            $product_base = $product_to_delete['product_base'];
            $total = $product_to_delete['total'];
    
            // Fetch current issue quantity from the database
            $issue_quantity_query = "SELECT issue_quantity FROM kits_job_work WHERE challan_no_issue = '$challan_no_issue' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base'";
            $issue_quantity_result = mysqli_query($con, $issue_quantity_query);
            if ($issue_quantity_result && mysqli_num_rows($issue_quantity_result) > 0) {
                $issue_quantity_row = mysqli_fetch_assoc($issue_quantity_result);
                $current_issue_quantity = $issue_quantity_row['issue_quantity'];
                
                // Calculate new issue quantity after adding back the deleted product's quantity
                $updated_issue_quantity = $current_issue_quantity + $total;
    
                // Update the issue quantity in the database
                $update_issue_quantity_query = "UPDATE kits_job_work SET issue_quantity = '$updated_issue_quantity' WHERE challan_no_issue = '$challan_no_issue' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base'";
                $update_issue_quantity_result = mysqli_query($con, $update_issue_quantity_query);
    
                // Update status if the updated issue quantity is greater than 0
                if ($updated_issue_quantity > 0) {
                    $update_status_query = "UPDATE kits_job_work SET status = 0 WHERE challan_no_issue = '$challan_no_issue' AND stitcher_name = '$stitcher_name' AND product_name = '$product_name' AND product_base = '$product_base'";
                    $update_status_result = mysqli_query($con, $update_status_query);
                }
            }
    
            // Remove the product from the session
            unset($_SESSION['temp_products'][$delete_index]);
            $_SESSION['temp_products'] = array_values($_SESSION['temp_products']); // Re-index array
    
            // Redirect to avoid form resubmission issues
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
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
            $stitcher_ist_company_ist = mysqli_real_escape_string($con, $product['stitcher_ist_company_ist']);

            $total = mysqli_real_escape_string($con, $product['total']);
            $stitcher_iind_company_iind = mysqli_real_escape_string($con, $product['stitcher_iind_company_iind']);
            $stitcher_iind_company_ist = mysqli_real_escape_string($con, $product['stitcher_iind_company_ist']);
            $stitcher_ist_company_ist = mysqli_real_escape_string($con, $product['stitcher_ist_company_ist']);
            $stitcher_ist_company_iind = mysqli_real_escape_string($con, $product['stitcher_ist_company_iind']);
            $date_and_time = mysqli_real_escape_string($con, $product['date_and_time']);

            // Insert product into the database
            $insert_query = "INSERT INTO football_received ( challan_no, stitcher_name, product_name, product_base, product_color, S_Ist_C_Ist, S_IInd_C_IInd, S_IInd_C_Ist, S_Ist_C_IInd, total) 
            VALUES ( '$challan_no', '$stitcher_name', '$product_name', '$product_base', '$product_color', '$stitcher_ist_company_ist', '$stitcher_iind_company_iind', '$stitcher_iind_company_ist', '$stitcher_ist_company_iind', '$total')";
             $insert_result = mysqli_query($con, $insert_query);


       
       

            if (!$insert_result) {
                $errors[] = "Failed to store data in the database.";
            }
        }


             // Update remaining quantity in products table
             $stitcher_ist_company_ist = $product['stitcher_ist_company_ist'];
         

             $stitcher_ist_company_ist = $product['stitcher_ist_company_ist'];
             $stitcher_iind_company_ist = $product['stitcher_iind_company_ist'];
             $stitcher_iind_company_iind = $product['stitcher_iind_company_iind'];
             $stitcher_ist_company_iind = $product['stitcher_ist_company_iind'];
 
             // Fetch existing remaining quantity for Ist Company Ist
             $existing_remaining_quantity_ist_company_ist_query = "SELECT remaining_quantity FROM products WHERE product_name = '$product_name' AND product_base = '$product_base'";
             $existing_remaining_quantity_ist_company_ist_result = mysqli_query($con, $existing_remaining_quantity_ist_company_ist_query);
             $row_ist_company_ist = mysqli_fetch_assoc($existing_remaining_quantity_ist_company_ist_result);
             $existing_remaining_quantity_ist_company_ist = $row_ist_company_ist['remaining_quantity'];
 
             // Fetch existing remaining quantity for IInd Company IInd
             $existing_remaining_quantity_iind_company_iind_query = "SELECT remaining_quantity FROM products WHERE product_name = '$product_name IIND' AND product_base = 'N/A' AND product_color = 'N/A'";
             $existing_remaining_quantity_iind_company_iind_result = mysqli_query($con, $existing_remaining_quantity_iind_company_iind_query);
             $row_iind_company_iind = mysqli_fetch_assoc($existing_remaining_quantity_iind_company_iind_result);
             $existing_remaining_quantity_iind_company_iind = $row_iind_company_iind['remaining_quantity'];
 
             // Calculate new remaining quantity
             $new_remaining_quantity_ist_company_ist = $existing_remaining_quantity_ist_company_ist + $stitcher_ist_company_ist + $stitcher_iind_company_ist;
             $new_remaining_quantity_iind_company_iind = $existing_remaining_quantity_iind_company_iind + $stitcher_iind_company_iind + $stitcher_ist_company_iind ;
 
             // Update remaining quantity in products table for Ist Company Ist
             $update_remaining_quantity_ist_company_ist_query = "UPDATE products SET remaining_quantity = '$new_remaining_quantity_ist_company_ist' WHERE product_name = '$product_name' AND product_base = '$product_base'";
             $update_remaining_quantity_ist_company_ist_result = mysqli_query($con, $update_remaining_quantity_ist_company_ist_query);
 
             // Update remaining quantity in products table for IInd Company IInd
             $update_remaining_quantity_iind_company_iind_query = "UPDATE products SET remaining_quantity = '$new_remaining_quantity_iind_company_iind' WHERE product_name = '$product_name IIND' AND product_base = 'N/A' AND product_color = 'N/A'";
             $update_remaining_quantity_iind_company_iind_result = mysqli_query($con, $update_remaining_quantity_iind_company_iind_query);
 
             if (!$update_remaining_quantity_ist_company_ist_result || !$update_remaining_quantity_iind_company_iind_result) {
                 $errors[] = "Failed to update remaining quantity in the database.";
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
            gap: 1rem;
        }
        .btn-group{
            display: flex;
            gap: 1rem;
        }
    </style>
</head>
<body>
    <?php include('include/nav.php'); ?>
    <div class="container-fluid mt-5"> <!-- Changed container to container-fluid -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h1 class="h4 text-center mb-4">Football Receiving Form</h1>
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
                                        <label for="stitcher_ist_company_ist">Stitcher Ist Company Ist:</label>
                                        <input type="number" class="form-control" id="stitcher_ist_company_ist" name="stitcher_ist_company_ist" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stitcher_ist_company_iind">Stitcher Ist Company IInd:</label>
                                        <input type="number" class="form-control" id="stitcher_ist_company_iind" name="stitcher_ist_company_iind" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stitcher_iind_company_ist">Stitcher IInd Company Ist:</label>
                                        <input type="number" class="form-control" id="stitcher_iind_company_ist" name="stitcher_iind_company_ist" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stitcher_iind_company_iind">Stitcher IInd Company IInd:</label>
                                        <input type="number" class="form-control" id="stitcher_iind_company_iind" name="stitcher_iind_company_iind" >
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
                                        <th>Stitcher Ist Company Ist</th>
                                        <th>Stitcher IInd Company IInd</th>
                                        <th>Stitcher IInd Company Ist</th>
                                        <th>Stitcher Ist Company IInd</th>
                                        <th>Total</th>
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
                                                <td><?php echo $product['stitcher_ist_company_ist']; ?></td>
                                                <td><?php echo $product['stitcher_iind_company_iind']; ?></td>
                                                <td><?php echo $product['stitcher_iind_company_ist']; ?></td>
                                                <td><?php echo $product['stitcher_ist_company_iind']; ?></td>
                                                <td><?php echo $product['stitcher_ist_company_ist'] + $product['stitcher_iind_company_iind'] + $product['stitcher_iind_company_ist'] + $product['stitcher_ist_company_iind']; ?></td>
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

    <!-- Add this JavaScript code to dynamically update the dropdowns -->
<script>
    document.getElementById("select_stitcher").addEventListener("change", function() {
        var selectedStitcher = this.value;
        fetchChallanNumbers(selectedStitcher);
    });

    document.getElementById("select_challan").addEventListener("change", function() {
        var selectedStitcher = document.getElementById("select_stitcher").value;
        var selectedChallan = this.value;
        fetchProductData(selectedStitcher, selectedChallan);
    });

    function fetchProductData(selectedStitcher, selectedChallan) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var responseData = JSON.parse(this.responseText);

                // Update product name dropdown
                var productNameSelect = document.getElementById("product_name");
                productNameSelect.innerHTML = "<option value='' selected disabled>Select Product Name</option>";
                responseData.productNames.forEach(function(productName) {
                    var option = document.createElement("option");
                    option.value = productName;
                    option.text = productName;
                    productNameSelect.appendChild(option);
                });

                // Update product base dropdown
                var productBaseSelect = document.getElementById("product_base");
                productBaseSelect.innerHTML = "<option value='' selected disabled>Select Product Base</option>";
                responseData.productBases.forEach(function(productBase) {
                    var option = document.createElement("option");
                    option.value = productBase;
                    option.text = productBase;
                    productBaseSelect.appendChild(option);
                });

                // Update product color dropdown
                var productColorSelect = document.getElementById("product_color");
                productColorSelect.innerHTML = "<option value='' selected disabled>Select Product Color</option>";
                responseData.productColors.forEach(function(productColor) {
                    var option = document.createElement("option");
                    option.value = productColor;
                    option.text = productColor;
                    productColorSelect.appendChild(option);
                });
            }
        };
        xhttp.open("GET", "fetch_product_data.php?stitcher=" + selectedStitcher + "&challan=" + selectedChallan, true);
        xhttp.send();
    }
</script>
        ]

</body>
</html>