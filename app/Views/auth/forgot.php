<?php ?>

<h2 class="sa-auth-title">Mot de passe oublié</h2>
<p class="sa-auth-subtitle">Saisissez votre email pour recevoir un lien de réinitialisation.</p>

<?php if ($flash): ?>
  <?php component('alert', ['type' => $flash['type'], 'message' => $flash['message'], 'dismissible' => true]); ?>
<?php endif; ?>

<form method="POST" action="<?= url('/forgot-password') ?>">
  <?= csrf_field() ?>

  <?php component('form-input', [
    'name'        => 'email',
    'label'       => 'Adresse email',
    'type'        => 'email',
    'placeholder' => 'vous@exemple.com',
    'required'    => true,
  ]); ?>

  <button type="submit" class="sa-btn sa-btn-primary sa-btn-full">Envoyer le lien</button>
</form>

<p class="sa-auth-alt">
  <a href="<?= url('/login') ?>" class="sa-link">← Retour à la connexion</a>
</p>
