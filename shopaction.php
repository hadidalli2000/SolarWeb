<?php
session_start();
include('connect.php');
include('validation.php'); 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    check_login();
    // Get the session email
    $email = $_SESSION['email'];

    // Get the system date
    $installationDate = date('Y-m-d');

    // Get the total price from the cart
    $total = isset($_POST['total']) ? $_POST['total'] : 0;
    $cartItems = isset($_POST['cartItems']) ? json_decode($_POST['cartItems'], true) : [];
    if($total==0){
        echo "<script>alert('Please Select Items'); window.location.href = 'shop.php';</script>";
    }
    else{
 // Retrieve the client ID based on the email
    $clientIdQuery = "SELECT ClientID FROM Clients WHERE Email = ?";
    $stmt = sqlsrv_prepare($conn, $clientIdQuery, array(&$email));
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    if (!sqlsrv_execute($stmt)) {
        die(print_r(sqlsrv_errors(), true));
    }
    $clientId = null;
    if (sqlsrv_fetch($stmt)) {
        $clientId = sqlsrv_get_field($stmt, 0);
    }
    sqlsrv_free_stmt($stmt);
    // Retrieve the last inserted project ID and increment it
    $lastProjectIdQuery = "SELECT MAX(ProjectID) AS LastProjectId FROM Project";
    $stmt = sqlsrv_prepare($conn, $lastProjectIdQuery);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    if (!sqlsrv_execute($stmt)) {
        die(print_r(sqlsrv_errors(), true));
    }
    $lastProjectId = null;
    if (sqlsrv_fetch($stmt)) {
        $lastProjectId = sqlsrv_get_field($stmt, 0);
    }
    sqlsrv_free_stmt($stmt);

    $newProjectId = $lastProjectId + 1;

    // Insert the new project into the database
    $insertQuery = "INSERT INTO Project (ProjectID, ClientID, Email, InstallationDate, Cost, Paid, AdminID, WStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array($newProjectId, $clientId, $email, $installationDate, $total, "0", "1", "Pending");
    $stmt = sqlsrv_prepare($conn, $insertQuery, $params);
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    if (!sqlsrv_execute($stmt)) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Insert project details into the database
    $insertProjectDetailsQuery = "INSERT INTO ProjectDetails (ProjectID, ItemID, Quantity, SystemID) VALUES (?, ?, ?, ?)";
    $systemId = 0; // Default system ID



        $stmt = sqlsrv_prepare($conn, $insertProjectDetailsQuery, array($newProjectId, "1", "1", "0"));
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        if (!sqlsrv_execute($stmt)) {
            die(print_r(sqlsrv_errors(), true));
        }
    

    // Insert invoice details into the database
    $insertInvoiceQuery = "INSERT INTO Invoice (ProjectID, ItemDescription, Quantity) VALUES (?, ?, ?)";

    foreach ($cartItems as $cartItem) {
        $itemDescription = $cartItem['itemDescription'];
        $quantity = $cartItem['quantity'];

        $stmt = sqlsrv_prepare($conn, $insertInvoiceQuery, array($newProjectId, $itemDescription, $quantity));
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        if (!sqlsrv_execute($stmt)) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    // Clear the cart
    $_SESSION['cartItems'] = array();

    // Redirect to the shop page
  
    echo "<script>alert('Order Created Successfully,Check It in My Orders!'); window.location.href = 'shop.php';</script>";
    //exit();
}}
?>
