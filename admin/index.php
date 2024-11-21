<!DOCTYPE html>
<html lang="eng">

<head>
	<title>Health Center Patient Record Management System</title>
	<meta charset="utf-8" />
	<link rel="shortcut icon" href="../images/loogo.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="../css/customize.css" />
	<style>
		body {
			background-image: url('../images/victorias.jpg');
			background-size: cover;
			background-repeat: no-repeat;
			background-attachment: fixed;
		}
	</style>
</head>

<body>
	<div class="navbar navbar-default">
		<img src="../images/loogo.png" style="float:left;" height="55px" /><label class="navbar-brand">San Luis Health Center Patient Record Management System </label>
	</div>
	<div id="top" class="login">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<center>
					<h1 class="panel-title">Administrator</h1>
				</center>
			</div>
			<div class="panel-body">
				<form enctype="multipart/form-data" action="login.php" role="form" method="POST">
					<div class="form-group">
						<label for="username">Username</label>
						<input class="form-control" name="username" placeholder="Username" type="text" required="required">
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input class="form-control" name="password" placeholder="Password" type="password" required="required">
					</div>
					<div class="form-group">
						<button class="btn btn-block btn-success" name="login"><span class="glyphicon glyphicon-log-in"></span> Login</button>
					</div>
				</form>
			</div>
		</div>
	</div>

</body>
<?php
include("script.php");
?>
<script type="text/javascript">
	$(document).ready(function() {
		function disableBack() {
			window.history.forward()
		}

		window.onload = disableBack();
		window.onpageshow = function(evt) {
			if (evt.persisted) disableBack()
		}
	});
</script>

</html>