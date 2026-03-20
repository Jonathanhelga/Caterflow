<?php require_once __DIR__ . '/Customer/customer_logic.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Detail</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="Customer/customerStyles.css?v=<?php echo filemtime(__DIR__ . '/Customer/customerStyles.css'); ?>">
</head>

<body>
    <section class="page-container">
        <div class="init-card">
            <header class="action-header">
                <h1>Kitchen Ndeso</h1>
                <p>Customer Details</p>
                <?php 
                    if (isset($_SESSION['flash_message'])) {
                        $msgClass = ($_SESSION['flash_type'] === 'success') ? 'success-msg' : 'global-error';
                        echo '<div class="' . $msgClass . '">' . $_SESSION['flash_message'] . '</div>';
                        unset($_SESSION['flash_type'], $_SESSION['flash_message']); 
                    } 
                ?>
            </header>

            <form method="post" action="customer.php" class="app-form">
                <div class="form-group">
                    <label for="name">Customer Name *:</label>
                    <input type="text" id="name" name="name" maxlength="100" required>
                </div>
                <div class="inline-group">
                    <div class="form-group">
                        <label for="type">Type of Customer *:</label>
                        <select name="type" id="type" required>
                            <option value="" disabled selected>Select type</option>
                            <option value="individual">Individual</option>
                            <option value="hotel">Hotel</option>
                            <option value="company">Company</option>
                            <option value="reseller">Reseller</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cust_code">Customer Code *:</label>
                        <input type="text" id="cust_code" name="cust_code" maxlength="10" required>
                    </div>
                </div>
                <div class="inline-group">
                    <div class="form-group">
                        <label for="email">Email Address / Contact Person:</label>
                        <input type="text" id="email" name="email" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *:</label>
                        <input type="text" id="phone" name="phone" maxlength="20" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="city">City:</label>
                    <input type="text" id="city" name="city" maxlength="100">
                </div>
                <div class="form-group">
                    <label for="address_line">Address Line:</label>
                    <textarea id="address_line" name="address_line" rows="3"></textarea>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="global-error"><?php echo $message; ?></div>
                <?php endif; ?>

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