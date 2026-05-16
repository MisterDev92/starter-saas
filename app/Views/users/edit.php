<?php $errors = $errors ?? []; ?>

<div class="sa-card sa-card-form">
  <div class="sa-card-header">
    <h3 class="sa-card-title">Modifier l'utilisateur</h3>
    <a href="<?= url('/users') ?>" class="sa-btn sa-btn-ghost">
      <i class="ti ti-arrow-left"></i> Retour
    </a>
  </div>
  <div class="sa-card-body">

    <?php component('form-errors', ['errors' => $errors]); ?>

    <form method="POST" action="<?= url('/users/update/' . ($user['id'] ?? '')) ?>">
      <?= csrf_field() ?>

      <div class="sa-grid sa-grid-2">
        <?php component('form-input', ['name' => 'name',  'label' => 'Nom complet',   'value' => $user['name']  ?? '', 'required' => true]); ?>
        <?php component('form-input', ['name' => 'email', 'label' => 'Adresse email', 'type' => 'email', 'value' => $user['email'] ?? '', 'required' => true]); ?>
      </div>

      <div class="sa-grid sa-grid-2">
        <?php component('form-select', [
          'name'     => 'role',
          'label'    => 'Rôle',
          'options'  => ['user' => 'Utilisateur', 'admin' => 'Administrateur'],
          'selected' => $user['role'] ?? 'user',
        ]); ?>

        <?php component('form-toggle', [
          'name'    => 'is_active',
          'label'   => 'Compte actif',
          'checked' => (bool)($user['is_active'] ?? true),
        ]); ?>
      </div>

      <hr class="sa-divider">

      <p class="sa-text-muted sa-text-sm">Laisser vide pour conserver le mot de passe actuel.</p>
      <?php component('form-input', [
        'name'        => 'password',
        'label'       => 'Nouveau mot de passe',
        'type'        => 'password',
        'placeholder' => '8 caractères minimum',
      ]); ?>

      <div class="sa-form-actions">
        <button type="submit" class="sa-btn sa-btn-primary">Enregistrer</button>
        <a href="<?= url('/users') ?>" class="sa-btn sa-btn-ghost">Annuler</a>
      </div>
    </form>
  </div>
</div>
