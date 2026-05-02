<?php
require_once __DIR__ . '/ForgotPassword/password_reset_logic.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="ForgotPassword/forgotPasswordStyles.css?v=<?php echo filemtime(__DIR__ . '/ForgotPassword/forgotPasswordStyles.css'); ?>">
    </head>
    <body>
        <section class="page-container">
            <div class="forgot-card">
                <header class="forgot-header">
                    <h1>Set a New Password</h1>
                    <?php if ($valid_token): ?>
                        <p>Choose a new password for your account. This link is single-use.</p>
                    <?php endif; ?>
                </header>

                <?php if (!$valid_token): ?>
                    <div class="global-error"><?php echo htmlspecialchars($global_err); ?></div>
                    <footer class="forgot-footer">
                        <p><a href="forgot_password.php">Request a new reset link</a></p>
                    </footer>
                <?php else: ?>
                    <form method="post" action="password_reset.php" class="forgot-form">
                        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                        <div class="form-group">
                            <label for="password">New Password</label>
                            <input type="password" id="password" name="password"
                                placeholder="Min. 6 characters"
                                class="<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                            <?php if (!empty($password_err)): ?>
                                <span class="error-text"><?php echo $password_err; ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password"
                                placeholder="Repeat password"
                                class="<?php echo (!empty($confirm_err)) ? 'is-invalid' : ''; ?>">
                            <?php if (!empty($confirm_err)): ?>
                                <span class="error-text"><?php echo $confirm_err; ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if (!empty($global_err)): ?>
                            <div class="global-error"><?php echo $global_err; ?></div>
                        <?php endif; ?>

                        <div class="form-actions">
                            <input type="submit" name="submit" value="Reset Password" class="btn-submit">
                        </div>
                    </form>

                    <footer class="forgot-footer">
                        <p><a href="login.php">Back to Login</a></p>
                    </footer>
                <?php endif; ?>
            </div>
        </section>
    </body>
</html>
