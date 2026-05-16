<?php
// Props: $menu_items (array), $user (array)
$menu_items = $menu_items ?? [];
$user       = $user       ?? [];
$userRole   = $user['role'] ?? 'user';

$defaultMenu = [
    ['label' => 'Dashboard',      'icon' => 'layout-dashboard', 'url' => '/dashboard',  'admin' => false],
    ['label' => 'Utilisateurs',   'icon' => 'users',            'url' => '/users',      'admin' => true],
    ['label' => '──────',         'icon' => '',                 'url' => '',            'admin' => false, 'divider' => true],
    ['label' => 'Démo',           'icon' => 'player-play',      'url' => '/demo',       'admin' => false],
];

$items = count($menu_items) ? $menu_items : $defaultMenu;
?>

<nav class="sa-sidebar" id="saSidebar">
  <div class="sa-sidebar-header">
    <a href="<?= url('/dashboard') ?>" class="sa-sidebar-brand">
      <span class="sa-brand-icon"><i class="ti ti-hexagon-letter-s"></i></span>
      <span class="sa-brand-name"><?= e(APP_NAME) ?></span>
    </a>
    <button class="sa-sidebar-close" id="saSidebarClose" aria-label="Fermer le menu">
      <i class="ti ti-x"></i>
    </button>
  </div>

  <div class="sa-sidebar-body">
    <ul class="sa-nav">
      <?php foreach ($items as $item): ?>
        <?php if (!empty($item['divider'])): ?>
          <li class="sa-nav-divider"></li>
          <?php continue; ?>
        <?php endif; ?>

        <?php if (!empty($item['admin']) && $userRole !== 'admin') continue; ?>

        <li class="sa-nav-item">
          <a href="<?= url($item['url']) ?>"
             class="sa-nav-link <?= active($item['url']) ?>">
            <span class="sa-nav-icon"><i class="ti ti-<?= e($item['icon']) ?>"></i></span>
            <span class="sa-nav-label"><?= e($item['label']) ?></span>
            <?php if (!empty($item['badge'])): ?>
              <span class="sa-badge sa-badge-<?= e($item['badge_type'] ?? 'primary') ?>"><?= e($item['badge']) ?></span>
            <?php endif; ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>

  <div class="sa-sidebar-footer">
    <div class="sa-sidebar-user">
      <?php component('avatar', ['name' => $user['name'] ?? 'U', 'size' => 'sm']); ?>
      <div class="sa-sidebar-user-info">
        <span class="sa-sidebar-user-name"><?= e($user['name'] ?? '') ?></span>
        <span class="sa-sidebar-user-role"><?= e($userRole) ?></span>
      </div>
    </div>
    <a href="<?= url('/logout') ?>" class="sa-sidebar-logout" title="Se déconnecter">
      <i class="ti ti-logout"></i>
    </a>
  </div>
</nav>

<div class="sa-sidebar-overlay" id="saSidebarOverlay"></div>
