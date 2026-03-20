<?php
require_once __DIR__ . '/Login/login_logic.php'; 
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Page</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="Login/loginStyles.css?v=<?php echo filemtime(__DIR__ . '/Login/loginStyles.css'); ?>">
    </head>
    <body>
        <section class="page-container">
            <div class="login-card">
                <header class="login-header">
                    <h1>Login Page</h1>
                    <?php 
                        if (isset($_SESSION['success_msg'])) {
                            echo '<div class="success-msg">' . $_SESSION['success_msg'] . '</div>';
                            unset($_SESSION['success_msg']); 
                        } 
                    ?>
                    
                </header>

                <form method="post" action="login.php" class="login-form">
                    
                    <div class="form-group">
                        <label for="username">Username / Email</label>
                        <input type="text" id="username" name="username" 
                            placeholder="Username or Email" 
                            value="<?php echo htmlspecialchars($username); ?>"
                            class="<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>">
                        <?php if (!empty($username_err)): ?>
                            <span class="error-text"><?php echo $username_err; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" 
                            placeholder="Password"
                            class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                        <?php if (!empty($password_err)): ?>
                            <span class="error-text"><?php echo $password_err; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember_me" id="remember_meo">
                            <span>Remember Me On This Device</span>
                        </label>
                    </div>

                    <?php if (!empty($login_err)): ?>
                        <div class="global-error"><?php echo $login_err; ?></div>
                    <?php endif; ?>

                    <div class="form-actions">
                        <input type="submit" name="submit" value="Login" class="btn-submit">
                    </div>
                </form>

                <footer class="login-footer">
                    <p>Not yet have an Account? <a href="./index.php">Click Here</a></p>
                    <!-- on xampp href="./index.php" and on AWS EC2 ../index.php or else it will throw an error -->
                    <p>Forgot Your Password? <a href="#">Click Here</a></p>
                </footer>
            </div>
        </section>
    </body>
</html>