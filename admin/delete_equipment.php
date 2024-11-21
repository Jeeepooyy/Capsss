<?php
require_once 'logincheck.php';
$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());

// Check if the delete request is made
if (isset($_POST['delete_equipment'])) {
    $equipment_id = $_POST['equipment_id'];

    // Prepare the delete statement to avoid SQL injection
    $stmt = $conn->prepare("DELETE FROM equipment WHERE equipment_id = ?");
    $stmt->bind_param("i", $equipment_id); // "i" indicates the type is integer

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "<script>alert('Equipment deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting equipment. Please try again.');</script>";
    }

    $stmt->close();
    echo "<script>window.location.href='equipment.php';</script>"; // Redirect back to equipment page
} else {
    // Redirect back to the equipment page if accessed directly
    header("Location: equipment.php");
    exit();
}
