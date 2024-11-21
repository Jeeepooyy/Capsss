<?php
require_once 'logincheck.php';
$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());

// Check if the delete request is made
if (isset($_GET['id'])) {
    $itr_no = $_GET['id'];

    // Start transaction to ensure both delete operations are handled together
    $conn->begin_transaction();

    try {
        // First, delete from the complaints table (if applicable)
        $stmt = $conn->prepare("DELETE FROM complaints WHERE itr_no = ?");
        $stmt->bind_param("i", $itr_no); // "i" indicates the type is integer
        $stmt->execute();
        $stmt->close();

        // Then, delete from the itr table
        $stmt = $conn->prepare("DELETE FROM itr WHERE itr_no = ?");
        $stmt->bind_param("i", $itr_no);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction if both deletes are successful
        $conn->commit();

        echo "<script>alert('Patient deleted successfully!');</script>";
    } catch (Exception $e) {
        // Rollback transaction in case of an error
        $conn->rollback();
        echo "<script>alert('Error deleting patient. Please try again.');</script>";
    }

    echo "<script>window.location.href='patient.php';</script>"; // Redirect back to patient page
} else {
    // Redirect back to the patient page if accessed directly
    header("Location: patient.php");
    exit();
}
