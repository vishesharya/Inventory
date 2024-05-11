$remaining_production_small_panel_query = "SELECT small_sheet_balance FROM sheets_small_stock WHERE product_name = '$product_name'";
            $remaining_production_small_panel_result = mysqli_query($con, $remaining_production_small_panel_query);
            $row = mysqli_fetch_assoc($remaining_production_small_panel_result);
            $remaining_small_panel = $row['small_sheet_balance'];
            
           // Update remaining_small_panel in sheets_product table
           $updated_remaining_small_panel = $remaining_small_panel + (int)$quantity3;
           $update_remaining_production_small_panel_query = "UPDATE sheets_small_stock SET small_sheet_balance = $updated_remaining_small_panel WHERE product_name = '$product_name'";
           mysqli_query($con, $update_remaining_production_small_panel_query);