<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    // Map PHP files to user-friendly labels
    $php_files = [
        'barcode.php' => 'Barcode Generate',
        'delete_code.php' => 'Barcode Delete',
        'fb_barcode.php' => 'Football Barcode Generate',
        'fcode_delete.php' => 'Football Barcode Delete',
        'fcode_print.php' => 'Football Barcode Print',
        'tb_barcode.php' => 'Tennis Ball Barcode Generate',
        'tcode_delete.php' => 'Tennis Ball Barcode Delete',
        'tcode_print.php' => 'Tennis Ball Barcode Print',
        'contact_query.php' => 'Contact Query',
        'f_query.php' => 'Football Contact Query',
        'fquery_print.php' => 'Football Contact Query Print',
        't_query.php' => 'Tennis Ball Contact Query',
        'tquery_print.php' => 'Tennis Ball Contact Query Print',
        'customer_query_dtls.php' => 'All Customer Query',
        'all_customer_query_print.php' => 'All Customer Query Print',
        'cust_query_more_dtls.php' => 'View Customer Query More Details',
        'top_user.php' => 'Top 10 Contact Query',
        'football_top.php' => 'Football Top 10 Contact Query',
        'print_football_top.php' => 'Football Top 10 Contact Query Print',
        'tennisball_top.php' => 'Tennis Ball Top 10 Contact Query',
        'print_tennisball_top.php' => 'Tennis Ball Top 10 Contact Query Print',
        'delete_all_data.php' => 'Delete All Barcode & Contact Query',

        'check_ratio.php' => 'Check Ratio',
        'analytics.php' => 'Analytics',

        'inventory.php' => 'Inventory',

        'sheets_inventory.php' => 'Sheets Inventory',
        'sheets_received.php' => 'Sheets Recived Form',
        'sheets_issue.php' => 'Sheets Issue Form',
        'sheets_buyer.php' => 'Sheets Selling Form',
        'sheets_received_data.php' => 'Sheets Received Data',
        'sheets_issue_data.php' => 'Sheets Issue Data ',
        'seets_job_work.php' => 'Sheets Job Work ',
        'sheets_selling_data.php' => 'Sheets Selling Data',
        'sheets_issue_all_slip.php' => 'Sheets Issue Slip',
        'sheets_product_list.php' => 'Sheets Inventory Status ( Packaging ) ',
        'sheets_product_list_for_production.php' => 'Sheets Inventory Status ( Production ) ',
        'sheets_color_panel_inventory.php' => 'Color Pannel ( Packaging ) ',
        'sheets_color_panel_production_inventory.php' => 'Color Pannel ( Production ) ',
        'all_small_stock-update.php' => 'Color Pannel Update',
        'sheets_panel_color.php' => 'Color Pannel Add/Delete',
       

     

        'kits_inventory.php' => 'Kits Inventory',
        'kits_receive_form.php' => 'Kits Recived Form',
        'kits_issue_form.php' => 'Kits Issue Form',
        'kits_return_form.php' => 'Kits Returns Form',
        'kits_receive_data.php' => 'Kits Received Data',
        'kits_issue_data.php' => 'Kits Issue Data ',
        'kits_return_data.php' => 'Kits Return Data ',
        'kits_receive_slip_all_data.php' => 'Kits Received Slip',
        'kits_issue_all_data_slip.php' => 'Kits Issue Slip',
        'kits_job_work.php' => 'Kits Job Work',
        'kits_product_list.php' => 'Kits Inventory Status',

        'kits_inventory_without_print.php' => 'Kits Inventory ( Without Print ) ',
        'kits_received_for_printing.php' => 'Kits Recived Form ( Without Print )',
        'kits_issue_for_printing.php' => 'Kits Issue Form ( Without Print )',
        'without_print_received_all_data.php' => 'Kits Received Data ( Without Print )',
        'without_print_issue_all_data.php' => 'Kits Issue Data ( Without Print )',
        'without_print_received_slip.php' => 'Kits Received Slip ( Without Print )',
        'without_print_issue_slip.php' => 'Kits Issue Slip ( Without Print )',
        'printing_job_work.php' => 'Kits Job Work ( Without Print )',
       
     
       
        'football_inventory.php' => 'Football Inventory',
        'football_received.php' => 'Football Recived Form',
        'football_issue.php' => 'Football Issue Form',
        'football_receiving_data.php' => 'Football Received Data',
        'football_issue_data.php' => 'Football Issue Data ',
        'football_receive_all_slip.php' => 'Football Received Slip',
        'football_product_list.php' => 'Football Inventory Status',

        'stitcher_macking_price.php' => 'Stitcher Invoice',
        'add_stitcher.php' => 'Add/Edit/Delete Stitcher',

        'add_inventory_products.php' => 'Add Inventory Products',
        'delete_inventory_products.php' => 'Delete Inventory Products',

        'add_bladder.php' => 'Add Bladder',
        'delete_bladder.php' => 'Delete Bladder',
        'bladder_inventory.php' => 'Bladder Inventory',
        'update_bladder_balance.php' => 'Update Bladder Balance',

        'add_ink.php' => 'Add Ink',
        'delete_ink.php' => 'Delete Ink',
        'ink_inventory.php' => 'Ink Inventory',
        
        'add_products.php' => 'Add Products',
        'delete_products.php' => 'Delete Products',
        'all_product_update.php' => 'Products Update',
        
        'add_thread.php' => 'Add Thread',
        'thread_inventory.php' => 'Thread Inventory',
        'update_thread_balance.php' => 'Update Thread Balance',
        'thread_inventory_and_price_update.php' => 'Thread Inventory and Price Update',
      
        'add_labour.php' => 'Add/Edit/Delete Labour',
        'admin_dashboard.php' => 'Admin Dashboard',
        'view_permissions.php' => 'View User Permissions',
        'update_permissions.php' => 'Update Permissions',
        'users.php' => 'Add / Delete Management',
        'guards.php' => 'Guards Management',
        'supervisor.php' => 'Supervisor Management',
        'first_time_question.php' => 'Security Q&A',
        
        
       
        
        
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
            width: 90%;
            max-width: 1200px;
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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
        }
        .file-item {
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
            width: 200px;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 20px;
            margin: 20px auto 0;
            text-align: center;
        }
        .btn:hover {
            background: #0056b3;
        }
        .select-all-container {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.file-item input[type="checkbox"]');
            const selectAllCheckbox = document.getElementById('select-all-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>View Permissions for User ID: <?php echo $user_id; ?></h1>
        <div class="select-all-container">
            <label>
                <input type="checkbox" id="select-all-checkbox" onclick="toggleSelectAll()"> Select All
            </label>
        </div>
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
