<?php
session_start();
include './include/connection.php';
include_once 'include/admin-main.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the necessary POST data is set
    if (isset($_POST['id']) && isset($_POST['field']) && isset($_POST['value'])) {
        $id = $_POST['id'];
        $field = $_POST['field']; 
        $value = $_POST['value'];

        // Sanitize input to prevent SQL injection (Prepared Statement would be better)
        $id = mysqli_real_escape_string($con, $id);
        $field = mysqli_real_escape_string($con, $field);
        $value = mysqli_real_escape_string($con, $value);

        // Update database for kits_product table
        $query_kits_product = "UPDATE sheets_production_small_stock SET $field = '$value' WHERE id = $id";
        $result_kits_product = mysqli_query($con, $query_kits_product);

        // Update database for products table
        $query_products = "UPDATE sheets_small_stock SET $field = '$value' WHERE id = $id";
        $result_products = mysqli_query($con, $query_products);
        
        if (!$result_kits_product || !$result_products) {
            echo "Error updating record: " . mysqli_error($con);
        }
    }
}

// Fetch common fields from all tables
$result = mysqli_query($con, "SELECT kp.id, kp.small_sheet_color,
                                    p.small_sheet_color AS small_sheet_color_product
                             FROM sheets_production_small_stock kp 
                             JOIN sheets_small_stock p ON kp.id = p.id 
                             ORDER BY small_sheet_color ASC
                             ");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php');?> Football Contact Query</title>
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.png">

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="assets/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="assets/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/loaders/blockui.min.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="assets/js/plugins/tables/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="assets/js/plugins/forms/selects/select2.min.js"></script>
    <script type="text/javascript" src="assets/js/core/app.js"></script>
    <script type="text/javascript" src="assets/js/pages/datatables_sorting.js"></script>
    <!-- /theme JS files -->

</head>

<body>

    <!-- Main navbar -->
    <?php include('include/top.php'); ?>
    <!-- /main navbar -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main sidebar -->
            
            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                <div class="page-header page-header-default">
                    <div class="page-header-content">
                        <div class="page-title">
                            <h4><i class="icon-arrow-left52 position-left"></i> <a href="inventory.php" class="text-semibold">Update All Small Panel Color</a></h4>
                        </div>

                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="inventory.php" class="btn bg-indigo-300"  >Update All Small Panel Color</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->

                <!-- Table of football contact query -->
                <div class="panel panel-flat" style="overflow: auto;">
                    <div class="panel-heading">
                        <h5 class="panel-title">Update All Small Panel Color</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a data-action="collapse"></a></li>
                                <li><a data-action="reload"></a></li>
                                <li><a data-action="close"></a></li>
                            </ul>
                        </div>
                    </div>
                    <table class="table datatable-multi-sorting">
                        <thead>
                            <tr>
                                <th>Sn.</th>
                                <th>Product Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sn=1;
                            while($data=mysqli_fetch_array($result)) {
                            ?>
                            <tr>
                                 <td><?php echo $sn; ?>.</td>
                                 <td contenteditable="true" data-field="small_sheet_color" data-id="<?php echo $data['id']; ?>"><?php echo $data['small_sheet_color']; ?></td>
                                <td><button class="btn btn-danger delete-row" data-id="<?php echo $data['id']; ?>">Delete</button></td>
                            </tr>
                            <?php 
                            $sn++; 
                            }  
                            ?>
                        </tbody>
                    </table>
                </div>
                <!-- /Table of football contact query -->

            </div>
            <!-- /main content -->

        </div>
        <!-- /page content -->

    </div>
    <!-- /page container -->

    <!-- Delete/Edit validation -->
    <script>
        // Function to handle update operation via AJAX
        function updateDatabase(id, field, value) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_small_stock_product.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);
                }
            };
            xhr.send('id=' + id + '&field=' + field + '&value=' + value);
        }

        // Function to handle delete operation via AJAX
        function deleteRow(id) {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete_product_row.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);
                    // Reload the page or update the table if necessary
                }
            };
            xhr.send('id=' + id);
        }
        // Event listener for update operation
        document.querySelectorAll('[contenteditable="true"]').forEach(function(element) {
            element.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    this.blur();
                    var id = this.getAttribute('data-id');
                    var field = this.getAttribute('data-field');
                    var value = this.innerText;
                    updateDatabase(id, field, value);
                }
            });
        });

        // Event listener for delete operation
        document.querySelectorAll('.delete-row').forEach(function(element) {
            element.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                if(confirm('Are you sure you want to delete this Product?')) {
                    deleteRow(id);
                }
            });
        });
    </script>
    <!-- /Delete/Edit validation -->

</body>
</html>
