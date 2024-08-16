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
        $stmt = $con->prepare("INSERT INTO guards (name, signature, status) VALUES (:name, :signature, 0)");
        $stmt->execute([':name' => $name, ':signature' => $signature]);

        echo "<div class='alert alert-success'>Guard added successfully!</div>";
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

            $stmt = $con->prepare("UPDATE guards SET name = :name, signature = :signature WHERE id = :id");
            $stmt->execute([':name' => $name, ':signature' => $signature, ':id' => $id]);
        } else {
            $stmt = $con->prepare("UPDATE guards SET name = :name WHERE id = :id");
            $stmt->execute([':name' => $name, ':id' => $id]);
        }

        echo "<div class='alert alert-success'>Guard updated successfully!</div>";
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];

        // Fetch the signature filename to delete it from the server
        $stmt = $con->prepare("SELECT signature FROM guards WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $signature = $stmt->fetchColumn();

        // Delete the signature file
        unlink('uploads/signatures/' . $signature);

        // Delete guard from the database
        $stmt = $con->prepare("DELETE FROM guards WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo "<div class='alert alert-success'>Guard deleted successfully!</div>";
    }

    if (isset($_POST['change_guard'])) {
        $id = $_POST['id'];

        // Set all guards' status to 0
        $stmt = $con->prepare("UPDATE guards SET status = 0");
        $stmt->execute();

        // Set the selected guard's status to 1
        $stmt = $con->prepare("UPDATE guards SET status = 1 WHERE id = :id");
        $stmt->execute([':id' => $id]);

        echo "<div class='alert alert-success'>Guard status updated successfully!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guard Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Guard Management</h2>
    
    <div class="row">
        <div class="col-md-6">
            <h3>Upload Guard</h3>
            <form method="POST" enctype="multipart/form-data" class="mb-4">
                <div class="mb-3">
                    <label for="name" class="form-label">Guard Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Guard Name" required>
                </div>
                <div class="mb-3">
                    <label for="signature" class="form-label">Signature</label>
                    <input type="file" name="signature" id="signature" class="form-control" required>
                </div>
                <button type="submit" name="upload" class="btn btn-primary">Upload Guard</button>
            </form>
        </div>

        <div class="col-md-6">
            <h3>Edit Guard</h3>
            <form method="POST" enctype="multipart/form-data" class="mb-4">
                <input type="hidden" name="id" value="<!-- Guard ID for Edit -->">
                <div class="mb-3">
                    <label for="edit-name" class="form-label">Guard Name</label>
                    <input type="text" name="name" id="edit-name" class="form-control" placeholder="Guard Name" required>
                </div>
                <div class="mb-3">
                    <label for="edit-signature" class="form-label">Signature</label>
                    <input type="file" name="signature" id="edit-signature" class="form-control">
                </div>
                <button type="submit" name="edit" class="btn btn-warning">Edit Guard</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <h3>Delete Guard</h3>
            <form method="POST" class="mb-4">
                <input type="hidden" name="id" value="<!-- Guard ID for Delete -->">
                <button type="submit" name="delete" class="btn btn-danger">Delete Guard</button>
            </form>
        </div>

        <div class="col-md-6">
            <h3>Change Guard Status</h3>
            <form method="POST" class="mb-4">
                <input type="hidden" name="id" value="<!-- Guard ID for Change -->">
                <button type="submit" name="change_guard" class="btn btn-success">Change Guard</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>