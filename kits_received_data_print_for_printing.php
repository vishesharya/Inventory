<?php
session_start();
include_once 'include/connection.php';
include_once 'include/admin-main.php';

$stitcher_query = "SELECT DISTINCT stitcher_name FROM print_received ORDER BY stitcher_name ASC"; 
$stitcher_result = mysqli_query($con, $stitcher_query);

$challan_no = isset($_POST['challan_no']) ? $_POST['challan_no'] : "";

$date_and_time_query = "SELECT date_and_time FROM print_received WHERE challan_no = '$challan_no' LIMIT 1";
$date_and_time_result = mysqli_query($con, $date_and_time_query);
$date_and_time_row = mysqli_fetch_assoc($date_and_time_result);
$date_and_time = isset($date_and_time_row['date_and_time']) ? $date_and_time_row['date_and_time'] : "";

if (isset($_SESSION['challan_no'])) {
    $challan_no = $_SESSION['challan_no'];
}

$result = null;

if (isset($_POST['view_entries'])) {
    $stitcher_name = isset($_POST['stitcher_name']) ? mysqli_real_escape_string($con, $_POST['stitcher_name']) : '';
    $conditions = "";

    if (!empty($stitcher_name)) {
        $conditions .= " WHERE stitcher_name = '$stitcher_name'";
    }

    if (!empty($_POST['from_date']) && !empty($_POST['to_date'])) {
        $start_date = mysqli_real_escape_string($con, $_POST['from_date']);
        $end_date = mysqli_real_escape_string($con, $_POST['to_date']);
        $conditions .= ($conditions == "") ? " WHERE" : " AND";
        $conditions .= " date_and_time BETWEEN '$start_date' AND '$end_date'";
    }

    if (!empty($_POST['challan_no'])) {
        $challan_no = mysqli_real_escape_string($con, $_POST['challan_no']);
        $conditions .= ($conditions == "") ? " WHERE" : " AND";
        $conditions .= " challan_no = '$challan_no'";
    }

    $query = "SELECT * FROM print_received $conditions";
    $result = mysqli_query($con, $query);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content -->
</head>
<body>
    <!-- Body content -->
    <?php if (isset($_POST['view_entries']) && mysqli_num_rows($result) > 0): ?>
        <div class="container">
            <h2>KITS RECEIVING SLIP</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Receive Product Name</th>
                        <th>Receive Product Base</th>
                        <th>Receive Product Color</th>
                        <th>Issue Product Name</th>
                        <th>Issue Product Base</th>
                        <th>Issue Product Color</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total_quantity = 0;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $total_quantity += $row['received_quantity'];
                    ?>
                        <tr>
                            <td><?php echo $row['product_name1']; ?></td>
                            <td><?php echo ucfirst($row['product_base1']); ?></td>
                            <td><?php echo ucfirst($row['product_color1']); ?></td>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo ucfirst($row['product_base']); ?></td>
                            <td><?php echo ucfirst($row['product_color']); ?></td>
                            <td><?php echo $row['received_quantity']; ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="6">Total</td>
                        <td><?php echo $total_quantity; ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

   <!-- JavaScript code for fetching challan numbers based on selected Stitcher and date range -->

     <script>
          document.getElementById("select_stitcher").addEventListener("change", function() {
            var selectedStitcher = this.value;
            fetchChallanNumbers(selectedStitcher);
        });

        function fetchChallanNumbers(selectedStitcher) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    var challanSelect = document.getElementById("select_challan");
                    var challanNumbers = JSON.parse(this.responseText);
                    challanSelect.innerHTML = "<option value='' selected disabled>Select Challan No</option>";
                    challanNumbers.forEach(function(challan) {
                        var option = document.createElement("option");
                        option.value = challan;
                        option.text = challan;
                        challanSelect.appendChild(option);
                    });
                }
            };
            xhttp.open("GET", "fetch_challan_no_for_kits_printing_received.php?stitcher=" + selectedStitcher, true);
            xhttp.send();
        }
  

function handleStitcherChange() {
    var selectedStitcher = document.getElementById("select_Stitcher").value;
    if (selectedStitcher) {
        fetchChallanNumbers(selectedStitcher);
    }
}

document.getElementById("select_stitcher").addEventListener("change", handleStitcherChange);
document.getElementById("from_date").addEventListener("change", handleStitcherChange);
document.getElementById("to_date").addEventListener("change", handleStitcherChange);

// Trigger initial fetch when page loads
handleStitcherChange();

    </script>
</body>
</html>