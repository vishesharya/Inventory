<?php
include './include/connection.php';
include './include/check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['upload'])) {
        $name = $_POST['name'];
        $signature = $_FILES['signature']['name'];
        $signature_temp = $_FILES['signature']['tmp_name'];

        // Move the uploaded signature to a directory
        $upload_dir = 'uploads/signatures/';
        $target_file = $upload_dir . basename($signature);
        move_uploaded_file($signature_temp, $target_file);

        // Insert guard details into the database
        $stmt = $pdo->prepare("INSERT INTO guards (name, signature, status) VALUES (:name, :signature, 0)");
        $stmt->execute([':name' => $name, ':signature' => $signature]);

        echo "Guard added successfully!";
    }

    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $signature = $_FILES['signature']['name'];
        $signature_temp = $_FILES['signature']['tmp_name'];

        // Update signature if a new one is uploaded
        if (!empty($signature)) {
            $upload_dir = 'uploads/signatures/';
            $target_file = $upload_dir . basename($signature);
            move_uploaded_file($signature_temp, $target_file);

            $stmt = $pdo->prepare("UPDATE guards SET name = :name, signature = :signature WHERE id = :id");
            $stmt->execute([':name' => $name, ':signature' => $signature, ':id' => $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE guards SET name = :name WHERE id = :id");
            $stmt->execute([':name' => $name, ':id' => $id]);
        }

        echo "Guard updated successfully!";
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        // Fetch the signature filename to delete it from the server
        $stmt = $pdo->prepare("SELECT signature FROM guards WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $signature = $stmt->fetchColumn();

        // Delete the signature file
        unlink('uploads/signatures/' . $signature);

        // Delete guard from the database
        $stmt = $pdo->prepare("DELETE FROM guards WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo "Guard deleted successfully!";
    }

    if (isset($_POST['change_guard'])) {
        $id = $_POST['id'];

        // Set all guards' status to 0
        $stmt = $pdo->prepare("UPDATE guards SET status = 0");
        $stmt->execute();

        // Set the selected guard's status to 1
        $stmt = $pdo->prepare("UPDATE guards SET status = 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo "Guard status updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guard Management</title>
</head>
<body>
    <h2>Guard Management</h2>

    <form method="POST" enctype="multipart/form-data">
        <!-- Upload Guard -->
        <h3>Upload Guard</h3>
        <input type="text" name="name" placeholder="Guard Name" required>
        <input type="file" name="signature" required>
        <button type="submit" name="upload">Upload Guard</button>
        <br><br>

        <!-- Edit Guard -->
        <h3>Edit Guard</h3>
        <input type="hidden" name="id" value="<!-- Guard ID for Edit -->">
        <input type="text" name="name" placeholder="Guard Name" required>
        <input type="file" name="signature">
        <button type="submit" name="edit">Edit Guard</button>
        <br><br>

        <!-- Delete Guard -->
        <h3>Delete Guard</h3>
        <input type="hidden" name="id" value="<!-- Guard ID for Delete -->">
        <button type="submit" name="delete">Delete Guard</button>
        <br><br>

        <!-- Change Guard Status -->
        <h3>Change Guard Status</h3>
        <input type="hidden" name="id" value="<!-- Guard ID for Change -->">
        <button type="submit" name="change_guard">Change Guard</button>
    </form>
</body>
</html>
