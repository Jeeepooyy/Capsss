<!DOCTYPE html>
<?php
require_once 'logincheck.php';
$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());
$query = $conn->query("SELECT * FROM `user` WHERE `user_id` = '$_SESSION[user_id]'") or die(mysqli_error());
$fetch = $query->fetch_array();
?>
<html lang="en">

<head>
	<title>Health Center Patient Record Management System</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="images/loogo.png" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css" />
	<link rel="stylesheet" type="text/css" href="css/customize.css" />
</head>

<body>
	<div class="navbar navbar-default navbar-fixed-top">
		<img src="images/loogo.png" style="float:left;" height="55px" /><label class="navbar-brand">San Luis Health Center Patient Record Management System</label>
		<ul class="nav navbar-right">
			<li class="dropdown">
				<a class="user dropdown-toggle" data-toggle="dropdown" href="#">
					<span class="glyphicon glyphicon-user"></span>
					<?php echo $fetch['firstname'] . " " . $fetch['lastname'] ?>
					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a class="me" href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a>
					</li>
				</ul>
			</li>
		</ul>
	</div>
	<br />
	<br />
	<br />
	<div class="well">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<center><label>HEMATOLOGY</label></center>
			</div>
		</div>
		<a style="float:right; margin-top:-4px;" href="view_hematology_record.php?itr_no=<?php echo $_GET['itr_no'] ?>?>" class="btn btn-info"><span class="glyphicon glyphicon-hand-right"></span> BACK</a>
		<br />
		<br />
		<div class="panel panel-primary">
			<?php
			$q1 = $conn->query("SELECT * FROM `itr` WHERE `itr_no` = '$_GET[itr_no]'") or die(mysqli_error());
			$f1 = $q1->fetch_array();
			?>
			<div class="panel-heading">
				<h4>Hematology Record / <?php echo $f1['firstname'] . " " . $f1['lastname'] ?></h4>
			</div>
			<div class="panel-body">
				<table id="table" cellspacing="0" class="display">
					<thead>
						<tr>
							<th>Date</th>
							<th>Pathologist</th>
							<th>Medical Technologist</th>
							<th>
								<center>Action</center>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());
						$q = $conn->query("SELECT * FROM `hematology` NATURAL JOIN `itr` WHERE `itr_no` = '$_GET[itr_no]' ORDER BY `date_requested` DESC") or die(mysqli_error());
						while ($f = $q->fetch_array()) {
						?>
							<tr>
								<td><?php echo date("m/d/Y", strtotime($f['date_requested'])) ?></td>
								<td><?php echo $f['pathologist'] ?></td>
								<td><?php echo $f['medical_technologist'] ?></td>
								<td>
									<center><a class="btn btn-info" href="hematology_form.php?itr_no=<?php echo $f['itr_no'] ?>&hem_id=<?php echo $f['hem_id'] ?>"><span class="glyphicon glyphicon-search"></span> View Detail</a>
										<center>
								</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		</div>

		<br />
	</div>

</body>
<?php require "script3.php" ?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#table").DataTable({
			"paging": false,
			"info": false,
			"order": "DESC"
		});
	});
</script>

</html>