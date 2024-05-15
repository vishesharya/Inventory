$stitcher_address_query = "SELECT stitcher_address FROM stitcher WHERE stitcher_name = '$stitcher_name' LIMIT 1";
            $stitcher_address_result = mysqli_query($con, $stitcher_address_query);
            $stitcher_address_row = mysqli_fetch_assoc($stitcher_address_result);
            $stitcher_address = $stitcher_address_row['stitcher_address'];