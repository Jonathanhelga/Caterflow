<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../Database/db_connect.php'; 

$username = isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : "Guest User";
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Kitchen Ndeso - Action Options</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
        <!-- Relative path for the CSS from the current root context where this will be included -->
        <link rel="stylesheet" href="ActionOptions/actionOptionsStyles.css?v=<?php echo filemtime(__DIR__ . '/actionOptionsStyles.css'); ?>">
        <script>
            function addCustomer(){ location.replace("customer.php"); } //also here EC2 ../customer.php while XAMPP customer.php
            function addProduct(){ location.replace("product.php"); } //also here EC2 ../product.php while XAMPP product.php
            function addSupplier(){ location.replace("supplier.php"); }
            function newOrder(){location.replace("order.php"); }
            function searchData(){location.replace("search.php");}
            // function calculateWorkHour(){location.replace("HelperWages/helperWages.php"); }
            function logOutPage(){ location.replace("./logOut.php"); }//also here EC2 ../logOut.php while XAMPP ./logOut.php
        </script>
    </head>
    <body>
        <section class="page-container">
            <div class="action-card">
                <header class="action-header">
                    <h1>Kitchen Ndeso</h1>
                    <p>Hello <?php echo $username; ?></p>
                </header>
                <div class="search-grid">
                    <button onclick="searchData()" class="btn-action">Search Data</button>
                </div>
                <div class="action-grid">
                    <button onclick="addCustomer()" class="btn-action">Add Customer</button>
                    <button onclick="addProduct()" class="btn-action">Add Product</button>
                    <button onclick="addSupplier()" class="btn-action">Add Supplier</button>
                    <button onclick="newOrder()" class="btn-action">New Order</button>
                    <!-- <button onclick="calculateWorkHour()" class="btn-action">Calculate Wages</button> -->
                </div>
                    
                <footer class="action-footer">
                    <small>&copy; Kitchen Ndeso</small>
                    <nav class="footer-nav">
                        <a href="mailto:your-email@gmail.com">Gmail</a>
                        <a href="https://wa.me/your-phone-number">WhatsApp</a>
                        <a href="line://ti/p/@your-line-id">Line</a>   
                    </nav>
                    <div class="logout-container">
                        <button onclick="logOutPage()" class="btn-logout">Log Out</button>
                    </div>
                </footer>
            </div>
        </section>
    </body>
</html>
