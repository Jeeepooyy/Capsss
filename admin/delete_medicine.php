<?php
require_once 'logincheck.php'; // Ensures only logged-in users can access this page
$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());

// Check if the delete request is made
if (isset($_POST['delete_medicine'])) {
    $medicine_id = $_POST['medicine_id'];

    // Prepare the delete statement to avoid SQL injection
    $stmt = $conn->prepare("DELETE FROM medicines WHERE medicine_id = ?");
    $stmt->bind_param("i", $medicine_id); // "i" indicates the type is integer

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "<script>alert('Medicine deleted successfully!'); window.location.href='medicines.php';</script>";
    } else {
        echo "<script>alert('Error deleting medicine. Please try again.'); window.location.href='medicines.php';</script>";
    }

    $stmt->close();
} else {
    // Redirect back to the medicines page if accessed directly
    header("Location: medicines.php");
    exit();
}
