<?php
session_start();
error_reporting(0);
include('include/connection.php');
include_once 'include/admin-main.php';

// Default value for Labour Name
$buyer_name = isset($_POST['buyer_name']) ? $_POST['buyer_name'] : "";
// Default value for invoice number
$invoice_number = isset($_POST['invoice_number']) ? $_POST['invoice_number'] : "";

// Default value for invoice number
$destination = isset($_POST['destination']) ? $_POST['destination'] : "";

// Generate Challan No if not set
if (!isset($_SESSION['challan_no'])) {
    $_SESSION['challan_no'] = generateChallanNo('KSI');
}

// Get Challan No from session
$challan_no = $_SESSION['challan_no'];

// Logic to fetch product names from the database
$product_query = "SELECT DISTINCT product_name FROM products";
$product_result = mysqli_query($con, $product_query);

// Logic to fetch product bases and colors based on selected product
$selected_product = isset($_POST['product_name']) ? $_POST['product_name'] : null;
if ($selected_product) {
    $product_base_query = "SELECT DISTINCT product_base FROM products WHERE product_name = '$selected_product'";
    $product_color_query = "SELECT DISTINCT product_color FROM products WHERE product_name = '$selected_product'";
    $product_base_result = mysqli_query($con, $product_base_query);
    $product_color_result = mysqli_query($con, $product_color_query);
}

// Function to generate Challan No
// Function to generate Challan No specific to football_issue table
function generateChallanNo($prefix) {
    global $con;
    // Check if the counter for football_issue exists in the session
    if (!isset($_SESSION['challan_counter'])) {
        // If not, initialize it to 1
        $_SESSION['challan_counter'] = 1;
    } else {
        // Otherwise, increment the counter by 1
        $_SESSION['challan_counter']++;
    }

    // Generate the Challan Number using the counter
    return $prefix . '-FI-' . $_SESSION['challan_counter'];
}


$errors = array();

if (isset($_POST['add_product'])) {
    // Validate input
    if (empty($_POST['product_name']) || empty($_POST['product_base']) || empty($_POST['product_color']) || empty($_POST['quantity'])) {
        $errors[] = "Please fill in all fields.";
    } else {
        // Sanitize input
        $invoice_number = isset($_POST['invoice_number']) ? mysqli_real_escape_string($con, $_POST['invoice_number']) : "";
        $buyer_name = isset($_POST['buyer_name']) ? mysqli_real_escape_string($con, $_POST['buyer_name']) : "";
        $destination = isset($_POST['destination']) ? mysqli_real_escape_string($con, $_POST['destination']) : "";
        $product_name = mysqli_real_escape_string($con, $_POST['product_name']);
        $product_base = mysqli_real_escape_string($con, $_POST['product_base']);
        $product_color = mysqli_real_escape_string($con, $_POST['product_color']);
        $quantity = mysqli_real_escape_string($con, $_POST['quantity']);

        // Check stock availability
        $stock_query = "SELECT remaining_quantity FROM products WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
        $stock_result = mysqli_query($con, $stock_query);
        $row = mysqli_fetch_assoc($stock_result);
        $remaining_quantity = $row['remaining_quantity'];

        if ($quantity > $remaining_quantity) {
            $errors[] = "Requested quantity exceeds available stock for $product_name, $product_base, $product_color. Available quantity: $remaining_quantity";
        } else {
            // Reduce the remaining_quantity in the database
            $updated_remaining_quantity = $remaining_quantity - $quantity;
            $update_stock_query = "UPDATE products SET remaining_quantity = $updated_remaining_quantity WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
            $update_stock_result = mysqli_query($con, $update_stock_query);

            if ($update_stock_result) {
                // Stock updated successfully, proceed to add product to session
                // Check if the product already exists in the session
                $product_exists = false;
                foreach ($_SESSION['temp_products'] as $key => $product) {
                    if ($product['product_name'] === $product_name && $product['product_base'] === $product_base && $product['product_color'] === $product_color) {
                        // Product already exists, update its quantity
                        $_SESSION['temp_products'][$key]['issue_quantity'] += $quantity;
                        $product_exists = true;
                        break;
                    }
                }

                if (!$product_exists) {
                    // Product does not exist, add it to the session
                    $temp_product = array(
                        'challan_no' => $challan_no,
                        'invoice_number' => $invoice_number,
                        'buyer_name' => $buyer_name,
                        'destination' => $destination,
                        'product_name' => $product_name,
                        'product_base' => $product_base,
                        'product_color' => $product_color,
                        'issue_quantity' => $quantity
                    );
                    $_SESSION['temp_products'][] = $temp_product;
                }
            } else {
                // Error updating stock
                $errors[] = "Failed to update stock quantity for $product_name, $product_base, $product_color.";
            }
        }
    }
}


// Check if delete button is clicked
if (isset($_POST['delete_product'])) {
    // Get the index of the product to be deleted
    $delete_index = $_POST['delete_index'];

    // Get the product details to update remaining_quantity in products table
    $deleted_product = $_SESSION['temp_products'][$delete_index];
    $product_name = mysqli_real_escape_string($con, $deleted_product['product_name']);
    $product_base = mysqli_real_escape_string($con, $deleted_product['product_base']);
    $product_color = mysqli_real_escape_string($con, $deleted_product['product_color']);
    $deleted_quantity = mysqli_real_escape_string($con, $deleted_product['issue_quantity']);

    // Fetch remaining_quantity from products table
    $remaining_quantity_query = "SELECT remaining_quantity FROM products WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
    $remaining_quantity_result = mysqli_query($con, $remaining_quantity_query);
    $row = mysqli_fetch_assoc($remaining_quantity_result);
    $remaining_quantity = $row['remaining_quantity'];

    // Calculate updated remaining_quantity
    $updated_remaining_quantity = $remaining_quantity + $deleted_quantity;

    // Update remaining_quantity in products table
    $update_remaining_quantity_query = "UPDATE products SET remaining_quantity = $updated_remaining_quantity WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
    mysqli_query($con, $update_remaining_quantity_query);

    // Remove the product from the session
    unset($_SESSION['temp_products'][$delete_index]);

    // Reset array keys to maintain consecutive numbering
    $_SESSION['temp_products'] = array_values($_SESSION['temp_products']);

    // Redirect to prevent form resubmission
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit();
}


// Store added products in the database when "Submit" button is clicked
if (isset($_POST['submit_products'])) {
    $temp_products = isset($_SESSION['temp_products']) ? $_SESSION['temp_products'] : [];

    if (empty($temp_products)) {
        $errors[] = "Please add at least one product.";
    } else {
        foreach ($temp_products as $product) {
            $challan_no = mysqli_real_escape_string($con, $product['challan_no']);
            $invoice_number = mysqli_real_escape_string($con, $product['invoice_number']);
            $buyer_name = mysqli_real_escape_string($con, $product['buyer_name']);
            $destination = mysqli_real_escape_string($con, $product['destination']);
            $product_name = mysqli_real_escape_string($con, $product['product_name']);
            $product_base = mysqli_real_escape_string($con, $product['product_base']);
            $product_color = mysqli_real_escape_string($con, $product['product_color']);
            $quantity = mysqli_real_escape_string($con, $product['issue_quantity']);
            $total = mysqli_real_escape_string($con, $product['total']);

            // Insert product into the database
            $insert_query = "INSERT INTO football_issue (challan_no, invoice_number, buyer_name, destination, product_name, product_base, product_color, issue_quantity) 
                            VALUES ('$challan_no', '$invoice_number', '$buyer_name', '$destination' , '$product_name', '$product_base', '$product_color', '$quantity')";
            $insert_result = mysqli_query($con, $insert_query);

            if (!$insert_result) {
                $errors[] = "Failed to store data in the database.";
            }
        }

        // If no errors, update the Challan Number and clear session storage
        if (empty($errors)) {
            // Update Challan Number
            $_SESSION['challan_no'] = generateChallanNo('KSI');
            
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
                        <h1 class="h4 text-center mb-4">Football Issue</h1>
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
                                        <label for="buyer_name">Invoice Number:</label>
                                        <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="<?php echo $invoice_number; ?>" placeholder="Enter Invoice Number" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                            <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="buyer_name">Buyer Name:</label>
                                        <input type="text" class="form-control" id="buyer_name" name="buyer_name" value="<?php echo $buyer_name; ?>" placeholder="Enter Labour Name" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="buyer_name">Destination:</label>
                                        <input type="text" class="form-control" id="destination" name="destination" value="<?php echo $destination; ?>" placeholder="Enter Address">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
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
                            </div>
                            <div class="row">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="quantity">Issue Quantity:</label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity">
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
                                            <th>Invoice Number</th>
                                            <th>Buyer Name</th>
                                            <th>Destination</th>
                                            <th>Product Name</th>
                                            <th>Product Base</th>
                                            <th>Product Color</th>
                                            <th>Issue Quantity</th>
                                            
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($_SESSION['temp_products'])) : ?>
                                            <?php foreach ($_SESSION['temp_products'] as $key => $product) : ?>
                                                <tr>
                                                    <td><?php echo $product['challan_no']; ?></td>
                                                    <td><?php echo $product['invoice_number']; ?></td>
                                                    <td><?php echo $product['buyer_name']; ?></td>
                                                    <td><?php echo $product['destination']; ?></td>
                                                    <td><?php echo $product['product_name']; ?></td>
                                                    <td><?php echo $product['product_base']; ?></td>
                                                    <td><?php echo $product['product_color']; ?></td>
                                                    <td><?php echo $product['issue_quantity']; ?></td>
                                                   
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
</html>
