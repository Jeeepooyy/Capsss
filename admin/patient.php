<!DOCTYPE html>
<?php
require_once 'logincheck.php';
?>
<html lang="eng">

<head>
	<title>Health Center Patient Record Management System</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../images/loogo.png" />
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="../css/jquery.dataTables.css" />
	<link rel="stylesheet" type="text/css" href="../css/customize.css" />
</head>

<body>
	<div class="navbar navbar-default navbar-fixed-top">
		<img src="../images/loogo.png" style="float:left;" height="55px" />
		<label class="navbar-brand">San Luis Health Center Patient Record Management System</label>
		<?php
		$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());
		$q = $conn->query("SELECT * FROM `admin` WHERE `admin_id` = '$_SESSION[admin_id]'") or die(mysqli_error());
		$f = $q->fetch_array();
		?>
		<ul class="nav navbar-right">
			<li class="dropdown">
				<a class="user dropdown-toggle" data-toggle="dropdown" href="#">
					<span class="glyphicon glyphicon-user"></span>
					<?php
					echo $f['firstname'] . " " . $f['lastname'];
					$conn->close();
					?>
					<b class="caret"></b>
				</a>
				<ul class="dropdown-menu">
					<li>
						<a class="me" href="logout.php"><i class="glyphicon glyphicon-log-out"></i> Logout</a>
					</li>
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
		<div style="display:none;" id="add_itr" class="panel panel-success">
			<div class="panel-heading">
				<label>ADD PATIENT INFORMATION</label>
				<button id="hide_itr" style="float:right; margin-top:-4px;" class="btn btn-sm btn-danger"><span class="glyphicon glyphicon-remove"></span> CLOSE</button>
			</div>
			<div class="panel-body">
				<form id="form_dental" method="POST" enctype="multipart/form-data">
					<div style="float:left;" class="form-inline">
						<label for="itr_no">ITR No:</label>
						<input class="form-control" size="3" min="0" type="number" name="itr_no">
					</div>
					<div style="float:right;" class="form-inline">
						<label for="family_no">Family no:</label>
						<input class="form-control" placeholder="(Optional)" size="5" type="text" name="family_no">
					</div>
					<br />
					<br />
					<br />
					<div class="form-inline">
						<label for="firstname">Firstname:</label>
						<input class="form-control" name="firstname" type="text" required="required">
						&nbsp;&nbsp;&nbsp;
						<label for="middlename">Middle Name:</label>
						<input class="form-control" name="middlename" placeholder="(Optional)" type="text">
						&nbsp;&nbsp;&nbsp;
						<label for="lastname">Lastname:</label>
						<input class="form-control" name="lastname" type="text" required="required">
					</div>
					<br />
					<div class="form-group">
						<label for="birthdate" style="float:left;">Birthdate:</label>
						<br style="clear:both;" />
						<select name="month" style="width:15%; float:left;" class="form-control" required="required">
							<option value="">Select a month</option>
							<option value="01">January</option>
							<option value="02">February</option>
							<option value="03">March</option>
							<option value="04">April</option>
							<option value="05">May</option>
							<option value="06">June</option>
							<option value="07">July</option>
							<option value="08">August</option>
							<option value="09">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						</select>
						<select name="day" class="form-control" style="width:13%; float:left;" required="required">
							<option value="">Select a day</option>
							<option value="01">01</option>
							<option value="02">02</option>
							<?php for ($i = 3; $i <= 31; $i++) echo "<option value='$i'>$i</option>"; ?>
						</select>
						<select name="year" class="form-control" style="width:13%; float:left;" required="required">
							<option value="">Select a year</option>
							<?php for ($i = date("Y"); $i >= 1965; $i--) echo "<option value='$i'>$i</option>"; ?>
						</select>
						<br style="clear:both;" />
						<br />
						<label for="phil_health_no">Phil Health no:</label>
						<input name="phil_health_no" placeholder="(if any)" class="form-control" type="text">
						<br />
						<label for="address">Address:</label>
						<select class="form-control" name="address" required="required">
							<option value="">-- Please select an address --</option>
							<option value="Abiacao, San Luis, Batangas">Abiacao</option>
							<option value="Bagong Tubig, San Luis, Batangas">Bagong Tubig</option>
							<option value="Balagtasin, San Luis, Batangas">Balagtasin</option>
							<option value="Balite, San Luis, Batangas">Balite</option>
							<option value="Banoyo, San Luis, Batangas">Banoyo</option>
							<option value="Boboy, San Luis, Batangas">Boboy</option>
							<option value="Bonliw, San Luis, Batangas">Bonliw</option>
							<option value="Calumpang West, San Luis, Batangas">Calumpang West</option>
							<option value="Calumpang East, San Luis, Batangas">Calumpang East</option>
							<option value="Dulangan, San Luis, Batangas">Dulangan</option>
							<option value="Durungao, San Luis, Batangas">Durungao</option>
							<option value="Locloc, San Luis, Batangas">Locloc</option>
							<option value="Luya, San Luis, Batangas">Luya</option>
							<option value="Mahabang Parang, San Luis, Batangas">Mahabang Parang</option>
							<option value="Manggahan, San Luis, Batangas">Manggahan</option>
							<option value="Muzon, San Luis, Batangas">Muzon</option>
							<option value="San Antonio, San Luis, Batangas">San Antonio</option>
							<option value="San Isidro, San Luis, Batangas">San Isidro</option>
							<option value="San Jose, San Luis, Batangas">San Jose</option>
							<option value="San Martin, San Luis, Batangas">San Martin</option>
							<option value="Santa Monica, San Luis, Batangas">Santa Monica</option>
							<option value="Taliba, San Luis, Batangas">Taliba</option>
							<option value="Talon, San Luis, Batangas">Talon</option>
							<option value="Tejero, San Luis, Batangas">Tejero</option>
							<option value="Tungal, San Luis, Batangas">Tungal</option>
							<option value="Poblacion, San Luis, Batangas">Poblacion</option>
						</select>
						<br />
						<label for="age">Age:</label>
						<input class="form-control" style="width:20%;" min="0" max="999" name="age" type="number">
						<br />
						<label for="civil_status">Civil Status:</label>
						<select style="width:22%;" class="form-control" name="civil_status" required="required">
							<option value="">--Please select an option--</option>
							<option value="Single">Single</option>
							<option value="Married">Married</option>
						</select>
						<br />
						<label for="gender">Gender:</label>
						<select style="width:22%;" class="form-control" name="gender" required="required">
							<option value="">--Please select an option--</option>
							<option value="Male">Male</option>
							<option value="Female">Female</option>
						</select>
					</div>
					<br />
					<div class="form-inline">
						<label for="bp">BP:</label>
						<input class="form-control" name="bp" type="text" required="required">
						&nbsp;&nbsp;&nbsp;
						<label for="temp">TEMP:</label>
						<input class="form-control" name="temp" type="number" max="999" min="0" size="15" required="required"><label>&deg;C</label>
						&nbsp;&nbsp;&nbsp;
						<label for="pr">PR:</label>
						<input class="form-control" name="pr" type="text" required="required">
						<br />
						<br />
						<label for="rr">RR:</label>
						<input class="form-control" name="rr" type="text" required="required">
						&nbsp;&nbsp;&nbsp;
						<label for="wt">WT :</label>
						<input class="form-control" name="wt" style="width:10%;" type="number" required="required"><label>kg</label>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<label for="ht">HT :</label>
						<input class="form-control" name="ht" style="margin-right:10px;" type="text" required="required">
					</div>
					<br />
					<div class="form-inline">
						<button class="btn btn-primary" name="save_patient"><span class="glyphicon glyphicon-save"></span> SAVE</button>
					</div>
				</form>
			</div>
		</div>
		<?php require '../add_patient.php' ?>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<label>PATIENTS LIST</Label>
			</div>
			<div class="panel-body">
				<button id="show_itr" class="btn btn-info"><span class="glyphicon glyphicon-plus"></span> ADD PATIENT</button>
				<br />
				<br />
				<table id="table" class="display" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>ITR No</th>
							<th>Name</th>
							<th>Birthdate</th>
							<th>Age</th>
							<th>Address</th>
							<th>Civil Status</th>
							<th>
								<center>Action</center>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());
						$query = $conn->query("SELECT * FROM `itr` ORDER BY `itr_no` DESC") or die(mysqli_error());
						while ($fetch = $query->fetch_array()) {
							$id = $fetch['itr_no'];
							$q = $conn->query("SELECT COUNT(*) as total FROM `complaints` where `itr_no` = '$id' && `status` = 'Pending'") or die(mysqli_error());
							$f = $q->fetch_array();
						?>
							<tr>
								<td><?php echo $fetch['itr_no'] ?></td>
								<td><?php echo $fetch['firstname'] . " " . $fetch['lastname'] ?></td>
								<td><?php echo $fetch['birthdate'] ?></td>
								<td><?php echo $fetch['age'] ?></td>
								<td><?php echo $fetch['address'] ?></td>
								<td><?php echo $fetch['civil_status'] ?></td>
								<td>
									<center>
										<a href="complaints.php?id=<?php echo $fetch['itr_no'] ?>&lastname=<?php echo $fetch['lastname'] ?>" class="btn btn-sm btn-info">Complaints <span class="badge"><?php echo $f['total'] ?></span></a>
										<a href="edit_patient.php?id=<?php echo $fetch['itr_no'] ?>&lastname=<?php echo $fetch['lastname'] ?>" class="btn btn-sm btn-warning"><span class="glyphicon glyphicon-pencil"></span> Update</a>
										<a href="delete_patient.php?id=<?php echo $fetch['itr_no'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this patient?');"><span class="glyphicon glyphicon-trash"></span> Delete</a>
									</center>
								</td>
							</tr>
						<?php
						}
						$conn->close();
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<?php include("script.php"); ?>
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
</body>

</html>