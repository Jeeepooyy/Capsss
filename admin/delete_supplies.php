<?php
require_once 'logincheck.php'; // Ensures only logged-in users can access this page
$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());

// Check if the form is submitted
if (isset($_POST['delete_supply'])) {
    $supply_id = $_POST['supply_id'];

    // Delete the supply from the database
    $delete_query = "DELETE FROM `supplies` WHERE `supply_id` = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $supply_id);

    if ($stmt->execute()) {
        // Redirect with success message
        echo "<script>alert('Supply deleted successfully!');</script>";
        echo "<script>window.location = 'supplies.php';</script>";
    } else {
        // Redirect with error message
        echo "<script>alert('Error deleting supply. Please try again.');</script>";
        echo "<script>window.location = 'supplies.php';</script>";
    }

    $stmt->close();
}

$conn->close();
