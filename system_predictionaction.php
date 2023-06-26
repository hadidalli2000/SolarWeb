<?php
session_start();
include('connect.php');
include('validation.php'); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_login();
    $totalWatts = $_POST['totalWatts'];
    $_SESSION['totalWatts'] = $totalWatts;
    $email = $_SESSION['email'];


    // Prepare the query with the totalWatts value
    $query = "SELECT * FROM Systems WHERE $totalWatts BETWEEN Min AND Max";

    // Execute the query
    $result = sqlsrv_query($conn, $query);

    // Check if the query was executed successfully
    if ($result !== false) {
        // Check if there are any matching rows
        if (sqlsrv_has_rows($result)) {
            // Fetch the row data
            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                // Output the row data
                $_SESSION['SystemID']=$row['SystemID'];
                  
                $_SESSION['invertername']=$row['Inverter_Name'];
             
                $_SESSION['batteryname']=$row['Battery_Name'];
            
                $_SESSION['battamp']=$row['Batt_Amp'];
              
                $_SESSION['battnum']=$row['Batt_Num'];
              
                $_SESSION['panelName']=$row['Panel_Name'];
                 
                $_SESSION['panelnb']=$row['Panel_nb'];
             
                $_SESSION['panelwatt']=$row['Panel_Watt'];
              
                $_SESSION['totalprice']=$row['Total_Price'];

               
              
              
                header('Location: system_result.php');
            }
        } else {
            echo "No matching rows found.";
        }

        // Free the result set
        sqlsrv_free_stmt($result);
    } else {
        echo "Query execution failed.";
    }
}
?>
<?php
session_start();

if (isset($_POST['wifi_price'])) {
    $wifiPrice = $_POST['wifi_price'];
    $_SESSION['wifi_price'] = $wifiPrice;

}
?>