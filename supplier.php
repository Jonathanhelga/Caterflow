<?php require_once __DIR__ . '/Supplier/supplier_logic.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Detail</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="Supplier/supplierStyles.css?v=<?php echo filemtime(__DIR__ . '/Supplier/supplierStyles.css'); ?>">
</head>

<body>
    <section class="page-container">
        <div class="init-card">
            <header class="action-header">
                <h1>Kitchen Ndeso</h1>
                <p>Supplier Details</p>
                <?php 
                    if (isset($_SESSION['flash_message'])) {
                        $msgClass = ($_SESSION['flash_type'] === 'success') ? 'success-msg' : 'global-error';
                        echo '<div class="' . $msgClass . '">' . $_SESSION['flash_message'] . '</div>';
                        unset($_SESSION['flash_type'], $_SESSION['flash_message']); 
                    } 
                ?>
            </header>

            <form method="post" action="supplier.php" class="customer-form">
                <div class="inline-group">
                    <div class="form-group">
                        <label for="name">Vendor Name *:</label>
                        <input type="text" id="name" name="name" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label for="code">Vendor Code *:</label>
                        <input type="text" id="code" name="vendor_code" maxlength="10" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="product">Vendor Product *:</label>
                    <select name="product" id="product" required>
                        <option value="" disabled selected>Select type</option>
                        <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product['product_id']; ?>"><?php echo $product['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="inline-group">
                    <div class="form-group">
                        <label for="contact_person">Contact Person:</label>
                        <input type="text" id="contact_person" name="contact_person" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *:</label>
                        <input type="text" id="phone" name="phone" maxlength="15" required>
                    </div>
                </div>

                <div class="actions">
                    <button type="reset" class="reset-btn">Reset</button>
                    <button type="submit" name="submit" value="submit">Submit</button>
                </div>
            </form>
            <footer class="back-link">
                <a href="./actionOptions.php">
                    <!-- also here XAMPP = ./actionOptions.php and EC2 = ./actionOptions.php -->
                    <button>Back to Front Page</button>
                </a>
            </footer>
        </div>
    </section>
</body>

</html>