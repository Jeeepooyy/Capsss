<?php
require_once 'logincheck.php';
$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());

// Update stock if form is submitted
if (isset($_POST['update_stock'])) {
    $equipment_id = $_POST['equipment_id'];
    $new_stock = $_POST['stock'];
    $conn->query("UPDATE `equipment` SET `stock` = '$new_stock' WHERE `equipment_id` = '$equipment_id'") or die(mysqli_error());
    echo "<script>alert('Stock updated successfully!');</script>";
}

// Add new equipment if form is submitted
if (isset($_POST['add_equipment'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];

    $conn->query("INSERT INTO `equipment` (`name`, `description`, `stock`) VALUES ('$name', '$description', '$stock')") or die(mysqli_error());
    echo "<script>alert('New equipment added successfully!');</script>";
    echo "<script>window.location = 'equipment.php';</script>"; // Redirect to refresh the page
}

// Fetch all equipment from the database
$equipment = $conn->query("SELECT * FROM `equipment`") or die(mysqli_error());
?>

<!DOCTYPE html>
<html lang="eng">

<head>
    <title>Manage Equipment</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css" />
    <link rel="stylesheet" type="text/css" href="../css/customize.css" />
    <?php require 'script.php' ?>
</head>

<body>
    <div class="navbar navbar-default navbar-fixed-top">
        <img src="../images/loogo.png" style="float:left;" height="55px" />
        <label class="navbar-brand">San Luis Health Center Patient Record Management System</label>
        <ul class="nav navbar-right">
            <li class="dropdown">
                <a class="user dropdown-toggle" data-toggle="dropdown" href="#">
                    <span class="glyphicon glyphicon-user"></span>
                    <?php
                    $q = $conn->query("SELECT * FROM `admin` WHERE `admin_id` = $_SESSION[admin_id]") or die(mysqli_error());
                    $f = $q->fetch_array();
                    echo $f['firstname'] . " " . $f['lastname'];
                    ?>
                    <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="logout.php"><i class="glyphicon glyphicon-log-out"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <div id="sidebar">
        <ul id="menu" class="nav menu">
            <li><a href="home.php"><i class="glyphicon glyphicon-home"></i> GIS Dashboard</a></li>
            <li><a href=""><i class="glyphicon glyphicon-cog"></i> Accounts</a>
                <ul>
                    <li><a href="admin.php"><i class="glyphicon glyphicon-cog"></i> Administrator</a></li>
                    <li><a href="user.php"><i class="glyphicon glyphicon-cog"></i> User</a></li>
                </ul>
            </li>
            <li><a href="patient.php"><i class="glyphicon glyphicon-user"></i> Patient</a></li>
            <li><a href=""><i class="glyphicon glyphicon-folder-close"></i> Sections</a>
                <ul>
                    <li><a href="fecalysis.php"><i class="glyphicon glyphicon-folder-open"></i> Fecalysis</a></li>
                    <li><a href="maternity.php"><i class="glyphicon glyphicon-folder-open"></i> Maternity</a></li>
                    <li><a href="hematology.php"><i class="glyphicon glyphicon-folder-open"></i> Hematology</a></li>
                    <li><a href="dental.php"><i class="glyphicon glyphicon-folder-open"></i> Dental</a></li>
                    <li><a href="xray.php"><i class="glyphicon glyphicon-folder-open"></i> Xray</a></li>
                    <li><a href="rehabilitation.php"><i class="glyphicon glyphicon-folder-open"></i> Rehabilitation</a></li>
                    <li><a href="sputum.php"><i class="glyphicon glyphicon-folder-open"></i> Sputum</a></li>
                    <li><a href="urinalysis.php"><i class="glyphicon glyphicon-folder-open"></i> Urinalysis</a></li>
                </ul>
            </li>

            <!-- Inventory Section -->
            <li><a href="#"><i class="glyphicon glyphicon-th-list"></i> Inventory</a>
                <ul>
                    <li><a href="medicines.php"><i class="glyphicon glyphicon-plus"></i> Medicines</a></li>
                    <li><a href="supplies.php"><i class="glyphicon glyphicon-plus"></i> Supplies</a></li>
                    <li><a href="equipment.php"><i class="glyphicon glyphicon-plus"></i> Equipment</a></li>
                </ul>
            </li>
        </ul>
    </div>

    <div id="content">
        <br />
        <br />
        <br />
        <div class="well">
            <h3 class="text-primary">Manage Equipment</h3>
            <hr>

            <!-- Button to trigger Add Equipment modal -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#addEquipmentModal">Add Equipment</button>
            <br />
            <br />

            <!-- Add Equipment Modal -->
            <div id="addEquipmentModal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Add New Equipment</h4>
                        </div>
                        <form method="POST" action="equipment.php">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>Equipment Name</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" name="description" required>
                                </div>
                                <div class="form-group">
                                    <label>Stock</label>
                                    <input type="number" class="form-control" name="stock" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="add_equipment" class="btn btn-success">Add</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Equipment Table -->
            <table id="table" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Equipment Name</th>
                        <th>Description</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($equip = $equipment->fetch_array()) {
                    ?>
                        <tr>
                            <td><?php echo $equip['name']; ?></td>
                            <td><?php echo $equip['description']; ?></td>
                            <td><?php echo $equip['stock']; ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit<?php echo $equip['equipment_id']; ?>">Edit Stock</button>

                                <!-- Edit Stock Modal for each equipment -->
                                <div id="edit<?php echo $equip['equipment_id']; ?>" class="modal fade" tabindex="-1" role="dialog">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit Stock for <?php echo $equip['name']; ?></h4>
                                            </div>
                                            <form method="POST" action="equipment.php">
                                                <div class="modal-body">
                                                    <input type="hidden" name="equipment_id" value="<?php echo $equip['equipment_id']; ?>">
                                                    <div class="form-group">
                                                        <label>New Stock</label>
                                                        <input type="number" class="form-control" name="stock" value="<?php echo $equip['stock']; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" name="update_stock" class="btn btn-success">Update</button>
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form to delete the equipment -->
                                <form method="POST" action="delete_equipment.php" style="display:inline;">
                                    <input type="hidden" name="equipment_id" value="<?php echo $equip['equipment_id']; ?>">
                                    <button type="submit" name="delete_equipment" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this equipment?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../js/jquery.js"></script>
    <script src="../js/bootstrap.js"></script>
    <script src="../js/jquery.dataTables.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#table').DataTable();
        });
    </script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>

</html>