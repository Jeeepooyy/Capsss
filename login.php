<?php
session_start();

if (isset($_POST['login'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());

	// Check if the user is an admin
	$adminQuery = $conn->query("SELECT * FROM `admin` WHERE `username` = '$username' AND `password` = '$password'") or die(mysqli_error());
	$isAdmin = $adminQuery->num_rows > 0;

	if ($isAdmin) {
		$adminFetch = $adminQuery->fetch_array();
		$_SESSION['admin_id'] = $adminFetch['admin_id'];
		echo ("<script> location.replace('./admin/home.php')</script>");
	} else {
		// Check if the user is a regular user
		$userQuery = $conn->query("SELECT * FROM `user` WHERE `username` = '$username' AND `password` = '$password'") or die(mysqli_error());
		$isUser = $userQuery->num_rows > 0;

		if ($isUser) {
			$userFetch = $userQuery->fetch_array();
			$_SESSION['user_id'] = $userFetch['user_id'];
			$section = $userFetch['section'];

			// Redirect based on the section
			switch ($section) {
				case "Fecalysis":
					echo ("<script> location.replace('fecalysis.php');</script>");
					break;
				case "Maternity":
					echo ("<script> location.replace('maternity.php');</script>");
					break;
				case "Hematology":
					echo ("<script> location.replace('hematology.php');</script>");
					break;
				case "Dental":
					echo ("<script> location.replace('dental.php');</script>");
					break;
				case "Xray":
					echo ("<script> location.replace('xray.php');</script>");
					break;
				case "Rehabilitation":
					echo ("<script> location.replace('rehabilitation.php');</script>");
					break;
				case "Sputum":
					echo ("<script> location.replace('sputum.php');</script>");
					break;
				case "Urinalysis":
					echo ("<script> location.replace('urinalysis.php');</script>");
					break;
				default:
					echo ("<script>alert('Invalid Section!')</script>");
					echo ("<script>window.location = 'index.php'</script>");
			}
		} else {
			echo "<script>alert('Invalid username or password')</script>";
			echo "<script>window.location = 'index.php'</script>";
		}
	}

	$conn->close();
}
