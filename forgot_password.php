<?php
require_once __DIR__ . '/ForgotPassword/forgot_password_logic.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="ForgotPassword/forgotPasswordStyles.css?v=<?php echo filemtime(__DIR__ . '/ForgotPassword/forgotPasswordStyles.css'); ?>">
    </head>
    <body>
        <section class="page-container">
            <div class="forgot-card">
                <header class="forgot-header">
                    <h1>Forgot Your Password?</h1>
                    <p>Enter the email associated with your Caterflow account and we'll send you a link to reset your password.</p>
                </header>

                <?php if ($success): ?>
                    <div class="success-msg">
                        If an account exists for that email, a reset link has just been sent. Please check your inbox (and spam folder). The link will expire in 1 hour.
                    </div>
                <?php else: ?>
                    <form method="post" action="forgot_password.php" class="forgot-form">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email"
                                placeholder="you@example.com"
                                value="<?php echo htmlspecialchars($email); ?>"
                                class="<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>">
                            <?php if (!empty($email_err)): ?>
                                <span class="error-text"><?php echo $email_err; ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($global_err)): ?>
                            <div class="global-error"><?php echo $global_err; ?></div>
                        <?php endif; ?>

                        <div class="form-actions">
                            <input type="submit" name="submit" value="Send Reset Link" class="btn-submit">
                        </div>
                    </form>
                <?php endif; ?>

                <footer class="forgot-footer">
                    <p>Remembered your password? <a href="login.php">Back to Login</a></p>
                </footer>
            </div>
        </section>
    </body>
</html>
