<!DOCTYPE html>
<?php
require_once 'logincheck.php';
$conn = new mysqli("localhost", "root", "", "hcpms") or die(mysqli_error());

// Sections to query
$sections = [
	"Fecalysis",
	"Maternity",
	"Hematology",
	"Dental",
	"Xray",
	"Rehabilitation",
	"Sputum",
	"Urinalysis",
];

// Barangay coordinates
$barangay_coordinates = [
	"Abiacao" => [13.84502502292867, 120.9354198572311],
	"Bagong Tubig" => [13.836364782532788, 120.92699254562548],
	"Balagtasin" => [13.829834222134961, 120.95191624428206],
	"Balite" => [13.821949021360238, 120.91172938162637],
	"Banoyo" => [13.821757216152488, 120.92170889460189],
	"Boboy" => [13.813370024348593, 120.94472870994576],
	"Bonliw" => [13.835919239478146, 120.94027183957543],
	"Calumpang West" => [13.838709552104003, 120.97475765736041],
	"Calumpang East" => [13.844741044877994, 120.97846959613605],
	"Dulangan" => [13.849093326483919, 120.92067851003206],
	"Durungao" => [13.830901484039284, 120.96959842925914],
	"Locloc" => [13.81677712562734, 120.91212392930157],
	"Luya" => [13.820347685733536, 120.93300768859926],
	"Mahabang Parang" => [13.811304920450421, 120.92183473332943],
	"Manggahan" => [13.84778023330916, 120.9666457694321],
	"Muzon" => [13.848706710904048, 120.99136841152834],
	"San Antonio" => [13.855982352713164, 120.97433815003451],
	"San Isidro" => [13.827353988467868, 120.94600063233186],
	"San Jose" => [13.855394168676758, 120.94945009064777],
	"San Martin" => [13.842739970872543, 120.95541136340725],
	"Santa Monica" => [13.83097554736077, 120.95684731602992],
	"Taliba" => [13.859344955109888, 120.94045115191763],
	"Talon" => [13.850392412738803, 120.93891136097426],
	"Tejero" => [13.853456191864613, 120.92602411737386],
	"Tungal" => [13.851621218749047, 120.95760150448365],
	"Población" => [13.857491220097696, 120.91998419296567],
];

// Patient data organized by section and barangay
$patient_locations = [];

foreach ($sections as $section) {
	$query = $conn->query("SELECT DISTINCT itr.address, itr.firstname, itr.lastname, complaints.date 
                           FROM complaints 
                           INNER JOIN itr ON complaints.itr_no = itr.itr_no 
                           WHERE complaints.section = '$section'") or die(mysqli_error());

	while ($row = $query->fetch_assoc()) {
		foreach ($barangay_coordinates as $barangay => $coords) {
			if (stripos($row['address'], $barangay) !== false) {
				$patient_locations[$section][$barangay][] = [
					'name' => $row['firstname'] . ' ' . $row['lastname'],
					'date' => $row['date'],
					'lat' => $coords[0],
					'lng' => $coords[1],
				];
				break;
			}
		}
	}
}
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
	<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
	<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
	<style>
		#map {
			height: 600px;
			width: 100%;
		}

		#filter-container {
			margin: 20px;
		}

		#gis-description {
			margin: 20px;
			background-color: #f5f5f5;
			padding: 15px;
			border-radius: 5px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		}
	</style>
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
		<div id="gis-description">
			<h3>What is GIS?</h3>
			<p>
				GIS (Geographic Information System) enables spatial mapping and visualization of patient records
				by barangay, helping in efficient healthcare resource allocation and analysis.
			</p>
		</div>
		<div id="filter-container">
			<label for="sectionFilter">Filter by Section:</label>
			<select id="sectionFilter" class="form-control" style="width: 300px;">
				<?php foreach ($sections as $section): ?>
					<option value="<?php echo $section; ?>"><?php echo $section; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div id="map"></div>
	</div>

	<script>
		// Pass PHP data to JavaScript
		const patientLocations = <?php echo json_encode($patient_locations); ?>;

		// Initialize the map
		const map = L.map('map').setView([13.840616434197873, 120.95314654369425], 12.8);

		// Add OpenStreetMap tiles
		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution: '© OpenStreetMap contributors'
		}).addTo(map);

		let markers = []; // Array to store markers

		// Function to display markers for a selected section
		function displayMarkers(section) {
			// Clear existing markers
			markers.forEach(marker => map.removeLayer(marker));
			markers = [];

			// Add new markers
			if (section === 'all') {
				for (const [sectionName, barangays] of Object.entries(patientLocations)) {
					addSectionMarkers(sectionName, barangays);
				}
			} else if (patientLocations[section]) {
				addSectionMarkers(section, patientLocations[section]);
			}
		}

		// Helper function to add markers for a specific section
		function addSectionMarkers(section, barangays) {
			for (const [barangay, patients] of Object.entries(barangays)) {
				const {
					lat,
					lng
				} = patients[0];
				let patientList = `<b>Section:</b> ${section}<br><b>Barangay:</b> ${barangay}<br><b>Patients:</b><ul>`;
				patients.forEach(patient => {
					patientList += `<li>${patient.name} (${patient.date})</li>`;
				});
				patientList += `</ul>`;

				const marker = L.marker([lat, lng]).addTo(map);
				marker.bindPopup(patientList).on('mouseover', function() {
					this.openPopup();
				});
				markers.push(marker);
			}
		}

		// Event listener for the filter
		document.getElementById('sectionFilter').addEventListener('change', function() {
			displayMarkers(this.value);
		});

		// Initial display of all markers
		displayMarkers('all');
	</script>
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<!-- Bootstrap JavaScript -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

</body>

</html>