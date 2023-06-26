<?php
$serverName = "DESKTOP-EU6LKO0";
$connectionOptions = array(
    "Database" => "SolarSystem",
    "Uid" => "da",
    "PWD" => "da"
);

$conn= sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

if (!is_resource($conn)) {
    die('Invalid database connection');
}

?>
