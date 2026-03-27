<?php require_once __DIR__ . '/Search/search_logic.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Searching</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Search/searchStyles.css?v=<?php echo filemtime(__DIR__ . '/Search/searchStyles.css'); ?>">
</head>

<body>
    <section class="main-container">
        <div class="init-card">
            <header class="action-header">
                <h1>Kitchen Ndeso</h1>
                <p>Search & Update Records</p>
            </header>

            <div class="form-group">
                <select class="main-category-select" id="category-selector">
                    <option value="customers">Customers</option>
                    <option value="suppliers">Suppliers</option>
                    <option value="products">Products</option>
                    <option value="orders">Orders</option>
                    <option value="installments">Payment Schedule</option>
                </select>
            </div>

            <!-- <div id="filter-inputs-container" class="input-container">
                <div class="inline-group">
                    <div class="form-group">
                        <label for="search-name">Customer Name :</label>
                        <input type="text" id="search-name" name="search_name" maxlength="100" placeholder="e.g. John Doe">
                    </div>
                    <div class="form-group">
                        <label for="search-code">Customer Code :</label>
                        <input type="text" id="search-code" name="search_code" maxlength="10" placeholder="e.g. CUST-01">
                    </div>
                </div>
            </div> -->

            <div id="results-table-container" class="table-container">
            </div>

            <footer class="back-link">
                <a href="./actionOptions.php">
                    <button type="button">Back to Front Page</button>
                </a>
            </footer>
        </div>
    </section>

    <section class="detail-container" id="detail-side-panel">
        <div class="init-card">
            <header class="action-header">
                <h1 id="detail-title">View Details</h1>
                <p id="detail-subtitle">Modify Information</p>
            </header>
            
            <div id="detail-form-container">
                <!-- Javascript will inject the detailed edit form here when a row is clicked -->
                <p style="text-align:center; color: #6b7280;">Select a record to view details</p>
            </div>

            <footer class="back-link">
                <!-- Button to hide the side panel -->
                <button type="button" id="close-detail-btn">Close Details</button>
            </footer>
        </div>
    </section>

    <template id="customer-filter-template">
        <div class="inline-group">
            <div class="form-group">
                <label>Customer Name :</label>
                <input type="text" id="template-name" name="name" maxlength="100">
            </div>
            <div class="form-group">
                <label>Customer Code :</label>
                <input type="text" id="template-code" name="cust_code" maxlength="10">
            </div>
        </div>
    </template>

    <!-- Optionally link your JS script here at the end -->
    <script src="Search/search_handler.js"></script>
</body>

</html>