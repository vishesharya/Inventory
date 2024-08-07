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
        $query = "UPDATE sheets_product SET $field = '$value' WHERE id = $id";
        $result = mysqli_query($con, $query);

        if (!$result) {
            echo "Error updating record: " . mysqli_error($con);
        } 
    }
}

// Fetch data alphabetically
$result = mysqli_query($con, "SELECT id, small_sheet_color, small_sheet_balance FROM sheets_production_small_stock ORDER BY small_sheet_color ASC");

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
                            <h4><i class="icon-arrow-left52 position-left"></i> <a href="inventory.php" class="text-semibold">Small Color Panel Inventory (Production)</a></h4>
                        </div>

                    </div>

                    <div class="breadcrumb-line">
                        <ul class="breadcrumb">
                            <li><a href="dashboard.php"><i class="icon-home2 position-left"></i> Home</a></li>
                            <li class="active"><a href="inventory.phpp" class="btn bg-indigo-300"  >Small Color Panel Inventory</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /page header -->

                <!-- Table of football contact query -->
                <div class="panel panel-flat" style="overflow: auto;">
                    <div class="panel-heading">
                        <h5 class="panel-title">Kits Product List</h5>
                        <div class="heading-elements">
                            <ul class="icons-list">
                                <li><a  href="./sheets_production_color_panel_print.php" >
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
                              
                                <th>Sheets Color Panel</th>
                                <th>Sheets Color Panel Stock</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $sn=1;
                            $total_balance = 0; 
                            while($data=mysqli_fetch_array($result)) {
                                $total_balance += $data['small_sheet_balance'];
                            ?>
                            <tr>
                                <td><?php echo $sn; ?>.</td>
                              
                                <td><?php echo ucfirst($data['small_sheet_color']); ?></td>
                               
                                <td contenteditable="true" data-field="small_sheet_balance" data-id="<?php echo $data['id']; ?>"><?php echo $data['small_sheet_balance']; ?></td>
                               
                            </tr>
                            <?php 
                            $sn++; 
                            }  
                            ?>
                        </tbody>
                        <tfoot>
                          <tr>
                               <th colspan="2"></th>
                              <th> Total: <?php echo $total_balance; ?></th>
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
        xhr.open('POST', 'sheets_production_color_panel_stock_update.php', true);
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
