
<?php
include('connect.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // Retrieve the form data
    $firstName = $_POST["first_name"];
    $lastName = $_POST["last_name"];
    $email = $_POST["email"];
    $phoneNumber = $_POST["phone_number"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];
    $gender = $_POST["gender"];
    $dateOfBirth = $_POST["date_of_birth"];
    
    // Perform form validation
    $errors = [];
    // Validate first name
    if (empty($firstName)) {
        $errors[] = "First name is required";
    }

    // Validate last name
    if (empty($lastName)) {
        $errors[] = "Last name is required";
    }

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

     // Validate phone number
     if (empty($phoneNumber)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match("/^[0-9]{8}$/", $phoneNumber)) {
        $errors[] = "Phone number must be 8 digits";
    }

    // Validate address
    if (empty($address)) {
        $errors[] = "Address is required";
    }

    // Validate password
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long";
    }

    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors[] = "Confirm password is required";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match";
    }
     // Validate date of birth
     if (empty($dateOfBirth)) {
        $errors[] = "Date of birth is required";
    } else {
        $currentYear = date("Y");
        $minimumAge = 18;
        $selectedYear = date("Y", strtotime($dateOfBirth));
        $age = $currentYear - $selectedYear;
        if ($age < $minimumAge) {
            $errors[] = "You must be at least 18 years old";
        }
    }

    // Check if there are any validation errors
    if (count($errors) > 0) {
        // Display the errors to the user
        foreach ($errors as $error) {
          //  echo "<p>Error: $error</p>";
            echo "<script>alert('$error'); window.location.href = 'signup.php';</script>";
        }
    } else {
        // Format the date of birth
        $dateOfBirthFormatted = date('Y-m-d', strtotime($dateOfBirth));

        // Prepare the SQL query with placeholders
        $query = "INSERT INTO Clients (FirstName, LastName, Email, Phone, Address,DateOfBirth, Password,Gender) VALUES (?, ?, ?, ?, ?, ?,?,?)";

        // Prepare the statement
        $stmt = sqlsrv_prepare($conn, $query, array($firstName, $lastName, $email, $phoneNumber, $address,$dateOfBirth, $password,$gender));

        // Check if preparing the statement was successful
        if ($stmt === false) {
            // Display the error and stop further execution
            echo"email exists";
        }

        // Execute the statement
        $result = sqlsrv_execute($stmt);

        if ($result === false) {
            // Display the query error and stop further execution
            echo"email exists";
        }
        else{
            $query = "INSERT INTO Emails (Email,Password,Status) VALUES (?, ?, ?)";

            // Prepare the statement
            $stmt = sqlsrv_prepare($conn, $query, array($email, $password,"Client"));
    
            // Check if preparing the statement was successful
            if ($stmt === false) {
                // Display the error and stop further execution
                echo"email exists";
            }
    
            // Execute the statement
            $result = sqlsrv_execute($stmt);
    
            if ($result === false) {
                // Display the query error and stop further execution
                 echo"email exists";
            }
            else{
        // Close the statement
        sqlsrv_free_stmt($stmt);
        header('Location:login.php');
    }

        }
    }
}

?>

