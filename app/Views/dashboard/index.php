<div class="sa-grid sa-grid-4 sa-mb-lg">
  <?php component('stat-card', ['label' => 'Utilisateurs', 'value' => $total_users,  'icon' => 'users',         'color' => 'primary', 'delta' => '', 'delta_type' => 'up']); ?>
  <?php component('stat-card', ['label' => 'Actifs',       'value' => $active_users, 'icon' => 'user-check',    'color' => 'success', 'delta' => '', 'delta_type' => 'up']); ?>
  <?php component('stat-card', ['label' => 'MRR',          'value' => '—',           'icon' => 'currency-euro', 'color' => 'info',    'delta' => '', 'delta_type' => 'up']); ?>
  <?php component('stat-card', ['label' => 'Churn',        'value' => '—',           'icon' => 'trending-down', 'color' => 'warning', 'delta' => '', 'delta_type' => 'down']); ?>
</div>

<div class="sa-grid sa-grid-2 sa-mb-lg">
  <div class="sa-card">
    <div class="sa-card-header"><h3 class="sa-card-title">Aperçu rapide</h3></div>
    <div class="sa-card-body">
      <p class="sa-text-muted">Bienvenue sur <strong><?= e(APP_NAME) ?></strong>.</p>
      <p class="sa-text-muted">Ce dashboard affichera vos métriques clés une fois vos données connectées.</p>
      <a href="<?= url('/demo/dashboard') ?>" class="sa-btn sa-btn-primary sa-mt-md">
        <i class="ti ti-player-play"></i> Voir le dashboard démo
      </a>
    </div>
  </div>
  <div class="sa-card">
    <div class="sa-card-header"><h3 class="sa-card-title">Liens rapides</h3></div>
    <div class="sa-card-body">
      <ul class="sa-quick-links">
        <li><a href="<?= url('/users') ?>"><i class="ti ti-users"></i> Gérer les utilisateurs</a></li>
        <li><a href="<?= url('/demo') ?>"><i class="ti ti-player-play"></i> Pages de démonstration</a></li>
        <li><a href="<?= url('/demo/ui-kit') ?>"><i class="ti ti-palette"></i> UI Kit — Composants</a></li>
      </ul>
    </div>
  </div>
</div>
