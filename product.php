<?php require_once __DIR__ . '/Product/product_logic.php'; ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Product/productStyles.css?v=<?php echo filemtime(__DIR__ . '/Product/productStyles.css'); ?>">
</head>

<body>
    <section class="page-container">
        <div class="init-card">
            <header class="action-header">
                <h1>Kitchen Ndeso</h1>
                <p>Product Details</p>
                <?php 
                    if (isset($_SESSION['flash_message'])) {
                        $msgClass = ($_SESSION['flash_type'] === 'success') ? 'success-msg' : 'global-error';
                        echo '<div class="' . $msgClass . '">' . $_SESSION['flash_message'] . '</div>';
                        unset($_SESSION['flash_type'], $_SESSION['flash_message']); 
                    } 
                ?>
            </header>

            <form method="post" action="product.php" class="app-form">
                <div class="form-group">
                    <label for="category">Product Category :</label>
                    <div class="category-row">
                        <input list="category-list" name="category" id="category" placeholder="Select or type a category…">
                        <datalist id="category-list">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category['name']); ?>">
                            <?php endforeach; ?>
                        </datalist>
                        <button type="button" id="save-category-btn" class="save-category-btn" title="Save new category">Save</button>
                    </div>
                    <p id="category-msg" class="category-msg" aria-live="polite"></p>
                </div>
                <div class="inline-group">
                    <div class="form-group">
                        <label for="name">Product Name *:</label>
                        <input type="text" id="name" name="name" maxlength="100" required>
                    </div>
                    <div class="form-group">
                        <label for="code">Product Code *:</label>
                        <input type="text" id="code" name="product_code" maxlength="10" required>
                    </div>
                </div>
                <div class="inline-group">
                    <div class="form-group">
                        <label for="type">Type of Product *:</label>
                        <select name="type" id="type" required>,
                            <option value="" disabled selected>Select type</option>
                            <option value="Vendor">Vendor</option>
                            <option value="In-house">In-House</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">Selling Price *:</label>
                        <div class="price-input-wrapper">
                            <span class="currency-symbol">Rp.</span>
                            <input type="number" id="price" name="product_price" step="0.01" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="inline-group">
                    <div class="form-group">
                        <label for="cost">Cost of Goods :</label>
                        <div class="price-input-wrapper">
                            <span class="currency-symbol">Rp.</span>
                            <input type="number" id="cost" name="product_cost" step="0.01" min="0">
                        </div>
                    </div>
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
    <script src="Product/product_category.js"></script>
</body>

</html>
