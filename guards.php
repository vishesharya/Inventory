<?php
include './include/check_login.php';
include './include/connection.php';
include_once 'include/admin-main.php';
include('access_control.php');



// Directory to store uploaded signatures
$target_dir = "uploads/";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_guard'])) {
        // Handle file upload
        $target_file = $target_dir . basename($_FILES["guard_signature"]["name"]);
        move_uploaded_file($_FILES["guard_signature"]["tmp_name"], $target_file);
        
        // Add a new guard
        $name = $_POST['guard_name'];
        $signature = $target_file;
        $sql = "INSERT INTO guards (name, signature, status) VALUES ('$name', '$signature', 0)";
        $con->query($sql);
    } elseif (isset($_POST['edit_guard'])) {
        // Handle file upload if a new file is uploaded 
        $id = $_POST['guard_id'];
        $name = $_POST['guard_name'];
        $signature = $_FILES["guard_signature"]["name"] ? $target_dir . basename($_FILES["guard_signature"]["name"]) : null;
        
        if ($signature) {
            move_uploaded_file($_FILES["guard_signature"]["tmp_name"], $signature);
            $sql = "UPDATE guards SET name='$name', signature='$signature' WHERE id=$id";
        } else {
            $sql = "UPDATE guards SET name='$name' WHERE id=$id";
        }
        $con->query($sql);
    } elseif (isset($_POST['delete_guard'])) {
        // Delete a guard
        $id = $_POST['guard_id'];
        $sql = "DELETE FROM guards WHERE id=$id";
        $con->query($sql);
    } elseif (isset($_POST['default_guard'])) {
        // Set a guard as default
        $id = $_POST['guard_id'];
        $con->query("UPDATE guards SET status=0");
        $con->query("UPDATE guards SET status=1 WHERE id=$id");
    }
}

// Retrieve guards from the database after every action to reflect changes
$guards = $con->query("SELECT * FROM guards");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guard Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        h2 {
            margin-bottom: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .img-thumbnail {
            max-height: 100px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Manage Guards</h2>
    
    <!-- Add Guard -->
    <div class="card">
        <div class="card-header">
            <h5>Add Guard</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="add_guard" value="1">
                <div class="mb-3">
                    <label for="guard-name" class="form-label">Guard Name:</label>
                    <input type="text" name="guard_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="guard-signature" class="form-label">Guard Signature (Image):</label>
                    <input type="file" name="guard_signature" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Guard</button>
            </form>
        </div>
    </div>

    <!-- Edit Guard -->
    <div class="card">
        <div class="card-header">
            <h5>Edit Guard</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="edit_guard" value="1">
                <div class="mb-3">
                    <label for="guard-id" class="form-label">Select Guard:</label>
                    <select name="guard_id" class="form-select" required>
                        <?php
                        $guards->data_seek(0); // Reset pointer to the start
                        while ($guard = $guards->fetch_assoc()) { ?>
                            <option value="<?= $guard['id'] ?>"><?= $guard['name'] ?> (Status: <?= $guard['status'] == 1 ? 'Default' : 'Not Default' ?>)</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="guard-name" class="form-label">Guard Name:</label>
                    <input type="text" name="guard_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="guard-signature" class="form-label">Guard Signature (Image):</label>
                    <input type="file" name="guard_signature" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-warning">Edit Guard</button>
            </form>
        </div>
    </div>

    <!-- Delete Guard -->
    <div class="card">
        <div class="card-header">
            <h5>Delete Guard</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="delete_guard" value="1">
                <div class="mb-3">
                    <label for="guard-id" class="form-label">Select Guard:</label>
                    <select name="guard_id" class="form-select" required>
                        <?php
                        $guards->data_seek(0); // Reset pointer to the start
                        while ($guard = $guards->fetch_assoc()) { ?>
                            <option value="<?= $guard['id'] ?>"><?= $guard['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger">Delete Guard</button>
            </form>
        </div>
    </div>

    <!-- Set Default Guard -->
    <div class="card">
        <div class="card-header">
            <h5>Set Default Guard</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="default_guard" value="1">
                <div class="mb-3">
                    <label for="guard-id" class="form-label">Select Guard:</label>
                    <select name="guard_id" class="form-select" required>
                        <?php
                        $guards->data_seek(0); // Reset pointer to the start
                        while ($guard = $guards->fetch_assoc()) { ?>
                            <option value="<?= $guard['id'] ?>"><?= $guard['name'] ?> (Status: <?= $guard['status'] == 1 ? 'Default' : 'Not Default' ?>)</option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Set Default</button>
            </form>
        </div>
    </div>

    <!-- Display Signatures -->
    <div class="card">
        <div class="card-header">
            <h5>Guard Signatures</h5>
        </div>
        <div class="card-body">
            <?php
            $guards->data_seek(0); // Reset pointer to the start
            while ($guard = $guards->fetch_assoc()) { ?>
                <div class="mb-3">
                    <strong><?= $guard['name'] ?>:</strong><br>
                    <img src="<?= $guard['signature'] ?>" alt="Signature" class="img-thumbnail">
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$con->close();
?>
