<?php
if (isset($_POST['save_complaints'])) {
	// Initialize database connection
	$conn = new mysqli("localhost", "root", "", "hcpms");
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	// Fetch POST data
	$date = date('Y-m-d H:i:s');  // Use full datetime format for accuracy
	$complaints = $_POST['complaints'];
	$remarks = $_POST['remarks'];
	$section = $_POST['section'];
	$itr_no = $_GET['id'];
	$lastname = $_GET['lastname'];

	// Validate the section based on patient gender
	$q = $conn->query("SELECT * FROM `itr` WHERE `itr_no` = '$itr_no' AND `lastname` = '$lastname'");
	if ($q && $q->num_rows > 0) {
		$f = $q->fetch_assoc();
		$gender = $f['gender'];

		if (($section == "Prenatal" || $section == "Maternity") && ($gender == "Male")) {
			echo "<script>alert('Wrong section!');</script>";
		} else {
			// Insert the complaint record
			$stmt = $conn->prepare("INSERT INTO `complaints` (`date`, `complaints`, `remark`, `itr_no`, `section`, `status`) VALUES (?, ?, ?, ?, ?, 'Pending')");
			$stmt->bind_param("sssss", $date, $complaints, $remarks, $itr_no, $section);

			if ($stmt->execute()) {
				echo "<script>alert('Complaint saved successfully!');</script>";
				echo "<script>location.replace('patient.php');</script>";
			} else {
				echo "<script>alert('Error saving complaint.');</script>";
			}

			$stmt->close();
		}
	} else {
		echo "<script>alert('Patient record not found.');</script>";
	}

	$conn->close();
}
