<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives. Aucune modification n\'est enregistrée.', 'dismissible' => true]); ?>

<?php
ob_start(); ?>
  <form class="sa-form-settings">
    <div class="sa-grid sa-grid-2">
      <?php component('form-input', ['name' => 'app_name', 'label' => 'Nom du SaaS', 'value' => 'Mon SaaS']); ?>
      <?php component('form-input', ['name' => 'app_url',  'label' => 'URL de base',  'value' => 'https://monsaas.com']); ?>
    </div>
    <?php component('form-select', ['name' => 'lang', 'label' => 'Langue', 'options' => ['fr' => 'Français', 'en' => 'English'], 'selected' => 'fr']); ?>
    <?php component('form-select', ['name' => 'timezone', 'label' => 'Fuseau horaire', 'options' => ['Europe/Paris' => 'Europe/Paris', 'UTC' => 'UTC'], 'selected' => 'Europe/Paris']); ?>
    <div class="sa-form-actions"><button type="button" class="sa-btn sa-btn-primary" data-confirm="Enregistrer (démo) ?">Enregistrer</button></div>
  </form>
<?php $tabGeneral = ob_get_clean();

ob_start(); ?>
  <form class="sa-form-settings">
    <div class="sa-grid sa-grid-2">
      <?php component('form-input', ['name' => 'mail_host', 'label' => 'SMTP Host',     'value' => 'smtp.mailtrap.io']); ?>
      <?php component('form-input', ['name' => 'mail_port', 'label' => 'SMTP Port',     'type' => 'number', 'value' => '2525']); ?>
      <?php component('form-input', ['name' => 'mail_user', 'label' => 'SMTP User',     'value' => '']); ?>
      <?php component('form-input', ['name' => 'mail_pass', 'label' => 'SMTP Password', 'type' => 'password', 'value' => '']); ?>
    </div>
    <?php component('form-input', ['name' => 'mail_from', 'label' => 'Email expéditeur', 'type' => 'email', 'value' => 'noreply@monsaas.com']); ?>
    <div class="sa-form-actions">
      <button type="button" class="sa-btn sa-btn-primary" data-confirm="Enregistrer (démo) ?">Enregistrer</button>
      <button type="button" class="sa-btn sa-btn-outline">Envoyer un email test</button>
    </div>
  </form>
<?php $tabEmail = ob_get_clean();

ob_start(); ?>
  <?php component('form-toggle', ['name' => '2fa',      'label' => 'Activer la double authentification (2FA)', 'checked' => false, 'description' => 'Recommandé pour les comptes admin.']); ?>
  <?php component('form-toggle', ['name' => 'ip_white', 'label' => 'Restriction par IP',                       'checked' => false, 'description' => 'Restreindre l\'accès aux IPs listées ci-dessous.']); ?>
  <?php component('form-input', ['name' => 'session_ttl', 'label' => 'Durée de session (secondes)', 'type' => 'number', 'value' => '7200']); ?>
  <div class="sa-form-actions"><button type="button" class="sa-btn sa-btn-primary" data-confirm="Enregistrer (démo) ?">Enregistrer</button></div>
<?php $tabSecurity = ob_get_clean();

ob_start(); ?>
  <?php foreach (['Stripe', 'Mailchimp', 'Slack', 'Zapier'] as $int): ?>
    <div class="sa-integration-row">
      <?php component('form-toggle', ['name' => strtolower($int), 'label' => $int, 'checked' => $int === 'Stripe']); ?>
      <?php component('form-input', ['name' => strtolower($int) . '_key', 'label' => '', 'placeholder' => 'Clé API ' . $int, 'value' => $int === 'Stripe' ? 'sk_live_••••••••••••' : '']); ?>
    </div>
  <?php endforeach; ?>
<?php $tabIntegrations = ob_get_clean();

ob_start(); ?>
  <div class="sa-danger-zone">
    <div class="sa-danger-item">
      <div>
        <strong>Vider le cache</strong>
        <p class="sa-text-muted sa-text-sm">Supprime les fichiers de cache temporaires.</p>
      </div>
      <button class="sa-btn sa-btn-outline-warning" data-confirm="Vider le cache (démo) ?">Vider le cache</button>
    </div>
    <div class="sa-danger-item">
      <div>
        <strong>Supprimer le compte</strong>
        <p class="sa-text-muted sa-text-sm">Suppression définitive de toutes les données.</p>
      </div>
      <button class="sa-btn sa-btn-outline-danger" data-confirm="Supprimer définitivement (démo) ?">Supprimer</button>
    </div>
  </div>
<?php $tabDanger = ob_get_clean();

component('tabs', ['tabs' => [
  ['label' => 'Général',        'icon' => 'settings',      'content' => $tabGeneral],
  ['label' => 'Email',          'icon' => 'mail',          'content' => $tabEmail],
  ['label' => 'Sécurité',       'icon' => 'shield',        'content' => $tabSecurity],
  ['label' => 'Intégrations',   'icon' => 'plug',          'content' => $tabIntegrations],
  ['label' => 'Danger zone',    'icon' => 'alert-triangle','content' => $tabDanger],
]]);
?>
