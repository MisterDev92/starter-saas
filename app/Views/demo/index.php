<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives. Ces pages sont accessibles sans authentification.', 'dismissible' => false]); ?>

<div class="sa-grid sa-grid-3 sa-mt-md">
  <?php
  $pages = [
    ['url' => '/demo/dashboard',     'icon' => 'layout-dashboard', 'title' => 'Dashboard',       'desc' => 'Stats, graphiques, activités récentes'],
    ['url' => '/demo/users',         'icon' => 'users',            'title' => 'Utilisateurs',    'desc' => 'Liste avec recherche, filtres, actions'],
    ['url' => '/demo/user-detail',   'icon' => 'user',             'title' => 'Fiche user',      'desc' => 'Profil complet avec onglets et activité'],
    ['url' => '/demo/subscriptions', 'icon' => 'credit-card',      'title' => 'Abonnements',     'desc' => 'Plans, MRR, churn, liste abonnés'],
    ['url' => '/demo/billing',       'icon' => 'receipt',          'title' => 'Facturation',     'desc' => 'Tableau de factures avec statuts'],
    ['url' => '/demo/analytics',     'icon' => 'chart-line',       'title' => 'Analytics',       'desc' => 'Trafic, sources, devices, pages vues'],
    ['url' => '/demo/logs',          'icon' => 'terminal',         'title' => 'Logs système',    'desc' => 'Viewer avec filtres par niveau'],
    ['url' => '/demo/settings',      'icon' => 'settings',         'title' => 'Paramètres',      'desc' => 'Onglets général, email, sécurité, intégrations'],
    ['url' => '/demo/notifications', 'icon' => 'bell',             'title' => 'Notifications',   'desc' => 'Centre de notifications avec filtres'],
    ['url' => '/demo/support',       'icon' => 'headset',          'title' => 'Support',         'desc' => 'Tickets avec priorités et statuts'],
    ['url' => '/demo/ui-kit',        'icon' => 'palette',          'title' => 'UI Kit',          'desc' => 'Tous les composants en une page'],
  ];
  foreach ($pages as $p):
  ?>
    <a href="<?= url($p['url']) ?>" class="sa-demo-card sa-card">
      <div class="sa-demo-icon"><i class="ti ti-<?= e($p['icon']) ?>"></i></div>
      <div class="sa-demo-info">
        <div class="sa-demo-title"><?= e($p['title']) ?></div>
        <div class="sa-demo-desc"><?= e($p['desc']) ?></div>
      </div>
      <i class="ti ti-arrow-right sa-demo-arrow"></i>
    </a>
  <?php endforeach; ?>
</div>
