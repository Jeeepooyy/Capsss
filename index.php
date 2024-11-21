<!DOCTYPE html>
<html lang="en">

<head>
	<title>Health Center Patient Record Management System</title>
	<meta charset="utf-8" />
	<link rel="shortcut icon" href="images/loogo.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="css/customize.css" />
	<style>
		body {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100vh;
			margin: 0;
			background: url('images/victorias.jpg') no-repeat center center fixed;
			background-size: cover;
		}

		#sidelogin {
			background-color: rgba(255, 255, 255, 0.8);
			padding: 20px;
			border-radius: 10px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
		}

		.navbar.navtop {
			position: absolute;
			top: 0;
			width: 100%;
		}

		#footer {
			position: absolute;
			bottom: 0;
			width: 100%;
			text-align: center;
			padding: 10px;
			background-color: rgba(255, 255, 255, 0.8);
		}
	</style>
</head>

<body>
	<div class="navbar navbar-default navtop">
		<img src="images/loogo.png" style="float:left;" height="55px" />
		<label class="navbar-brand">San Luis Health Center Patient Record Management System</label>
	</div>

	<div id="sidelogin">
		<form action="login.php" enctype="multipart/form-data" method="POST">
			<label class="lbllogin">Please Login Here...</label>
			<br />
			<br />
			<div class="form-group">
				<label for="username">Username</label>
				<input class="form-control" type="text" name="username" required="required" />
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input class="form-control" type="password" name="password" required="required" />
			</div>
			<br />
			<div class="form-group">
				<button class="btn btn-success form-control" type="submit" name="login"><span class="glyphicon glyphicon-log-in"></span> Login</button>
			</div>
		</form>
	</div>

</body>
<?php
include("admin/script.php");
?>

</html>