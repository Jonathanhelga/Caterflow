<?php 
require_once __DIR__ . '/Register/register_logic.php';  
?> 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Register Page</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="Register/registerStyles.css?v=<?php echo filemtime(__DIR__ . '/Register/registerStyles.css'); ?>">
    </head>
    <body>
        <section class="page-container">
            <div class="register-card">
                <header class="register-header">
                    <h1>Register Page</h1>
                </header>

                <form method="post" action="index.php" class="registration-form">
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username"
                            placeholder="e.g. catering_pro"
                            value="<?php echo htmlspecialchars($username); ?>">
                        <?php if (!empty($errors['username'])): ?>
                            <span class="error-text"><?php echo $errors['username']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email"
                            placeholder="you@example.com"
                            value="<?php echo htmlspecialchars($email); ?>">
                        <?php if (!empty($errors['email'])): ?>
                            <span class="error-text"><?php echo $errors['email']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Min. 6 characters">
                        <?php if (!empty($errors['password'])): ?>
                            <span class="error-text"><?php echo $errors['password']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat password">
                        <?php if (!empty($errors['confirm_password'])): ?>
                            <span class="error-text"><?php echo $errors['confirm_password']; ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="form-options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember_me" id="remember_me">
                            <span>Remember Me On This Device</span>
                        </label>
                    </div>

                    <?php if (!empty($errors['weird'])): ?>
                        <div class="global-error"><?php echo $errors['weird']; ?></div>
                    <?php endif; ?>

                    <div class="form-actions">
                        <input type="submit" name="submit" value="Create Account" class="btn-submit">
                    </div>
                </form>

                <footer class="register-footer">
                    <p>Already Have an Account? <a href="login.php">Click Here</a></p>
                    <p>Forgot Your Password? <a href="forgot_password.php">Click Here</a></p>
                </footer>
            </div>
        </section>
    </body>
</html>