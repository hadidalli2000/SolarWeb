<!-- advanced_options.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Advanced Options</title>
    <link rel="stylesheet" href="advanced_options.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include('sideBar.html');include('validation.php');  ?>

    <?php
        session_start(); // Start the session
        check_login();
        // Access the saved email from the session
        $email = $_SESSION['email'];

        // Include the database connection file
        include('connect.php');

        // Retrieve the user information from the database
        $query = "SELECT * FROM Clients WHERE email = ?";
        $params = array($email);
        $stmt = sqlsrv_query($conn, $query, $params);

        // Check if the query execution is successful
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Fetch the user data
        $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    ?>

    <!-- Rest of the page content -->
    <div class="container">
        <h2 class="title-question">Advanced Options</h2>

        <form id="updateForm" action="advancedaction.php" method="POST">
            <label for="Fname">First Name:</label>
            <input type="text" id="Fname" name="Fname" placeholder="Enter your First name" required value="<?php echo $user['FirstName']; ?>">
            
            <label for="Lname">Last Name:</label>
            <input type="text" id="Lname" name="Lname" placeholder="Enter your Last name" required value="<?php echo $user['LastName']; ?>">

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required value="<?php echo $user['Phone']; ?>">

            <label for="Address">Address:</label>
            <input type="text" id="Address" name="Address" placeholder="Enter your Address" required value="<?php echo $user['Address']; ?>">

			<label for="password">Change Password or Current Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>

            <button class="button submit" type="submit">Save Changes</button>
        </form>
    </div>

    <script>
        // JavaScript code to handle form submission
        const form = document.getElementById('updateForm');

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            // Get form values
            const firstName = document.getElementById('Fname').value;
            const lastName = document.getElementById('Lname').value;
            const password = document.getElementById('password').value;
            const phone = document.getElementById('phone').value;
            const address = document.getElementById('Address').value;
     
            // Set form values to lowercase
            document.getElementById('Fname').value = firstName;
            document.getElementById('Lname').value = lastName;
            if (password.length < 8) {
				alert("Password should be at least 8 characters long.");
				return false;
			}
            // Submit the form
            form.submit();
            
			
        });
    </script>
</body>
</html>
