<!-- my_orders.php -->
<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link rel="stylesheet" href="invoice.css">
    <style>
        .table-content {
            display: flex;
            flex-direction: column;
        }

        .table-content .table-row {
            display: flex;
            flex-direction: row;
            margin-bottom: 10px;
            text-align:center;
        }

        .table-content .table-row .table-cell {
            flex: 1;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <?php include('sideBar.html'); 
    
    
    
    ?>

    <!-- Rest of the page content -->
    <div class="container">
        <!-- Search bar -->
        <input type="text" class="search-bar" id="searchBar" placeholder="Search by Project ID" style="display:none">

        <!-- Orders table -->
        <div class="table">
            <div class="table-header">
                <div class="header__item"><a id="project-id">Project ID</a></div>
                <div class="header__item"><a id="order-date">Order Date</a></div>
                <div class="header__item"><a id="cost">Cost</a></div>
                <div class="header__item"><a id="paid">Paid</a></div>
                <div class="header__item"><a id="status">Status</a></div>
                <div class="header__item"><a id="action">Action</a></div>
            </div>
            <form id="cancelOrderForm" method="post">
                <div class="table-content" id="ordersTableBody">    
                    <!-- Fill in the table content here -->
                </div>
            </form>
        </div>
    </div>

    <!-- Cancel Order button -->
    <button class="button cancel" id="cancelOrderButton" onclick="cancelOrder()">Cancel Order</button>

    <!-- PHP Code -->
    <?php
    include('connect.php');include('validation.php');
    session_start();
    check_login();
    $email = $_SESSION['email'];
     

    // Function to delete an order by project ID
    function deleteOrder($conn, $projectID) {
        $query = "DELETE FROM Project WHERE ProjectID = ?";
        $params = array($projectID);
        $stmt = sqlsrv_prepare($conn, $query, $params);
        $result = sqlsrv_execute($stmt);

        if ($result === false) {
            die(print_r(sqlsrv_errors(), true));
        }
    }

    // Check if the cancelOrderButton is clicked
    if (isset($_POST['cancelOrderButton'])) {
        $checkboxes = isset($_POST['orderCheckbox']) ? $_POST['orderCheckbox'] : [];
        foreach ($checkboxes as $orderID) {
            deleteOrder($conn, $orderID);
        }
    }

    // Check if the orderID is posted to delete an order
    if (isset($_POST['orderID'])) {
        $orderID = $_POST['orderID'];
        deleteOrder($conn, $orderID);
    }

    // SQL query to retrieve the user's orders based on email
    $query = "SELECT * FROM Project WHERE email = ?";
    $params = array($email);
    $stmt = sqlsrv_query($conn, $query, $params);

    // Check if the query execution is successful
    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Fetch the results and populate the orders array
    $orders = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $order = array(
            'projectID' => $row['ProjectID'],
            'orderDate' => $row['InstallationDate'],
            'cost' => $row['Cost'],
            'paid' => $row['Paid'],
            'status' => $row['WStatus']
        );
        $orders[] = $order;
    }
    ?>

    <!-- JavaScript code -->
    <script>
    // JavaScript functions for order functionality
    const orders = <?php echo json_encode($orders); ?>;

    function populateOrdersTable() {
        const ordersTableBody = document.getElementById('ordersTableBody');
        ordersTableBody.innerHTML = '';

        orders.forEach(order => {
            const { projectID, orderDate, cost, paid, status } = order;
            const row = `
                <div class="table-row">
                    <div class="table-cell">${projectID}</div>
                    <div class="table-cell">${orderDate}</div>
                    <div class="table-cell">$${cost}</div>
                    <div class="table-cell">${paid ? 'Yes' : 'No'}</div>
                    <div class="table-cell">${status}</div>
                    <div class="table-cell"><input type="checkbox" name="orderCheckbox[]" value="${projectID}"></div>
                </div>
            `;
            ordersTableBody.innerHTML += row;
        });
    }

    function cancelOrder() {
        const checkboxes = document.querySelectorAll('#ordersTableBody input[type="checkbox"]:checked');
        const canceledOrderIDs = [];

        checkboxes.forEach(checkbox => {
            const orderID = parseInt(checkbox.value);
            canceledOrderIDs.push(orderID);
            deleteOrder(orderID);
        });

        if (canceledOrderIDs.length > 0) {
            const form = document.getElementById('cancelOrderForm');
            form.submit();
        }
    }

    function deleteOrder(orderID) {
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Order deleted successfully
            }
        };
        xhr.open('POST', '<?php echo $_SERVER["PHP_SELF"]; ?>', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send('orderID=' + orderID);
    }

    // Event listener
    searchBar.addEventListener('input', populateOrdersTable);

    // Initial population of the orders table
    populateOrdersTable();
    </script>
</body>
</html>
