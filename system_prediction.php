<!-- system_prediction.php -->
<!DOCTYPE html>
<html>
<head>
	<title>System Prediction</title>
	<link rel="stylesheet" type="text/css" href="system_prediction.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
	<!-- Sidebar -->
	<?php include('sideBar.html'); ?>

	<!-- Rest of the page content -->
	<div class="content">
		<!-- Explanation of the page -->
		<h2>System Prediction</h2>
		<p>
			This page allows you to answer a few questions so that our system can predict the optimal solar plan for you.
		</p>
		<form method="POST" action="system_predictionaction.php">
		<!-- Question 1: Items to turn on using solar energy -->
		<h3 class="title-question">Select items you want to turn on using solar energy:</h3>
		<div class="question card-grid">
		<!-- TV/Lamps/Fans -->
		<div class="box-card active appliance" onclick="toggleSelection(this)" data-appliance-index="0">
			<h4>TV/Lamps/Fans</h4>
			<img src="./images/tv.png" alt="TV/Lamps/Fans">
			<p class="watts">Watts</p>
		</div>

		<!-- Fridge -->
		<div class="box-card active appliance" onclick="toggleSelection(this)" data-appliance-index="1">
			<h4>Fridge</h4>
			<img src="./images/fr.png" alt="Fridge">
			<p class="watts">Watts</p>
		</div>

		<!-- Washing Machine -->
		<div class="box-card active appliance" onclick="toggleSelection(this)" data-appliance-index="2">
			<h4>Washing Machine</h4>
			<img src="./images/washing machine.png" alt="Washing Machine">
			<p class="watts">Watts</p>
		</div>

		<!-- Air Conditioner -->
		<div class="box-card active appliance" onclick="toggleSelection(this)" data-appliance-index="3">
			<h4>Air Conditioner</h4>
			<img src="./images/ac.png" alt="Air Conditioner">
			<p class="watts">Watts</p>
		</div>

		<!-- Water Heater -->
		<div class="box-card active appliance" onclick="toggleSelection(this)" data-appliance-index="4">
			<h4>Water Heater</h4>
			<img src="./images/water-heater.png" alt="Water Heater">
			<p class="watts">Watts</p>
		</div>
		</div>

		<!-- Question 2: Position for solar panel installation -->
		<h3 class="title-question">Select the position where you want to install the solar panels:</h3>
		<div class="question card-grid">
			<!-- Roof -->
			<div class="box-card active position" onclick="selectPosition(this)">
				<h4>Roof</h4>
				<img src="./images/solar_roof.png" alt="Roof">
			</div>

			<!-- High -->
			<div class="box-card active position" onclick="selectPosition(this)">
				<h4>High</h4>
				<img src="./images/solar_high.png" alt="High">
			</div>

			<!-- Low -->
			<div class="box-card active position" onclick="selectPosition(this)">
				<h4>Low</h4>
				<img src="./images/solar_low.png" alt="Low">
			</div>
		</div>

		<!-- Question 3: Inverter with WIFI -->
		<h3 class="title-question">Select if you want the inverter with WIFI:</h3>
		<div class="question card-grid">
			<!-- With WIFI -->
			<div class="box-card active wifi" onclick=" selectWifi(this)">
				<h4 >With WIFI</h4>
				<h4 style="margin:0">It Costs 50$</h4>
				<img src="./images/yeswifi.png" alt="Inverter with WIFI">
				<p style="margin-left: 80px;display:none;"class="Wprice">0</p>

			</div>
		</div>

		<!-- Submit button -->
		<button class="button submit" onclick="validateForm(event);saveValueToSession()">Submit</button>
		<p id="totalWatts" style="display:none"></p>
	</div>

	<script>
		// Validation for Submit
		function validateForm(event) {
			event.preventDefault(); // Prevent the form from submitting

			var selectedAppliances = document.querySelectorAll(".appliance.selected");
			var selectedPosition = document.querySelector(".position.selected");
			displayTotalWatts();
				 // Calculate and display the total watts
				var totalWatts = calculateTotalWatts();

			if (selectedAppliances.length === 0 || !selectedPosition || !Number.isInteger(totalWatts)) {
				alert("Please select appliances and a position.");
			} else {
				document.getElementById("totalWatts").textContent = "Total Watts: " + totalWatts;

				var hiddenInput = document.createElement("input");
				hiddenInput.type = "hidden";
				hiddenInput.name = "totalWatts";
				hiddenInput.value = totalWatts;
				document.querySelector("form").appendChild(hiddenInput);				

				document.querySelector("form").submit(); // Submit the form
			}
		}

		//For appliances
		var selectedCards = [];

		// Define options for each appliance
		var applianceOptions = [
			["Select an option", "100", "200", "300"], // TV/Lamps/Fans
			["Select an option", "100", "200", "300"], // Fridge
			["Select an option", "200", "300", "400"], // Washing Machine
			["Select an option", "1000", "1600", "2000"], // Air Conditioner
			["Select an option", "1000", "1400", "2000"], // Water Heater
		];

		function toggleSelection(card) {
			if (selectedCards.includes(card)) {
				// Card is already selected, remove it from the selectedCards array
				card.classList.remove("selected");
				card.classList.add("default");
				selectedCards = selectedCards.filter(function (selectedCard) {
				return selectedCard !== card;
				});
				resetWattsValue(card);
			} else {
				// Card is not selected, add it to the selectedCards array
				card.classList.add("selected");
				card.classList.remove("default");
				selectedCards.push(card);
				createSelectElement(card);
			}
		}

		function resetWattsValue(card) {
			var wattsElement = card.querySelector(".watts");
			wattsElement.textContent = wattsElement.dataset.defaultWatts;
		}

		function createSelectElement(card) {
			// Get the appliance index from the data attribute
			var applianceIndex = parseInt(card.getAttribute("data-appliance-index"));

			// Create the select element
			var select = document.createElement("select");

			// Add options to the select element based on the appliance index
			var options = applianceOptions[applianceIndex];
			for (var i = 0; i < options.length; i++) {
				var option = document.createElement("option");
				option.text = options[i];
				select.appendChild(option);
			}

			// Set the position of the select element
			var cardRect = card.getBoundingClientRect();
			select.classList.add("select-container");

			// Add event listener to handle option selection
			select.addEventListener("change", function () {
				event.stopPropagation(); // Stop event propagation
				// Do something with the selected option
				var selectedOption = select.options[select.selectedIndex].text;
				var wattsElement = card.querySelector(".watts");
				if (selectedOption !== "Select an option") {
				wattsElement.textContent = selectedOption;
				} else {
				resetWattsValue(card);
				}

				// Remove the select element
				card.removeChild(select);

				// Collect the selected watt value
				var wattValue = parseInt(selectedOption);

				// Calculate the total watts
				var totalWatts = calculateTotalWatts();
				console.log("Total Watts:", totalWatts);

				// Send the watt value to the server
				sendDataToServer(wattValue);
			});

			// Add event listener to prevent event propagation on select container click
			select.addEventListener("click", function (event) {
			event.stopPropagation(); // Stop event propagation
			});
			
			// Append the select element to the card
			card.appendChild(select);
		}

		function calculateTotalWatts() {
		var totalWatts = 0;

		for (var i = 0; i < selectedCards.length; i++) {
			var card = selectedCards[i];
			var wattsElement = card.querySelector(".watts");
			var wattValue = parseInt(wattsElement.textContent);
			totalWatts += wattValue;
		}


		return totalWatts;
		}

		function displayTotalWatts() {
			var totalWatts = calculateTotalWatts();
			var totalWattsElement = document.getElementById("totalWatts");
			totalWattsElement.textContent = "Total Watts: " + totalWatts;

			// Add the total watt value to a hidden input field in the form
			var hiddenInput = document.createElement("input");
			hiddenInput.type = "hidden";
			hiddenInput.name = "totalWatts";
			hiddenInput.value = totalWatts;
			document.querySelector("form").appendChild(hiddenInput);
		}

		// For position
		var selectedCard = null;

		function selectPosition(card) {
		if (selectedCard === card) {
			// Card is already selected, unselect it
			card.classList.remove("selected");
			selectedCard = null;
		} else {
			// Unselect the previously selected card (if any)
			if (selectedCard) {
			selectedCard.classList.remove("selected");
			}

			// Select the current card
			card.classList.add("selected");
			selectedCard = card;

			// Get the selected position from the clicked card
			var selectedPosition = card.querySelector("h4").textContent;

			// Store the selected position in a cookie
			document.cookie = "selectedPosition=" + selectedPosition;
		}
		}

		// For wifi
		// Global variable to store the selected state
		var isCardSelected = false;
		function selectWifi(card) {
			if (card.classList.contains("selected")) {
				// Card is already selected, unselect it
				card.classList.remove("selected");
				isCardSelected = false;	
				var element = document.getElementsByClassName("Wprice")[0];
                wifip=element.textContent = "0";
				

			} else {
				// Select the card
				card.classList.add("selected");
				isCardSelected = true;
				var element = document.getElementsByClassName("Wprice")[0];
                wifip=element.textContent = "50";
			}
		}
	
    

    function saveValueToSession() {
        var elements = document.getElementsByClassName('Wprice');
        var wifiPrice = elements[0].innerText;
        console.log(wifiPrice); // Output the value in the console

        // Send the value to the server using an AJAX request
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "system_predictionaction.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log("Value saved to session successfully!");
                } else {
                    console.error("Error saving value to session:", xhr.status);
                }
            }
        };
        xhr.send("wifi_price=" + encodeURIComponent(wifiPrice));
        
        // Rest of your code...
    }
	    
		
	
	</script>

	<style>
		.select-container {
			position: absolute;
			padding: 10px;
			/* width: 150px; */
			font-size: 14px;
			z-index: 1;
		}
		.selected {
			background-color: rgb(120, 186, 230);
		}

		.default {
			background-color: white;
		}
	</style>
   
	</form>
	
</body>
</html>