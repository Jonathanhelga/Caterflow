<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function send_password_reset_email(string $toEmail, string $toName, string $resetLink): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = (int)$_ENV['SMTP_PORT'];
        $mail->CharSet    = 'UTF-8';

        $fromName = $_ENV['SMTP_FROM_NAME'] ?? 'Caterflow';
        $mail->setFrom($_ENV['SMTP_FROM'], $fromName);
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = 'Reset your Caterflow password';

        $safeLink = htmlspecialchars($resetLink, ENT_QUOTES, 'UTF-8');
        $safeName = htmlspecialchars($toName, ENT_QUOTES, 'UTF-8');

        $mail->Body = "
            <div style=\"font-family: Arial, sans-serif; color:#0f172a; max-width:520px; margin:auto;\">
                <h2 style=\"color:#469062;\">Caterflow password reset</h2>
                <p>Hi {$safeName},</p>
                <p>We received a request to reset the password on your Caterflow account.
                Click the button below to choose a new password. This link is valid for <strong>1 hour</strong> and can only be used once.</p>
                <p style=\"margin: 24px 0;\">
                    <a href=\"{$safeLink}\" style=\"background:#469062;color:#fff;padding:12px 22px;border-radius:8px;text-decoration:none;font-weight:600;\">Reset Password</a>
                </p>
                <p>If the button doesn't work, paste this link into your browser:<br>
                <span style=\"color:#475569; word-break: break-all;\">{$safeLink}</span></p>
                <hr style=\"border:none; border-top:1px solid #e2e8f0; margin:24px 0;\">
                <p style=\"font-size:13px; color:#64748b;\">If you didn't request this, you can safely ignore this email — your password will stay the same.</p>
            </div>
        ";
        $mail->AltBody = "Hi {$toName},\n\nReset your Caterflow password using this link (valid for 1 hour, single-use):\n{$resetLink}\n\nIf you didn't request this, ignore this email.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Password reset mail error: " . $mail->ErrorInfo);
        return false;
    }
}
