<?php
include './include/connection.php';
include './include/check_login.php';

// Directory to store uploaded signatures
$target_dir = "uploads/";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_supervisor'])) {
        // Handle file upload
        $target_file = $target_dir . basename($_FILES["supervisor_signature"]["name"]);
        move_uploaded_file($_FILES["supervisor_signature"]["tmp_name"], $target_file);
        
        // Add a new supervisor
        $name = $_POST['supervisor_name'];
        $signature = $target_file;
        $sql = "INSERT INTO supervisors (name, signature, status) VALUES ('$name', '$signature', 0)";
        $con->query($sql);
    } elseif (isset($_POST['edit_supervisor'])) {
        // Handle file upload if a new file is uploaded
        $id = $_POST['supervisor_id'];
        $name = $_POST['supervisor_name'];
        $signature = $_FILES["supervisor_signature"]["name"] ? $target_dir . basename($_FILES["supervisor_signature"]["name"]) : null;
        
        if ($signature) {
            move_uploaded_file($_FILES["supervisor_signature"]["tmp_name"], $signature);
            $sql = "UPDATE supervisors SET name='$name', signature='$signature' WHERE id=$id";
        } else {
            $sql = "UPDATE supervisors SET name='$name' WHERE id=$id";
        }
        $con->query($sql);
    } elseif (isset($_POST['delete_supervisor'])) {
        // Delete a supervisor
        $id = $_POST['supervisor_id'];
        $sql = "DELETE FROM supervisors WHERE id=$id";
        $con->query($sql);
    } elseif (isset($_POST['default_supervisor'])) {
        // Set a supervisor as default
        $id = $_POST['supervisor_id'];
        $con->query("UPDATE supervisors SET status=0");
        $con->query("UPDATE supervisors SET status=1 WHERE id=$id");
    }
}

// Retrieve supervisors from the database after every action to reflect changes
$supervisors = $con->query("SELECT * FROM supervisors");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Management</title>
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
    <h2 class="text-center">Manage Supervisors</h2>
    
    <!-- Add Supervisor -->
    <div class="card">
        <div class="card-header">
            <h5>Add Supervisor</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="add_supervisor" value="1">
                <div class="mb-3">
                    <label for="supervisor-name" class="form-label">Supervisor Name:</label>
                    <input type="text" name="supervisor_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="supervisor-signature" class="form-label">Supervisor Signature (Image):</label>
                    <input type="file" name="supervisor_signature" class="form-control" accept="image/*" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Supervisor</button>
            </form>
        </div>
    </div>

    <!-- Edit Supervisor -->
    <div class="card">
        <div class="card-header">
            <h5>Edit Supervisor</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="edit_supervisor" value="1">
                <div class="mb-3">
                    <label for="supervisor-id" class="form-label">Select Supervisor:</label>
                    <select name="supervisor_id" class="form-select" required>
                        <?php
                        $supervisors->data_seek(0); // Reset pointer to the start
                        while ($supervisor = $supervisors->fetch_assoc()) { ?>
                            <option value="<?= $supervisor['id'] ?>"><?= $supervisor['name'] ?> (Status: <?= $supervisor['status'] == 1 ? 'Default' : 'Not Default' ?>)</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="supervisor-name" class="form-label">Supervisor Name:</label>
                    <input type="text" name="supervisor_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="supervisor-signature" class="form-label">Supervisor Signature (Image):</label>
                    <input type="file" name="supervisor_signature" class="form-control" accept="image/*">
                </div>
                <button type="submit" class="btn btn-warning">Edit Supervisor</button>
            </form>
        </div>
    </div>

    <!-- Delete Supervisor -->
    <div class="card">
        <div class="card-header">
            <h5>Delete Supervisor</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="delete_supervisor" value="1">
                <div class="mb-3">
                    <label for="supervisor-id" class="form-label">Select Supervisor:</label>
                    <select name="supervisor_id" class="form-select" required>
                        <?php
                        $supervisors->data_seek(0); // Reset pointer to the start
                        while ($supervisor = $supervisors->fetch_assoc()) { ?>
                            <option value="<?= $supervisor['id'] ?>"><?= $supervisor['name'] ?></option>
                        <?php } ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger">Delete Supervisor</button>
            </form>
        </div>
    </div>

    <!-- Set Default Supervisor -->
    <div class="card">
        <div class="card-header">
            <h5>Set Default Supervisor</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <input type="hidden" name="default_supervisor" value="1">
                <div class="mb-3">
                    <label for="supervisor-id" class="form-label">Select Supervisor:</label>
                    <select name="supervisor_id" class="form-select" required>
                        <?php
                        $supervisors->data_seek(0); // Reset pointer to the start
                        while ($supervisor = $supervisors->fetch_assoc()) { ?>
                            <option value="<?= $supervisor['id'] ?>"><?= $supervisor['name'] ?> (Status: <?= $supervisor['status'] == 1 ? 'Default' : 'Not Default' ?>)</option>
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
            <h5>Supervisor Signatures</h5>
        </div>
        <div class="card-body">
            <?php
            $supervisors->data_seek(0); // Reset pointer to the start
            while ($supervisor = $supervisors->fetch_assoc()) { ?>
                <div class="mb-3">
                    <strong><?= $supervisor['name'] ?>:</strong><br>
                    <img src="<?= $supervisor['signature'] ?>" alt="Signature" class="img-thumbnail">
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
