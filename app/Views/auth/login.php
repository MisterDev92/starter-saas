<?php $errors = $errors ?? []; $old = $old ?? []; ?>

<h2 class="sa-auth-title">Connexion</h2>
<p class="sa-auth-subtitle">Entrez vos identifiants pour accéder au tableau de bord.</p>

<?php if ($flash): ?>
  <?php component('alert', ['type' => $flash['type'], 'message' => $flash['message'], 'dismissible' => true]); ?>
<?php endif; ?>

<?php component('form-errors', ['errors' => $errors]); ?>

<form method="POST" action="<?= url('/login') ?>" novalidate>
  <?= csrf_field() ?>

  <?php component('form-input', [
    'name'        => 'email',
    'label'       => 'Adresse email',
    'type'        => 'email',
    'value'       => $old['email'] ?? '',
    'placeholder' => 'vous@exemple.com',
    'required'    => true,
  ]); ?>

  <?php component('form-input', [
    'name'        => 'password',
    'label'       => 'Mot de passe',
    'type'        => 'password',
    'placeholder' => '••••••••',
    'required'    => true,
  ]); ?>

  <div class="sa-auth-actions">
    <a href="<?= url('/forgot-password') ?>" class="sa-link sa-text-sm">Mot de passe oublié ?</a>
  </div>

  <button type="submit" class="sa-btn sa-btn-primary sa-btn-full">Se connecter</button>
</form>

<p class="sa-auth-alt">
  Pas encore de compte ? <a href="<?= url('/register') ?>" class="sa-link">Créer un compte</a>
</p>
