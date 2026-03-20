<?php require_once __DIR__ . '/Order/order_logic.php'; ?>


<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Order/orderStyles.css?v=<?php echo filemtime(__DIR__ . '/Order/orderStyles.css'); ?>">
</head>
<template id="add-new-item__template">
    <div class="inline-group item-row">
        <div class="form-group">
            <label>Select Item *:</label>
            <select class="item-select" name="products[]" required>
                <option value="" data-price="0">--Please choose a product--</option>
                <?php foreach ($products as $product): ?>
                <option value="<?php echo htmlspecialchars($product['product_id']); ?>"
                    data-price="<?php echo htmlspecialchars($product['price']); ?>">
                    <?php echo htmlspecialchars($product['name']); ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group quantity-group">
            <label>Quantity *:</label>
            <input class="item-quantity" type="number" name="quantities[]" step="1" min="1" required>
        </div>
        <div class="form-group subtotal-group">
            <label>SubTotal :</label>
            <p class="item-subtotal">0</p>
        </div>
    </div>
</template>

<body>
    <section class="page-container">
        <div class="init-card">
            <header class="action-header">
                <h1>Kitchen Ndeso</h1>
                <p> New Order</p>
                <?php
if (isset($_SESSION['flash_message'])) {
    $msgClass = ($_SESSION['flash_type'] === 'success') ? 'success-msg' : 'global-error';
    echo '<div class="' . $msgClass . '">' . $_SESSION['flash_message'] . '</div>';
    unset($_SESSION['flash_type'], $_SESSION['flash_message']);
}
?>
            </header>

            <form method="post" action="order.php" class="app-form">
                <div class="form-group">
                    <label for="customer-list">Customer Name *:</label>
                    <select id="customer-list" name="customers" required>
                        <option value="">--Please choose a customer--</option>
                        <?php foreach ($customers as $customer): ?>
                        <option value="<?php echo htmlspecialchars($customer['cust_id']); ?>">
                            <?php echo htmlspecialchars($customer['name']) . ' (' . htmlspecialchars($customer['cust_code']) . ')'; ?>
                        </option>
                        <?php
endforeach; ?>
                    </select>
                </div>
                <div class="inline-group">
                    <div class="form-group">
                        <label>Order Date :</label>
                        <input type="datetime-local" id="order-date" name="order_date"
                            value="<?php echo date('Y-m-d\TH:i'); ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Delivery Date *:</label>
                        <input type="datetime-local" id="delivery-date" name="delivery_date"
                            min="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                </div>
                <div class="inline-group">
                    <div class="form-group">
                        <label>Number of Tenures *:</label>
                        <input type="number" name="tenures" step="1" min="1" value="1" required>
                    </div>
                    <div class="form-group">
                        <label>Each Tenure Period *:</label>
                        <div style="display: flex; gap: 10px;">
                            <input type="number" name="tenure_interval" value="1" min="1" required
                                style="width: 80px; flex: none;">
                            <select name="tenure_period" required>
                                <option value="days">Days</option>
                                <option value="weeks">Weeks</option>
                                <option value="months">Months</option>
                                <option value="years">Years</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="actions">
                    <button type="button" id="new-item__button">New Item</button>
                    <button type="button" id="remove-item__button">Remove Item</button>
                </div>
                <div id="items-container" class="items-container">
                    <!-- Items will be dynamically inserted here -->
                </div>
                <?php if (!empty($message)): ?>
                <div class="global-error">
                    <?php echo $message; ?>
                </div>
                <?php
endif; ?>
                <div class="actions">
                    <button type="reset" class="reset-btn">Reset</button>
                    <button type="submit" name="submit" value="submit">Submit</button>
                </div>
            </form>
            <footer class="back-link">
                <a href="./actionOptions.php">
                    <button>Back to Front Page</button>
                </a>
            </footer>
        </div>
    </section>
    <script src="Order/order_handler.js"></script>
</body>

</html>