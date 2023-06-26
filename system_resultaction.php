<?php
session_start();
include('connect.php');
include('validation.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    // Get the session email
    $email = $_SESSION['email'];

    // Get the system date
    $installationDate = date('Y-m-d');

    // Get the total price 
    $totalprice=$_SESSION['totalprice'];
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
    $_SESSION['newProjectId']=$newProjectId;
    // Insert the new project into the database
    $insertQuery = "INSERT INTO Project (ProjectID, ClientID, Email, InstallationDate, Cost, Paid, AdminID, WStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = array($newProjectId, $clientId, $email, $installationDate, intval($totalprice), "0", "1", "Pending");
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



        $stmt = sqlsrv_prepare($conn, $insertProjectDetailsQuery, array($newProjectId, "1", "1",$SystemID));
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        if (!sqlsrv_execute($stmt)) {
            die(print_r(sqlsrv_errors(), true));
        }
    

    // Insert invoice details into the database
    $insertInvoiceQuery = "INSERT INTO Invoice (ProjectID, ItemDescription, Quantity) VALUES (?, ?, ?)";

        $itemDescription =$invertername;
        $quantity = 1;

        $stmt = sqlsrv_prepare($conn, $insertInvoiceQuery, array($newProjectId, $itemDescription, $quantity));
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }
        if (!sqlsrv_execute($stmt)) {
            die(print_r(sqlsrv_errors(), true));
        }
    }
    $insertInvoiceQuery = "INSERT INTO Invoice (ProjectID, ItemDescription, Quantity) VALUES (?, ?, ?)";
    
    $itemDescription =$batteryname.' '. $battamp.'AH';
    $quantity = $battnum;

    $stmt = sqlsrv_prepare($conn, $insertInvoiceQuery, array($newProjectId, $itemDescription, $quantity));
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }
    if (!sqlsrv_execute($stmt)) {
        die(print_r(sqlsrv_errors(), true));
    }

$insertInvoiceQuery = "INSERT INTO Invoice (ProjectID, ItemDescription, Quantity) VALUES (?, ?, ?)";
    
$itemDescription =$panelname.' '.$panelwatt.'W';
$quantity = $panelnb;

$stmt = sqlsrv_prepare($conn, $insertInvoiceQuery, array($newProjectId, $itemDescription, $quantity));
if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
if (!sqlsrv_execute($stmt)) {
    die(print_r(sqlsrv_errors(), true));
}


    
    // Redirect to the shop page
  
    header('Location: invoice.php');
    $message = "order sent successfully";
    echo "<script>alert('$message');</script>";
    
    exit();

?>
