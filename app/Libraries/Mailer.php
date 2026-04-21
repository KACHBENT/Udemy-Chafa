<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class MailerException extends \RuntimeException {}

class Mailer
{
    private function env(string $key, $default = null)
    {
        if (function_exists('env')) {
            $val = env($key);
            if ($val !== null && $val !== '') return $val;
        }

        $val = getenv($key);
        if ($val !== false && $val !== '') return $val;

        return $default;
    }

    public function sendOrFail(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        ?string $replyToEmail = null,
        ?string $replyToName = null
    ): void {
        $mail = new PHPMailer(true);

        try {
            // ====== Leer config ======
            $host = (string) $this->env('SMTP_HOST', '');
            $user = (string) $this->env('SMTP_USER', '');
            $pass = (string) $this->env('SMTP_PASS', '');
            $port = (int)    $this->env('SMTP_PORT', 587);

            $secure = strtolower((string) $this->env('SMTP_SECURE', 'tls'));
            $fromEmail = (string) $this->env('SMTP_FROM_EMAIL', $user);
            $fromName  = (string) $this->env('SMTP_FROM_NAME', 'Sistema');
            if ($host === '' || $user === '' || $pass === '') {
                throw new MailerException('SMTP incompleto: revisa SMTP_HOST/SMTP_USER/SMTP_PASS en .env');
            }
            if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                throw new MailerException('Correo destino inválido.');
            }
            if ($fromEmail === '') {
                throw new MailerException('SMTP_FROM_EMAIL vacío. Debe ser el mismo buzón que SMTP_USER para mejor compatibilidad.');
            }

            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->Port       = $port;
            $mail->SMTPAuth   = true;
            $mail->Username   = $user;
            $mail->Password   = $pass;

            if ($port === 465 || $secure === 'ssl' || $secure === 'smtps') {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            } else {
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Timeout      = (int) $this->env('SMTP_TIMEOUT', 15);
            $mail->SMTPKeepAlive = false;
            $mail->CharSet      = 'UTF-8';
            $mail->isHTML(true);

            $debug = (int) $this->env('SMTP_DEBUG', 0); 
            if ($debug > 0) {
                $mail->SMTPDebug = $debug;
                $mail->Debugoutput = function ($str, $level) {
                    error_log("SMTP[$level] $str");
                };
            }

            $mail->setFrom($fromEmail, $fromName);

            if ($replyToEmail) {
                $mail->addReplyTo($replyToEmail, $replyToName ?: $replyToEmail);
            }

            $mail->addAddress($toEmail, $toName ?: $toEmail);

            $mail->Subject = $subject;
            $mail->Body    = $htmlBody;

            // ====== Envío ======
            if (!$mail->send()) {
                throw new MailerException($mail->ErrorInfo ?: 'No se pudo enviar el correo.');
            }
        } catch (PHPMailerException $e) {
            throw new MailerException($e->getMessage(), 0, $e);
        }
    }

    public function send(
        string $toEmail,
        string $toName,
        string $subject,
        string $htmlBody,
        ?string $replyToEmail = null,
        ?string $replyToName = null
    ): array {
        try {
            $this->sendOrFail($toEmail, $toName, $subject, $htmlBody, $replyToEmail, $replyToName);
            return ['ok' => true, 'error' => null];
        } catch (\Throwable $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
    }
}
