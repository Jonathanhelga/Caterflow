<?php require_once __DIR__ . '/CustomerContact/contact_logic.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Contacts</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet"
        href="CustomerContact/contactStyles.css?v=<?php echo filemtime(__DIR__ . '/CustomerContact/contactStyles.css'); ?>">
</head>

<body>
    <section class="page-container">
        <div class="contact-card" data-selected="<?php echo $selectedCustId; ?>">

            <!-- Header -->
            <header class="action-header">
                <h1>Kitchen Ndeso</h1>
                <p>Customer Contacts</p>
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="<?php echo $_SESSION['flash_type'] === 'success' ? 'success-msg' : 'global-error'; ?>">
                        <?php echo $_SESSION['flash_message']; ?>
                    </div>
                    <?php unset($_SESSION['flash_message'], $_SESSION['flash_type']); ?>
                <?php endif; ?>
            </header>

            <!-- Customer Selector -->
            <div class="customer-selector">
                <label for="customer-pick">Select Customer (Hotel / Company) *</label>
                <select id="customer-pick" <?php echo empty($customers) ? 'disabled' : ''; ?>>
                    <option value="" disabled <?php echo !$selectedCustId ? 'selected' : ''; ?>>
                        <?php echo empty($customers) ? '— No hotel/company customers yet —' : 'Choose a customer…'; ?>
                    </option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?php echo $c['cust_id']; ?>"
                            data-type="<?php echo htmlspecialchars($c['type']); ?>"
                            <?php echo ($c['cust_id'] == $selectedCustId) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['cust_code'] . ' — ' . $c['name']); ?>
                            (<?php echo ucfirst($c['type']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Main Content: Existing Contacts + Add Form -->
            <div class="content-grid">

                <!-- Left: Existing Contacts -->
                <div class="contacts-panel">
                    <h3 class="panel-title">Existing Contacts</h3>
                    <div id="contacts-list">
                        <p class="panel-placeholder">Select a customer to view their contacts.</p>
                    </div>
                </div>

                <!-- Right: Add New Contact Form -->
                <div class="form-panel">
                    <h3 class="panel-title">Add New Contact</h3>

                    <form method="post" action="customer_contact.php" class="app-form" id="contact-form">
                        <input type="hidden" name="cust_id" id="form-cust-id"
                            value="<?php echo $selectedCustId; ?>">

                        <div class="form-group">
                            <label for="type">Role / Position *</label>
                            <select name="type" id="type" required>
                                <option value="" disabled selected>Select role…</option>
                                <option value="Purchasing">Purchasing</option>
                                <option value="Payment">Payment</option>
                                <option value="Receiving">Receiving</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="contact_name">Contact Name *</label>
                            <input type="text" id="contact_name" name="contact_name"
                                maxlength="100" placeholder="e.g. Budi Santoso" required>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="text" id="phone" name="phone"
                                maxlength="20" placeholder="e.g. 08123456789" required>
                        </div>

                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea id="notes" name="notes" rows="3"
                                placeholder="e.g. Only available on weekdays, extension 102"></textarea>
                        </div>

                        <?php if (!empty($message)): ?>
                            <div class="global-error"><?php echo htmlspecialchars($message); ?></div>
                        <?php endif; ?>

                        <div class="actions">
                            <button type="reset" class="reset-btn">Reset</button>
                            <button type="submit" name="submit" value="submit" id="submit-btn" disabled>
                                Add Contact
                            </button>
                        </div>
                    </form>
                </div>

            </div><!-- /.content-grid -->

            <footer class="back-link">
                <a href="actionOptions.php"><button>Back to Front Page</button></a>
            </footer>
        </div>
    </section>

    <script src="CustomerContact/contact_handler.js"></script>
</body>

</html>
