<?php $errors = $errors ?? []; $old = $old ?? []; ?>

<h2 class="sa-auth-title">Créer un compte</h2>

<?php if ($flash): ?>
  <?php component('alert', ['type' => $flash['type'], 'message' => $flash['message'], 'dismissible' => true]); ?>
<?php endif; ?>

<?php component('form-errors', ['errors' => $errors]); ?>

<form method="POST" action="<?= url('/register') ?>" novalidate>
  <?= csrf_field() ?>

  <?php component('form-input', [
    'name'     => 'name',
    'label'    => 'Nom complet',
    'value'    => $old['name'] ?? '',
    'required' => true,
  ]); ?>

  <?php component('form-input', [
    'name'     => 'email',
    'label'    => 'Adresse email',
    'type'     => 'email',
    'value'    => $old['email'] ?? '',
    'required' => true,
  ]); ?>

  <?php component('form-input', [
    'name'        => 'password',
    'label'       => 'Mot de passe',
    'type'        => 'password',
    'placeholder' => '8 caractères minimum',
    'required'    => true,
  ]); ?>

  <button type="submit" class="sa-btn sa-btn-primary sa-btn-full">Créer mon compte</button>
</form>

<p class="sa-auth-alt">
  Déjà un compte ? <a href="<?= url('/login') ?>" class="sa-link">Se connecter</a>
</p>
