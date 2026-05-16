<?php $errors = $errors ?? []; $old = $old ?? []; ?>

<div class="sa-card sa-card-form">
  <div class="sa-card-header">
    <h3 class="sa-card-title">Nouvel utilisateur</h3>
    <a href="<?= url('/users') ?>" class="sa-btn sa-btn-ghost">
      <i class="ti ti-arrow-left"></i> Retour
    </a>
  </div>
  <div class="sa-card-body">

    <?php component('form-errors', ['errors' => $errors]); ?>

    <form method="POST" action="<?= url('/users/store') ?>">
      <?= csrf_field() ?>

      <div class="sa-grid sa-grid-2">
        <?php component('form-input', ['name' => 'name',  'label' => 'Nom complet',    'value' => $old['name']  ?? '', 'required' => true]); ?>
        <?php component('form-input', ['name' => 'email', 'label' => 'Adresse email',  'type' => 'email', 'value' => $old['email'] ?? '', 'required' => true]); ?>
      </div>

      <?php component('form-select', [
        'name'     => 'role',
        'label'    => 'Rôle',
        'options'  => ['user' => 'Utilisateur', 'admin' => 'Administrateur'],
        'selected' => $old['role'] ?? 'user',
      ]); ?>

      <p class="sa-text-muted sa-text-sm sa-mt-sm">
        Un email d'invitation sera envoyé avec un lien pour définir le mot de passe (valable 24h).
      </p>

      <div class="sa-form-actions">
        <button type="submit" class="sa-btn sa-btn-primary">Créer et envoyer l'invitation</button>
        <a href="<?= url('/users') ?>" class="sa-btn sa-btn-ghost">Annuler</a>
      </div>
    </form>
  </div>
</div>
