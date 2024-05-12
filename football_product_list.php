<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id']) && isset($_POST['field']) && isset($_POST['value'])) {
        $id = $_POST['id'];
        $field = $_POST['field'];
        $value = $_POST['value'];

        // Sanitize input to prevent SQL injection
        $id = mysqli_real_escape_string($con, $id);
        $field = mysqli_real_escape_string($con, $field);
        $value = mysqli_real_escape_string($con, $value);

        // Update database
        $query = "UPDATE products SET $field = '$value' WHERE id = $id";
        $result = mysqli_query($con, $query);

        if (!$result) {
            echo "Error updating record: " . mysqli_error($con);
        }
    }
}

// Fetch data alphabetically
$result = mysqli_query($con, "SELECT id, product_name, product_base, product_color, remaining_quantity FROM products ORDER BY product_name ASC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php include('include/title.php');?></title>
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

    <style>
             /* Hide the column with per_pice_price data */
        td[data-field="per_pice_price"] {
         display: none;
         }

        /* Hide the column with 2nd_price data */
       td[data-field="2nd_price"] {
        display: none;
        }

    </style>

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
                            <h4><i class="icon-arrow-left52 position-left"></i> <a href="inventory.php" class="text-semibold">Click Here - Go Back</a></h4>
                        </div>

                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="inventory.phpp" class="btn bg-indigo-300"  >Football Inventory Data</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->

               
             
                <!-- /Print form -->

                <!-- Table of football contact query -->
               
                    <div class="panel panel-flat" style="overflow: auto;">
                        <div class="panel-heading">
                            <h5 class="panel-title">Football Product List</h5>
                            <div class="heading-elements">
                                <ul class="icons-list">
                                    <li><a  href="print_football_product_list.php" >
					     	<button  class="btn btn-success ">Print </button>  
				        	</a>    </li>
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
                                    <th>Product Base</th>
                                    <th>Product Color</th>
                                    <th>Product Closing Balance</th>
                                    <th>X</th>
                                    <th>X</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $totalRemainingQuantity = 0; // Initialize total remaining quantity
                            $sn = 1;
                            while ($data = mysqli_fetch_array($result)) {
                                $totalRemainingQuantity += $data['remaining_quantity']; // Add to total
                                ?>
                                <tr>
                                    <td><?php echo $sn; ?>.</td>
                                    <td><?php echo $data['product_name']; ?></td>
                                    <td><?php echo ucfirst($data['product_base']); ?></td>
                                    <td><?php echo ucfirst($data['product_color']); ?></td>
                                    <td contenteditable="true" data-field="remaining_quantity" data-id="<?php echo $data['id']; ?>"><?php echo $data['remaining_quantity']; ?></td>
                                    <td contenteditable="true" data-field="per_pice_price" data-id="<?php echo $data['id']; ?>"><?php echo $data['per_pice_price']; ?></td>
                                    <td contenteditable="true" data-field="2nd_price" data-id="<?php echo $data['id']; ?>"><?php echo $data['2nd_price']; ?></td>
                                </tr>
                                <?php 
                                $sn++; 
                                }  
                                ?>
                            </tbody>
                            <tfoot>
            <tr>
                <th colspan="4">Total Remaining Quantity:</th>
                <th><?php echo $totalRemainingQuantity; ?></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
                        </table>
                    </div>
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

    function updateDatabase(id, field, value) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_for_football.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log(xhr.responseText);
            }
        };
        xhr.send('id=' + id + '&field=' + field + '&value=' + value);
    }
    </script>
</body>
</html>
