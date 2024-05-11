if (!empty($_POST['small_sheet_color'])) {
            // Shift quantity from sheets_production_small_stock to sheets_small_stock
            $remaining_production_small_panel_query = "SELECT small_sheet_balance FROM sheets_production_small_stock WHERE product_name = '$product_name'";
            $remaining_production_small_panel_result = mysqli_query($con, $remaining_production_small_panel_query);
            $row = mysqli_fetch_assoc($remaining_production_small_panel_result);
            $remaining_production_small_panel = $row['small_sheet_balance'];
        
            // Update remaining_small_panel in sheets_small_stock table
            $updated_remaining_small_panel = $remaining_production_small_panel - (int)$deleted_quantity3 ;
            $update_remaining_small_panel_query = "UPDATE sheets_small_stock SET small_sheet_balance = small_sheet_balance + $deleted_quantity3  WHERE product_name = '$product_name'";
            mysqli_query($con, $update_remaining_small_panel_query);
        
            // Update remaining_small_panel in sheets_production_small_stock table
            $update_remaining_production_small_panel_query = "UPDATE sheets_production_small_stock SET small_sheet_balance = $updated_remaining_small_panel WHERE product_name = '$product_name'";
            mysqli_query($con, $update_remaining_production_small_panel_query);
        } else {
            // Shift quantity from sheets_production_product to sheets_product
            $remaining_production_small_panel_query = "SELECT remaining_small_panel FROM sheets_production_product WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
            $remaining_production_small_panel_result = mysqli_query($con, $remaining_production_small_panel_query);
            $row = mysqli_fetch_assoc($remaining_production_small_panel_result);
            $remaining_production_small_panel = $row['remaining_small_panel'];
        
            // Update remaining_small_panel in sheets_product table
            $updated_remaining_small_panel = $remaining_production_small_panel - (int)$deleted_quantity3 ;
            $update_remaining_small_panel_query = "UPDATE sheets_product SET remaining_small_panel = remaining_small_panel + $deleted_quantity3  WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
            mysqli_query($con, $update_remaining_small_panel_query);
        
            // Update remaining_small_panel in sheets_production_product table
            $update_remaining_production_small_panel_query = "UPDATE sheets_production_product SET remaining_small_panel = $updated_remaining_small_panel WHERE product_name = '$product_name' AND product_base = '$product_base' AND product_color = '$product_color'";
            mysqli_query($con, $update_remaining_production_small_panel_query);
        }