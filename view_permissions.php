<?php
include_once 'include/connection.php';
include './include/check_login.php';



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];

    // Get all PHP files
    $php_files = [
        'add_bladder.php',
        'add_ink.php',
        'add_inventory_products.php',
        'add_labour.php',
        'add_products.php',
        'add_stitcher.php',
        'add_thread.php',
        'all_customer_query_print.php',
        'all_product_update.php',
        'all_small_stock-update.php',
        'analytics.php',
        'barcode.php',
        'bladder_inventory.php',
        'chart.php',
        'check_ratio.php',
        'code_print-faizal.php',
        'code_print.php',
        'contact.php',
        'contact_query.php',
        'customer_query_dtls.php',
        'cust_query_more_dtls.php',
        'dashboard.php',
        'default.php',
        'delete.php',
        'delete_all_data.php',
        'delete_bladder.php',
        'delete_code.php',
        'delete_ink.php',
        'delete_inventory_products.php',
        'delete_products.php',
        'delete_product_row.php',
        'selling_data_print.php',
        'sheets_teceive_temp.php',
        'sheet_color_panel_stock_update.php',
        'stitcher_macking_price.php',
        'stitcher_macking_price_data_fatch.php',
        'tb_barcode.php',
        'tcode_delete.php',
        'tcode_print.php',
        'temp.php',
        'tennisball_top.php',
        'thanks.php',
        'thread_inventory.php',
        'thread_inventory_and_price_update.php',
        'top_user.php',
        'tquery_print.php',
        't_query.php',
        'update.php',
        'update_bladder_balance.php',
        'update_for_football.php',
        'update_for_kits.php',
        'update_small_stock_product.php',
        'update_thread_balance.php',
        'users.php',
        'validation.php',
        'wethout_print_issue_color_fetch.php',
        'wethout_print_received_color_fetch.php',
        'without_print_issue_all_data.php',
        'without_print_issue_slip.php',
        'without_print_received_all_data.php',
        'without_print_received_slip.php'
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
    </head>
    <body>
        <h1>View Permissions for User ID: <?php echo $user_id; ?></h1>
        <form method="post" action="update_permissions.php">
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <?php foreach ($php_files as $file): ?>
                <div>
                    <input type="checkbox" name="permissions[<?php echo $file; ?>]" value="1" <?php echo isset($permissions[$file]) && $permissions[$file] ? 'checked' : ''; ?>>
                    <label for="permissions[<?php echo $file; ?>]"><?php echo $file; ?></label>
                </div>
            <?php endforeach; ?>
            <button type="submit">Update Permissions</button>
        </form>
    </body>
    </html>
    <?php
}
?>
