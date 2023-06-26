<!-- system_result.php -->
<!DOCTYPE html>
<html>
	<head>
		<title>System Result</title>
		<link rel="stylesheet" type="text/css" href="system_prediction.css">
	</head>
	<body>
		<!-- Sidebar -->
		<?php include('sideBar.html'); 
		      include('validation.php'); 
			?>
		<?php 
		
				session_start();
				check_login();
					$SystemID=$_SESSION['SystemID'];
					$invertername=$_SESSION['invertername'];
					$batteryname=$_SESSION['batteryname'];
					$battamp=$_SESSION['battamp'];
					$battnum=$_SESSION['battnum'];
					$panelname=$_SESSION['panelName'];
					$panelnb=$_SESSION['panelnb'];
					$panelwatt=$_SESSION['panelwatt'];
					$totalprice=$_SESSION['totalprice'];
					$wifiprice=$_SESSION['wifi_price'];
					$amperepernight=$battnum;
					$amperePerDay = number_format(($panelwatt * 0.8 * $panelnb) / 230, 1);
					$wifiprice=intval($wifiprice);
					$totalprice=intval($totalprice);
					$totalprice=$totalprice+$wifiprice;
					?>
					
		<!-- Rest of the page content -->
		<form method="POST" action="system_resultaction.php">
		<div class="content">
			<!-- Items needed for the best predicted solar plan -->
			<h2 class="title-question">The items you need for the best predicted solar plan:</h2>
			<div class="question card-grid">
				<!-- Inverter -->
				<div class="box-card inactive">
					<h4><?php echo $invertername; ?></h4>
					<img src="./images/growatt.png" alt="Inverter">
				</div>

				<!-- Battery -->
				<div class="box-card inactive">
				<h4>
					<?php echo $batteryname; ?><br>
					<?php echo $battamp. ' AH'; ?><br>
					<?php echo 'Count: '.$battnum; ?>
				</h4>
					<img src="./images/battery300.png" alt="Battery">
				</div>

				<!-- Solar Panel -->
				<div class="box-card inactive">
				<h4>
					<?php echo $panelname; ?><br>
					<?php echo $panelwatt. ' Watt'; ?><br>
					<?php echo 'Count: '.$panelnb; ?>
				</h4>
					<img src="./images/solarpanel.png" alt="Solar Panel">
				</div>

				<!-- Accessories -->
				<div class="box-card inactive">
					<h4>All Accessories</h4>
					<img src="./images/cables.png" alt="Accessories">
				</div>

				<!-- Position -->
				<div class="box-card inactive position">
					<h4>Position</h4>
					<img src="./images/solar_high.png" alt="Position">
				</div>
			</div>

		<!-- User's choice of inverter with or without WIFI -->
	<h2 class="title-question">You chose:</h2>
	<div class="question card-grid">
		<!-- With WIFI -->
		<div class="checkbox-container">
		<input type="checkbox" name="wifi" value="1" <?php if(isset($_SESSION['wifi_price']) && $_SESSION['wifi_price'] == 50) echo 'checked disabled'; ?>>
			<label for="with-wifi">With WIFI</label>
		</div>

		<!-- Without WIFI -->
		<div class="checkbox-container">
		<input type="checkbox" name="without_wifi" value="1" <?php if(isset($_SESSION['wifi_price']) && $_SESSION['wifi_price'] == 0) echo 'checked disabled'; ?>>
			<label for="without-wifi">Without WIFI</label>
		</div>
	</div>

			<!-- Concluding details -->
			<h2 class="title-question">The Concluding details:</h2>
			<div class="question card-grid">
				<!-- Ampere per day -->
				<div class="label-container">
					<label for="ampere-day">Ampere per day:</label>
					<input type="text" id="ampere-day" value="<?php echo $amperePerDay; ?>" disabled>
				</div>

				<!-- Ampere per night -->
				<div class="label-container">
					<label for="ampere-night">Ampere per night:</label>
					<input type="text" id="ampere-night" value="<?php echo $amperepernight; ?>" disabled>
				</div>

				<!-- Total price -->
				<div class="label-container">
					<label for="total-price">Total price ($):</label>
					<input type="text" id="total-price" value=" <?php echo $totalprice; ?>" disabled>
				</div>
			</div>

			<!-- Update and Send buttons -->
			<div class="buttons">
				<button class="button send" >Send Order</button>
				<button class="button update" formaction="system_prediction.php" onclick="window.location.href='system_prediction.php'">Update</button>
			</div>
		</div>
	</form>
	</body>
	<script>
		// Check the wifi box
		function wifiCheckbox() {
			var isSelected = localStorage.getItem("isCardSelected");

			var checkboxTrue = document.getElementById("with-wifi");
			var checkboxFalse = document.getElementById("without-wifi");
			
			if (isSelected === "true") {
				alert("wifi on");
				checkboxTrue.checked = true;
			} else {
				alert("wifi off");
				checkboxFalse.checked = true;
			}

			// Clear the value of isCardSelected from localStorage
  			localStorage.removeItem("isCardSelected");
		}

		// For Position
		document.addEventListener("DOMContentLoaded", function() {
			// Retrieve the selected position from the cookie
			var selectedPosition = getCookie("selectedPosition");

			// Find the position element and update its content
			var positionElement = document.querySelector(".position");
			positionElement.querySelector("h4").textContent = selectedPosition;
			positionElement.querySelector("img").alt = selectedPosition;

			// Delete the cookie after using it
			document.cookie = "selectedPosition=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
		});

		// Function to retrieve a specific cookie value
		function getCookie(name) {
			var cookies = document.cookie.split("; ");
			for (var i = 0; i < cookies.length; i++) {
				var cookie = cookies[i].split("=");
				if (cookie[0] === name) {
					return decodeURIComponent(cookie[1]);
				}
			}
			return "";
		}
	</script>

</html>
