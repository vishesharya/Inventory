<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');

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
        $query = "UPDATE sheets_production_product SET $field = '$value' WHERE id = $id";
        $result = mysqli_query($con, $query);

        if (!$result) {
            echo "Error updating record: " . mysqli_error($con);
        }
    }
}
 
$total_big_panel = 0;
$total_small_panel = 0;
$total_plain_panel = 0;

// Fetch data alphabetically
$result = mysqli_query($con, "SELECT id, product_name, product_base, product_color, remaining_big_panel, remaining_small_panel, remaining_plain_panel FROM sheets_production_product ORDER BY product_name ASC");

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


    

</head>

<body>

    <!-- Main navbar -->
    <?php include('include/top.php'); ?>
    <!-- /main navbar -->

    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Page header -->
                <div class="page-header page-header-default">
                    <div class="page-header-content">
                        <div class="page-title">
                            <h4><i class="icon-arrow-left52 position-left"></i> <a href="inventory.php" class="text-semibold">Sheets Product Inventory For Production</a></h4>
                        </div>

                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="inventory.phpp" class="btn bg-indigo-300"  >Sheets Inventory Data</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->

                <!-- Table of football contact query -->
                <div class="panel panel-flat" style="overflow: auto;">
                    <div class="panel-heading">
                        <h5 class="panel-title">Sheets Product List</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a  href="sheets_production_product_print.php" >
				     	<button  class="btn btn-success ">Print</button>  
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
                                <th>Big Panel Stock</th>
                                <th>Plain Panel Stock</th>
                                <th>Small Panel Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sn=1;
                            while($data=mysqli_fetch_array($result)) {
                                $total_big_panel += $data['remaining_big_panel'];
                                $total_small_panel += $data['remaining_small_panel'];
                                $total_plain_panel += $data['remaining_plain_panel'];
                            ?>
                            <tr>
                                <td><?php echo $sn; ?>.</td>
                                <td><?php echo $data['product_name']; ?></td>
                                <td><?php echo ucfirst($data['product_base']); ?></td>
                                <td><?php echo ucfirst($data['product_color']); ?></td>
                                <td contenteditable="true" data-field="remaining_big_panel" data-id="<?php echo $data['id']; ?>"><?php echo $data['remaining_big_panel']; ?></td>
                                <td contenteditable="true" data-field="remaining_plain_panel" data-id="<?php echo $data['id']; ?>"><?php echo $data['remaining_plain_panel']; ?></td>
                                <td contenteditable="true" data-field="remaining_small_panel" data-id="<?php echo $data['id']; ?>"><?php echo $data['remaining_small_panel']; ?></td>
                            </tr>
                            <?php 
                            $sn++; 
                            }  
                            ?>
                        </tbody>
                        <tfoot>
                           <tr>
                                 <td colspan="4"></td>
                                 <td><strong>Total Big Panel: <?php echo $total_big_panel; ?></strong></td>
                                  <td><strong>Total Plain Panel: <?php echo $total_plain_panel; ?></strong></td>
                                  
                                  <td><strong>Total Small Panel: <?php echo $total_small_panel; ?></strong></td>
                           </tr>
                      </tfoot>
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
        xhr.open('POST', 'sheets_production_product_stock_update.php', true);
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
