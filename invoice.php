<!-- invoice.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <link rel="stylesheet" href="invoice.css">
</head>
<body>
    <!-- Sidebar -->
    <?php include('sideBar.html');include('validation.php'); 
    
    ?>

    <?php  
        session_start();
        check_login();
        $invertername = $_SESSION['invertername'];
        $batteryname = $_SESSION['batteryname'];
        $battamp = $_SESSION['battamp'];
        $battnum = $_SESSION['battnum'];
        $panelname = $_SESSION['panelName'];
        $panelnb = $_SESSION['panelnb'];
        $panelwatt = $_SESSION['panelwatt'];
        $totalprice = $_SESSION['totalprice'];
        $email = $_SESSION['email'];
        $newProjectId = $_SESSION['newProjectId'];
        $wifiprice=$_SESSION['wifi_price'];
                    $wifiprice=intval($wifiprice);
					$totalprice=intval($totalprice);
					$totalprice=$totalprice+$wifiprice;


        // Include the database connection file
        include('connect.php');
		
        // Retrieve customer information from the database
        $query = "SELECT FirstName, LastName, Phone, Address FROM Clients WHERE email = ?";
        $params = array($email);
        $stmt = sqlsrv_query($conn, $query, $params);

        // Check if the query execution is successful
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Fetch the customer data
        $customer = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $firstName = $customer['FirstName'];
        $lastName = $customer['LastName'];
        $phoneNumber = $customer['Phone'];
        $address = $customer['Address'];

        // Retrieve item prices from the database
        $query = "SELECT Price FROM items WHERE Item_Name IN (?, ?, ?)";
        $params = array($invertername, $batteryname.' '.$battamp.'AH', $panelname.' '.$panelwatt.'W');
        $stmt = sqlsrv_query($conn, $query, $params);

        // Check if the query execution is successful
        if ($stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        // Fetch the item prices
        $itemPrices = array();
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $itemPrices[] = $row['Price'];
        }

        // Assign the prices to variables
        $inverterPrice = $itemPrices[2];
        $batteryPrice = $itemPrices[0];
		$newbatteryPrice=$batteryPrice*$battnum;
        $panelPrice = $itemPrices[1];
		$newpanelPrice=$panelPrice*$panelnb;
		$total= $inverterPrice + $newbatteryPrice + $newpanelPrice;
		$installation=$totalprice-$total;
	
    ?>

    <!-- Rest of the page content -->
    <div class="container" id="print">
        <!-- Companies information -->
        <div class="invoice-header">
            <h1>Dalli Solar</h1>
            <p>Address: Lebanon, Khaldah</p>
            <p>Mobile: 76734623</p>
        </div>

        <!-- Customer information and offer number -->
		<div class="invoice-info">
            <div>
                <h2>To:</h2>
                <p>Name: <?php echo $firstName . ' ' . $lastName; ?></p>
                <p>Phone: <?php echo $phoneNumber; ?></p>
                <p>Address: <?php echo $address; ?></p>
            </div>
            <div>
                <p style="font-weight: 700;">Offer Number: #<?php echo $newProjectId; ?></p>
            </div>
        </div>

        <!-- Invoice table -->
        <div class="table">
            <div class="table-header">
                <div class="header__item"><a id="item">Item</a></div>
                <div class="header__item"><a id="price">Price ($)</a></div>
            </div>
            <div class="table-content">
                <div class="table-row">
                    <div class="table-data"><?php echo $invertername; ?></div>
                    <div class="table-data"><?php echo $inverterPrice.' $'; ?></div>
                </div>
                <div class="table-row">
                    <div class="table-data"><?php echo $battnum.' '.$batteryname.' '.$battamp.'AH'; ?></div>
                    <div class="table-data"><?php echo $newbatteryPrice.' $'; ?></div>
                </div>
                <div class="table-row">
                    <div class="table-data"><?php echo $panelnb.' '.$panelname.' '.$panelwatt.'W'; ?></div>
                    <div class="table-data"><?php echo $newpanelPrice.' $'; ?></div>
                </div>
                <div class="table-row">
                    <div class="table-data">Accessories (Including Installation, Iron Base, Cables,....)</div>
                    <div class="table-data"><?php echo $installation.' $'; ?></div>
                </div>
                <!-- Row for total price -->    
            </div>
            
        </div>
		
        <div class="table-data" style="padding:20px;padding-left:300px;font-weight: 700;">Total: <?php echo number_format($totalprice,2).' $'; ?></div>
        <!-- Contact information -->
        <div class="invoice-footer">
            <p>If you have any questions concerning the invoice, use the following contact information:</p>
            <p>Mohamad Dalli: 03619310</p>
            <p>Hadi Dalli: 76734623</p>
        </div>
    </div>

    <!-- Print and Send buttons -->
    <div class="buttons">
        <button class="button print" onclick="printDiv('print')">Print Invoice</button>
        <script>
            function printDiv(print) {
                var div = document.getElementById(print);
                var html = div.innerHTML;
                window.print(html);
            }
        </script>
    </div>
</body>
</html>
