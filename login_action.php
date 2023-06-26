<?php
session_start(); // Start the session

include('connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Construct the SQL query
    $sql = "SELECT * FROM Clients WHERE Email = ? AND Password = ?";
    $params = array($email, $password);

    // Execute the SQL query
    $stmt = sqlsrv_query($conn, $sql, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Check if the query returned a row
    if (sqlsrv_has_rows($stmt)) {
        // Login successful
        // Save the email in a session variable
        $_SESSION['email'] = $email;
        header('Location: home.php');
        exit; // Make sure to exit after redirecting
    } else {
        // Invalid email or password
        echo "<script>alert('Invalid email or password. Please try again'); window.location.href = 'login.php';</script>";
    }
}
?>