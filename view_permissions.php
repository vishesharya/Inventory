<?php
include_once 'include/connection.php';
include './include/check_login.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    // Map PHP files to user-friendly labels
    $php_files = [
        'add_bladder.php' => 'Add Bladder',
        'add_ink.php' => 'Add Ink',
        'add_inventory_products.php' => 'Add Inventory Products',
        'add_labour.php' => 'Add Labour',
        'add_products.php' => 'Add Products',
        'add_stitcher.php' => 'Add Stitcher',
        'add_thread.php' => 'Add Thread',
        'all_customer_query_print.php' => 'All Customer Query Print',
        'all_product_update.php' => 'All Product Update',
        'all_small_stock-update.php' => 'All Small Stock Update',
        'analytics.php' => 'Analytics',
        'barcode.php' => 'Barcode',
        'bladder_inventory.php' => 'Bladder Inventory',
        'chart.php' => 'Chart',
        'check_ratio.php' => 'Check Ratio',
        'code_print-faizal.php' => 'Code Print Faizal',
        'code_print.php' => 'Code Print',
        'contact.php' => 'Contact',
        'contact_query.php' => 'Contact Query',
        'customer_query_dtls.php' => 'Customer Query Details',
        'cust_query_more_dtls.php' => 'Customer Query More Details',
        'dashboard.php' => 'Dashboard',
        'default.php' => 'Default',
        'delete.php' => 'Delete',
        'delete_all_data.php' => 'Delete All Data',
        'delete_bladder.php' => 'Delete Bladder',
        'delete_code.php' => 'Delete Code',
        'delete_ink.php' => 'Delete Ink',
        'delete_inventory_products.php' => 'Delete Inventory Products',
        'delete_products.php' => 'Delete Products',
        'delete_product_row.php' => 'Delete Product Row',
        'selling_data_print.php' => 'Selling Data Print',
        'sheets_teceive_temp.php' => 'Sheets Teceive Temp',
        'sheet_color_panel_stock_update.php' => 'Sheet Color Panel Stock Update',
        'stitcher_macking_price.php' => 'Stitcher Macking Price',
        'stitcher_macking_price_data_fatch.php' => 'Stitcher Macking Price Data Fetch',
        'tb_barcode.php' => 'TB Barcode',
        'tcode_delete.php' => 'TCode Delete',
        'tcode_print.php' => 'TCode Print',
        'temp.php' => 'Temp',
        'tennisball_top.php' => 'Tennisball Top',
        'thanks.php' => 'Thanks',
        'thread_inventory.php' => 'Thread Inventory',
        'thread_inventory_and_price_update.php' => 'Thread Inventory and Price Update',
        'top_user.php' => 'Top User',
        'tquery_print.php' => 'TQuery Print',
        't_query.php' => 'TQuery',
        'update.php' => 'Update',
        'update_bladder_balance.php' => 'Update Bladder Balance',
        'update_for_football.php' => 'Update for Football',
        'update_for_kits.php' => 'Update for Kits',
        'update_small_stock_product.php' => 'Update Small Stock Product',
        'update_thread_balance.php' => 'Update Thread Balance',
        'users.php' => 'User Management',
        'validation.php' => 'Validation',
        'wethout_print_issue_color_fetch.php' => 'Without Print Issue Color Fetch',
        'wethout_print_received_color_fetch.php' => 'Without Print Received Color Fetch',
        'without_print_issue_all_data.php' => 'Without Print Issue All Data',
        'without_print_issue_slip.php' => 'Without Print Issue Slip',
        'without_print_received_all_data.php' => 'Without Print Received All Data',
        'without_print_received_slip.php' => 'Without Print Received Slip'
    ];

    // Get user's current permissions
    $stmt = $con->prepare("SELECT file, has_access FROM permissions WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $permissions = [];
    while ($row = $result->fetch_assoc()) {
        $permissions[$row['file']] = $row['has_access'];
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>View Permissions</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
            }
            .container {
                width: 50%;
                margin: 50px auto;
                background: #fff;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }
            h1 {
                text-align: center;
                color: #333;
            }
            .file-list {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            .file-item {
                flex: 1 1 48%;
                display: flex;
                align-items: center;
                background: #f9f9f9;
                padding: 10px;
                border-radius: 4px;
                border: 1px solid #ddd;
            }
            .file-item label {
                margin-left: 10px;
                font-weight: bold;
            }
            .file-item input {
                margin-right: 10px;
            }
            .btn {
                display: block;
                width: 100%;
                padding: 10px;
                background: #007bff;
                color: #fff;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-size: 16px;
            }
            .btn:hover {
                background: #0056b3;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>View Permissions for User ID: <?php echo $user_id; ?></h1>
            <form method="post" action="update_permissions.php">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <div class="file-list">
                    <?php foreach ($php_files as $file => $label): ?>
                        <div class="file-item">
                            <input type="checkbox" name="permissions[<?php echo $file; ?>]" value="1" <?php echo isset($permissions[$file]) && $permissions[$file] ? 'checked' : ''; ?>>
                            <label for="permissions[<?php echo $file; ?>]"><?php echo $label; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="submit" class="btn">Update Permissions</button>
            </form>
        </div>
    </body>
    </html>
    <?php
}
?>
