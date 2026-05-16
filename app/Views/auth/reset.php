<?php $errors = $errors ?? []; ?>

<h2 class="sa-auth-title">Nouveau mot de passe</h2>

<?php component('form-errors', ['errors' => $errors]); ?>

<form method="POST" action="<?= url('/reset-password') ?>">
  <?= csrf_field() ?>
  <input type="hidden" name="token" value="<?= e($token ?? '') ?>">

  <?php component('form-input', [
    'name'        => 'password',
    'label'       => 'Nouveau mot de passe',
    'type'        => 'password',
    'placeholder' => '8 caractères minimum',
    'required'    => true,
  ]); ?>

  <?php component('form-input', [
    'name'        => 'password_confirm',
    'label'       => 'Confirmer le mot de passe',
    'type'        => 'password',
    'placeholder' => '••••••••',
    'required'    => true,
  ]); ?>

  <button type="submit" class="sa-btn sa-btn-primary sa-btn-full">Enregistrer</button>
</form>
