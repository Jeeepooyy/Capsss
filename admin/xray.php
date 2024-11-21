<!DOCTYPE html>
<?php
require_once 'logincheck.php';
$year = isset($_GET['year']) ? $_GET['year'] : date("Y");
$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());

// Fetch monthly patient counts for the chart based on complaint date
$patient_counts = [];
for ($month = 1; $month <= 12; $month++) {
	$query = $conn->query("SELECT COUNT(*) as total FROM `complaints` WHERE `section` = 'Xray' AND YEAR(`date`) = '$year' AND MONTH(`date`) = '$month'") or die(mysqli_error());
	$result = $query->fetch_array();
	$patient_counts[] = $result['total'];
}
// Fetch age distribution for Xray patients
$age_ranges = [
	"0-10" => "age BETWEEN 0 AND 10",
	"11-20" => "age BETWEEN 11 AND 20",
	"21-30" => "age BETWEEN 21 AND 30",
	"31-40" => "age BETWEEN 31 AND 40",
	"41-50" => "age BETWEEN 41 AND 50",
	"51-60" => "age BETWEEN 51 AND 60",
	"61+" => "age >= 61"
];
$age_distribution = [];
foreach ($age_ranges as $range => $condition) {
	$query = $conn->query("SELECT COUNT(DISTINCT complaints.itr_no) as total FROM `itr` INNER JOIN `complaints` ON itr.itr_no = complaints.itr_no WHERE complaints.section = 'Xray' AND YEAR(complaints.date) = '$year' AND $condition") or die(mysqli_error());
	$result = $query->fetch_array();
	$age_distribution[$range] = (int) $result['total'];
}

// Fetch gender distribution for Xray patients
$query = $conn->query("SELECT gender, COUNT(DISTINCT complaints.itr_no) as total FROM `itr` INNER JOIN `complaints` ON itr.itr_no = complaints.itr_no WHERE complaints.section = 'Xray' AND YEAR(complaints.date) = '$year' GROUP BY gender") or die(mysqli_error());
$gender_distribution = [];
while ($result = $query->fetch_array()) {
	$gender_distribution[$result['gender']] = (int) $result['total'];
}

// Generate recommendations based on chart data
$recommendations = [];

// Recommendation for monthly patient population
if (array_sum($patient_counts) === 0) {
	$recommendations[] = "No patient records for Xray this year. Consider outreach programs to raise awareness.";
} else {
	$max_month = array_keys($patient_counts, max($patient_counts))[0] + 1;
	$recommendations[] = "Highest patient count observed in " . date("F", mktime(0, 0, 0, $max_month, 10)) . ". Consider optimizing resources during this period.";
}

// Recommendation for age distribution
$largest_age_group = array_keys($age_distribution, max($age_distribution))[0];
$recommendations[] = "Majority of patients belong to the age group $largest_age_group. Tailor services for this demographic.";

// Recommendation for gender distribution
if (count($gender_distribution) === 2) {
	if ($gender_distribution['Male'] > $gender_distribution['Female']) {
		$recommendations[] = "Male patients form the majority. Ensure male-specific health resources are available.";
	} else {
		$recommendations[] = "Female patients form the majority. Consider programs targeting female health.";
	}
} else {
	$recommendations[] = "Gender distribution is skewed. Investigate reasons for low representation of one gender.";
}

$conn->close();
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
	<?php require 'script.php' ?>
	<script src="../js/jquery.canvasjs.min.js"></script>
	<script type="text/javascript">
		window.onload = function() {
			$(".chartContainer").CanvasJSChart({
				animationEnabled: true,
				theme: "light2",
				title: {
					text: "Monthly Xray Patient Population <?php echo $year ?>"
				},
				axisY: {
					title: "Total Population",
					includeZero: false
				},
				data: [{
					type: "column",
					toolTipContent: "{label}: {y}",
					dataPoints: [{
							label: "January",
							y: <?php echo $patient_counts[0] ?>
						},
						{
							label: "February",
							y: <?php echo $patient_counts[1] ?>
						},
						{
							label: "March",
							y: <?php echo $patient_counts[2] ?>
						},
						{
							label: "April",
							y: <?php echo $patient_counts[3] ?>
						},
						{
							label: "May",
							y: <?php echo $patient_counts[4] ?>
						},
						{
							label: "June",
							y: <?php echo $patient_counts[5] ?>
						},
						{
							label: "July",
							y: <?php echo $patient_counts[6] ?>
						},
						{
							label: "August",
							y: <?php echo $patient_counts[7] ?>
						},
						{
							label: "September",
							y: <?php echo $patient_counts[8] ?>
						},
						{
							label: "October",
							y: <?php echo $patient_counts[9] ?>
						},
						{
							label: "November",
							y: <?php echo $patient_counts[10] ?>
						},
						{
							label: "December",
							y: <?php echo $patient_counts[11] ?>
						}
					]
				}]
			});
			// Age distribution chart
			$(".ageDistributionContainer").CanvasJSChart({
				animationEnabled: true,
				theme: "light2",
				title: {
					text: "Age Distribution of Xray Patients"
				},
				axisY: {
					title: "Number of Patients",
					includeZero: true
				},
				data: [{
					type: "bar",
					toolTipContent: "{label}: {y}",
					dataPoints: [
						<?php foreach ($age_distribution as $range => $total): ?> {
								label: "<?php echo $range ?>",
								y: <?php echo $total ?>
							},
						<?php endforeach; ?>
					]
				}]
			});

			// Gender distribution chart
			$(".genderDistributionContainer").CanvasJSChart({
				animationEnabled: true,
				theme: "light2",
				title: {
					text: "Gender Distribution of Xray Patients"
				},
				data: [{
					type: "pie",
					showInLegend: true,
					toolTipContent: "{label}: {y}",
					dataPoints: [
						<?php foreach ($gender_distribution as $gender => $total): ?> {
								label: "<?php echo ucfirst($gender) ?>",
								y: <?php echo $total ?>
							},
						<?php endforeach; ?>
					]
				}]
			});
			//Recommendations
			const recommendations = <?php echo json_encode($recommendations); ?>;
			let recommendationsHtml = "<h4>Recommendations</h4><ul>";
			recommendations.forEach(rec => {
				recommendationsHtml += `<li>${rec}</li>`;
			});
			recommendationsHtml += "</ul>";

			const recommendationsContainer = document.createElement("div");
			recommendationsContainer.classList.add("well");
			recommendationsContainer.innerHTML = recommendationsHtml;
			document.getElementById("content").appendChild(recommendationsContainer);
		}

		function updateYear() {
			const year = document.getElementById("yearSelect").value;
			window.location.href = "?year=" + year;
		}
	</script>
	<style>
		.chartContainer,
		.ageDistributionContainer,
		.genderDistributionContainer {
			height: 400px;
			width: 100%;
		}

		@media (max-width: 768px) {

			.chartContainer,
			.ageDistributionContainer,
			.genderDistributionContainer {
				height: 250px;
			}
		}
	</style>
</head>

<body>
	<div class="navbar navbar-default navbar-fixed-top">
		<img src="../images/loogo.png" style="float:left;" height="55px" /><label class="navbar-brand">San Luis Health Center Patient Record Management System</label>
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
		<div class="container" style="padding-top: 80px;">
			<div id="yearFilter" style="margin-bottom: 20px;">
				<label for="yearSelect">Select Year:</label>
				<select id="yearSelect" class="form-control" style="width: 200px; display: inline-block;" onchange="updateYear()">
					<?php
					$currentYear = date("Y");
					for ($i = $currentYear; $i >= $currentYear - 10; $i--) {
						$selected = $i == $year ? 'selected' : '';
						echo "<option value='$i' $selected>$i</option>";
					}
					?>
				</select>
			</div>
			<br />
			<div id="ta" class="well">
				<div class="chartContainer"></div>
				<div class="ageDistributionContainer" style="margin-top: 20px;"></div>
				<div class="genderDistributionContainer" style="margin-top: 20px;"></div>
			</div>
			<br />
			<button id="s_target" class="btn btn-success"><span class="glyphicon glyphicon-eye-open"></span> Show Record</button>
			<button id="h_target" style="display:none;" class="btn btn-danger"><span class="glyphicon glyphicon-eye-close"></span> Hide Record</button>
			<br />
			<br />
			<div id="target">
				<div class="alert alert-info">Xray Record</div>
				<table id="table" cellspacing="0" class="display">
					<thead>
						<tr>
							<th>ITR No</th>
							<th>Name</th>
							<th>Age</th>
							<th>Gender</th>
							<th>Address</th>
							<th>Complaint Date</th>
							<th>
								<center>Action</center>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						// Fetch patients who have xray complaints
						$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());
						$query = $conn->query("SELECT itr.itr_no, itr.firstname, itr.lastname, itr.age, itr.gender, itr.address, complaints.date AS complaint_date FROM `complaints` INNER JOIN `itr` ON complaints.itr_no = itr.itr_no WHERE complaints.section = 'Xray' GROUP BY itr.itr_no ORDER BY complaints.date DESC") or die(mysqli_error());
						while ($fetch = $query->fetch_array()) {
							$formatted_date = date("F j, Y", strtotime($fetch['complaint_date']));
						?>
							<tr>
								<td><?php echo $fetch['itr_no'] ?></td>
								<td><?php echo $fetch['firstname'] . " " . $fetch['lastname'] ?></td>
								<td><?php echo $formatted_date ?></td>
								<td><?php echo $fetch['age'] ?></td>
								<td><?php echo $fetch['gender'] ?></td>
								<td><?php echo $fetch['address'] ?></td>
								<td>
									<center>
										<a class="btn btn-primary btn-sm" href="patient.php?itr_no=<?php echo $fetch['itr_no'] ?>"><span class="glyphicon glyphicon-search"></span> View Records</a>
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

		<script type="text/javascript">
			$(document).ready(function() {
				$("#target").hide();
				$("#s_target").click(function() {
					$("#target").fadeIn();
					$("#s_target").hide();
					$("#ta").slideUp();
					$("#h_target").show();
				});
				$("#h_target").click(function() {
					$("#target").fadeOut();
					$("#s_target").show();
					$("#h_target").hide();
					$("#ta").slideDown();
				});
			});
		</script>
</body>

</html>