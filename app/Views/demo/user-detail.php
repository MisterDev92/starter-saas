<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives.', 'dismissible' => true]); ?>

<div class="sa-grid sa-grid-3-1 sa-mb-lg">
  <!-- Profil -->
  <div class="sa-card sa-text-center sa-card-profile">
    <div class="sa-card-body">
      <?php component('avatar', ['name' => $user['name'], 'size' => 'lg']); ?>
      <h3 class="sa-mt-md"><?= e($user['name']) ?></h3>
      <p class="sa-text-muted"><?= e($user['email']) ?></p>
      <div class="sa-mt-sm">
        <?php component('badge', ['label' => $user['role'], 'type' => $user['role'] === 'admin' ? 'admin' : 'user']); ?>
        <?php component('badge', ['label' => $user['plan'] ?? 'Pro', 'type' => 'primary']); ?>
        <?php component('badge', ['label' => $user['is_active'] ? 'Actif' : 'Inactif', 'type' => $user['is_active'] ? 'active' : 'inactive']); ?>
      </div>
      <div class="sa-profile-stats sa-mt-lg">
        <?php component('stat-mini', ['label' => 'Connexions', 'value' => '142', 'color' => 'primary']); ?>
        <?php component('stat-mini', ['label' => 'Tickets',   'value' => '4',   'color' => 'warning']); ?>
        <?php component('stat-mini', ['label' => 'Jours',     'value' => '187', 'color' => 'info']); ?>
      </div>
    </div>
  </div>

  <!-- Onglets -->
  <div>
    <?php
    ob_start(); ?>
      <dl class="sa-dl">
        <dt>Inscrit le</dt><dd><?= e(format_date($user['created_at'])) ?></dd>
        <dt>Dernière connexion</dt><dd><?= e(format_date($user['last_login'])) ?></dd>
        <dt>Plan</dt><dd><?= e($user['plan'] ?? 'Pro') ?></dd>
        <dt>Email vérifié</dt><dd><?php component('badge', ['label' => 'Oui', 'type' => 'active']); ?></dd>
        <dt>2FA</dt><dd><?php component('badge', ['label' => 'Non activé', 'type' => 'inactive']); ?></dd>
      </dl>
    <?php $tabInfo = ob_get_clean();

    ob_start(); ?>
      <?php component('timeline', ['events' => array_map(fn($a) => [
        'icon' => $a['icon'], 'color' => $a['color'], 'title' => $a['title'], 'description' => $a['desc'] ?? '', 'date' => $a['date'],
      ], $activities)]); ?>
    <?php $tabActivity = ob_get_clean();

    ob_start(); ?>
      <dl class="sa-dl">
        <dt>Plan actuel</dt><dd><strong><?= e($user['plan'] ?? 'Pro') ?></strong></dd>
        <dt>Prix mensuel</dt><dd>49 €</dd>
        <dt>Prochaine facturation</dt><dd>15/06/2026</dd>
        <dt>Statut</dt><dd><?php component('badge', ['label' => 'Actif', 'type' => 'active']); ?></dd>
      </dl>
    <?php $tabSub = ob_get_clean();

    ob_start();
    component('chart-bar', [
      'id'       => 'chartLogins',
      'labels'   => $chart_weeks,
      'datasets' => [['label' => 'Connexions', 'data' => $chart_logins, 'backgroundColor' => '#4f46e5']],
    ]);
    $tabLogs = ob_get_clean();

    component('tabs', ['tabs' => [
      ['label' => 'Informations', 'icon' => 'info-circle',  'content' => $tabInfo],
      ['label' => 'Activité',     'icon' => 'activity',     'content' => $tabActivity],
      ['label' => 'Abonnement',   'icon' => 'credit-card',  'content' => $tabSub],
      ['label' => 'Connexions',   'icon' => 'chart-bar',    'content' => $tabLogs],
    ]]);
    ?>
  </div>
</div>
