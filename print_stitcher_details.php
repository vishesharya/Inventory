<?php
session_start();

include './include/connection.php';
include_once '<include/admin-main.php';

include './include/connection.php';

// Fetch all stitcher details
$query = "SELECT * FROM stitcher";
$result = mysqli_query($con, $query);

// HTML for printing
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Stitcher Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            width: 100%;
            margin: 0 auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Stitcher Details</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Aadhar No</th>
                    <th>PAN No</th>
                    <th>Bank Name</th>
                    <th>Account No</th>
                    <th>IFSC Code</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . ($row['stitcher_name'] ? $row['stitcher_name'] : '-') . "</td>";
                        echo "<td>" . ($row['stitcher_contact'] ? $row['stitcher_contact'] : '-') . "</td>";
                        echo "<td>" . ($row['stitcher_address'] ? $row['stitcher_address'] : '-') . "</td>";
                        echo "<td>" . ($row['stitcher_aadhar'] ? $row['stitcher_aadhar'] : '-') . "</td>";
                        echo "<td>" . ($row['stitcher_pan'] ? $row['stitcher_pan'] : '-') . "</td>";
                        echo "<td>" . ($row['bank_name'] ? $row['bank_name'] : '-') . "</td>";
                        echo "<td>" . ($row['bank_no'] ? $row['bank_no'] : '-') . "</td>";
                        echo "<td>" . ($row['ifsc_code'] ? $row['ifsc_code'] : '-') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' style='text-align:center;'>No stitchers found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button class="print-button" onclick="window.print()">Print</button>
    </div>
</body>

</html>
