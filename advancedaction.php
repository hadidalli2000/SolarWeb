<?php
  include('connect.php');
  include('validation.php'); 
  session_start();
  check_login();
  $email = $_SESSION['email'];

  // Get the updated user information from the form data
  $firstName = $_POST['Fname'];
  $lastName = $_POST['Lname'];
  $password = $_POST['password'];
  $phone = $_POST['phone'];
  $address = $_POST['Address'];

  // Update the user's information in the "Clients" table
  $query = "UPDATE Clients SET FirstName = ?, LastName = ?, Password = ?, Phone = ?, Address = ? WHERE email = ?";
  $params = array($firstName, $lastName, $password, $phone, $address, $email);
  $stmt = sqlsrv_query($conn, $query, $params);

  // Check if the query execution is successful
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }
  $query = "UPDATE Emails SET Password = ? WHERE Email = ?";
  $params = array($password, $email);
  $stmt = sqlsrv_query($conn, $query, $params);

  // Check if the query execution is successful
  if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
  }

  // Return a success response
  http_response_code(200);
  header('Location:home.php');
?>
