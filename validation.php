<?php
include('connect.php');

function check_login()
{
 if(empty($_SESSION['email'])){
    header("location:404.html");
    die;
 }

}


?>