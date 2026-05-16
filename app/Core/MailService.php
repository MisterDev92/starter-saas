<?php

namespace Core;

/**
 * Envoi d'emails via Mandrill.
 * Requiert le dossier /mandrill/ (copiez-le depuis le projet source).
 */
class MailService
{
    private string $fromEmail;
    private string $fromName;

    public function __construct()
    {
        $this->fromEmail = defined('MAIL_FROM')      ? MAIL_FROM      : 'noreply@example.com';
        $this->fromName  = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : (defined('APP_NAME') ? APP_NAME : 'App');
    }

    public function sendInvitation(string $toEmail, string $toName, string $setupUrl): bool
    {
        return $this->send(
            $toEmail,
            $toName,
            'Bienvenue — Définissez votre mot de passe',
            $this->buildHtml(
                'Bienvenue sur ' . (defined('APP_NAME') ? APP_NAME : 'l\'application'),
                'Votre compte a été créé. Cliquez sur le bouton ci-dessous pour définir votre mot de passe.',
                'Définir mon mot de passe',
                $setupUrl,
                'Ce lien est valable <strong>24 heures</strong>.'
            )
        );
    }

    public function sendPasswordReset(string $toEmail, string $toName, string $resetUrl): bool
    {
        return $this->send(
            $toEmail,
            $toName,
            'Réinitialisation de votre mot de passe',
            $this->buildHtml(
                'Réinitialisation du mot de passe',
                'Vous avez demandé la réinitialisation de votre mot de passe.',
                'Réinitialiser mon mot de passe',
                $resetUrl,
                'Ce lien est valable <strong>15 minutes</strong>. Si vous n\'êtes pas à l\'origine de cette demande, ignorez cet email.'
            )
        );
    }

    private function send(string $toEmail, string $toName, string $subject, string $html): bool
    {
        $mandrillLib = ROOT_PATH . '/mandrill/src/Mandrill.php';
        if (!file_exists($mandrillLib)) {
            error_log('[MailService] Bibliothèque Mandrill introuvable : ' . $mandrillLib);
            return false;
        }

        $apiKey = defined('MANDRILL_KEY') ? MANDRILL_KEY : '';
        if ($apiKey === '') {
            error_log('[MailService] MANDRILL_KEY non définie dans .env');
            return false;
        }

        try {
            require_once $mandrillLib;
            $mandrill  = new \Mandrill($apiKey);
            $message   = [
                'subject'      => $subject,
                'from_email'   => $this->fromEmail,
                'from_name'    => $this->fromName,
                'to'           => [['email' => $toEmail, 'name' => $toName, 'type' => 'to']],
                'html'         => $html,
                'auto_text'    => true,
                'track_opens'  => true,
                'track_clicks' => true,
            ];
            $mandrill->messages->send($message, false, null, null);
            return true;
        } catch (\Exception $e) {
            error_log('[MailService] Erreur Mandrill : ' . $e->getMessage());
            return false;
        }
    }

    private function buildHtml(string $title, string $body, string $btnLabel, string $btnUrl, string $footer): string
    {
        $appName = defined('APP_NAME') ? e(APP_NAME) : 'App';
        $safeBtn = e($btnUrl);
        $safeTitle = e($title);
        $safeLabel = e($btnLabel);

        return '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#f1f5f9;font-family:-apple-system,BlinkMacSystemFont,\'Segoe UI\',sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
<tr><td align="center">
<table width="520" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.08);">
  <tr><td style="background:#4f46e5;padding:24px 40px;text-align:center;">
    <span style="color:#fff;font-size:1.1rem;font-weight:700;">' . $appName . '</span>
  </td></tr>
  <tr><td style="padding:32px 40px;">
    <h1 style="margin:0 0 16px;font-size:1.15rem;font-weight:700;color:#1e293b;">' . $safeTitle . '</h1>
    <p style="margin:0 0 24px;font-size:.95rem;line-height:1.6;color:#374151;">' . $body . '</p>
    <table cellpadding="0" cellspacing="0">
      <tr><td style="border-radius:8px;background:#4f46e5;">
        <a href="' . $safeBtn . '" style="display:inline-block;padding:12px 28px;color:#fff;font-size:.95rem;font-weight:600;text-decoration:none;">' . $safeLabel . '</a>
      </td></tr>
    </table>
    <p style="margin:20px 0 0;font-size:.82rem;color:#64748b;">' . $footer . '</p>
    <hr style="margin:20px 0;border:none;border-top:1px solid #e2e8f0;">
    <p style="margin:0;font-size:.78rem;color:#94a3b8;">Lien direct : <a href="' . $safeBtn . '" style="color:#4f46e5;">' . $safeBtn . '</a></p>
  </td></tr>
  <tr><td style="padding:16px 40px;background:#f8fafc;border-top:1px solid #e2e8f0;text-align:center;">
    <span style="font-size:.75rem;color:#94a3b8;">© ' . $appName . '</span>
  </td></tr>
</table>
</td></tr>
</table>
</body></html>';
    }
}
