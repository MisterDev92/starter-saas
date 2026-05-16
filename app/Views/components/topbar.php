<?php
// Props: $page_title (string), $notifications (array)
$page_title    = $page_title    ?? '';
$notifications = $notifications ?? [];
$unreadCount   = count(array_filter($notifications, fn($n) => empty($n['read'])));
?>

<header class="sa-topbar">
  <div class="sa-topbar-left">
    <button class="sa-sidebar-toggle" id="saSidebarToggle" aria-label="Ouvrir le menu">
      <i class="ti ti-menu-2"></i>
    </button>
    <?php if ($page_title): ?>
      <h1 class="sa-topbar-title"><?= e($page_title) ?></h1>
    <?php endif; ?>
  </div>

  <div class="sa-topbar-right">

    <!-- Dark mode -->
    <button class="sa-topbar-btn" id="saDarkToggle" title="Thème sombre">
      <i class="ti ti-moon" id="saDarkIcon"></i>
    </button>

    <!-- Notifications -->
    <div class="sa-dropdown" id="saNotifDropdown">
      <button class="sa-topbar-btn sa-notif-btn" data-dropdown="saNotifDropdown">
        <i class="ti ti-bell"></i>
        <?php if ($unreadCount > 0): ?>
          <span class="sa-notif-badge"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
        <?php endif; ?>
      </button>
      <div class="sa-dropdown-menu sa-dropdown-menu-right">
        <div class="sa-dropdown-header">
          <span>Notifications</span>
          <?php if ($unreadCount > 0): ?>
            <span class="sa-badge sa-badge-primary"><?= $unreadCount ?> non lues</span>
          <?php endif; ?>
        </div>
        <?php if (empty($notifications)): ?>
          <div class="sa-dropdown-empty">Aucune notification</div>
        <?php else: ?>
          <?php foreach (array_slice($notifications, 0, 5) as $notif): ?>
            <div class="sa-dropdown-item <?= empty($notif['read']) ? 'sa-unread' : '' ?>">
              <span class="sa-notif-icon sa-text-<?= e($notif['color'] ?? 'info') ?>">
                <i class="ti ti-<?= e($notif['icon'] ?? 'bell') ?>"></i>
              </span>
              <div class="sa-notif-body">
                <div class="sa-notif-title"><?= e($notif['title'] ?? '') ?></div>
                <div class="sa-notif-text"><?= e(truncate($notif['text'] ?? '', 60)) ?></div>
                <div class="sa-notif-date"><?= e(format_date($notif['date'] ?? '', 'd/m H:i')) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
          <div class="sa-dropdown-footer">
            <a href="<?= url('/demo/notifications') ?>">Voir toutes</a>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Profil -->
    <div class="sa-dropdown" id="saProfileDropdown">
      <button class="sa-topbar-profile" data-dropdown="saProfileDropdown">
        <?php component('avatar', ['name' => $_SESSION['user_name'] ?? 'U', 'size' => 'sm']); ?>
        <span class="sa-profile-name"><?= e($_SESSION['user_name'] ?? '') ?></span>
        <i class="ti ti-chevron-down"></i>
      </button>
      <div class="sa-dropdown-menu sa-dropdown-menu-right">
        <div class="sa-dropdown-header">
          <strong><?= e($_SESSION['user_name'] ?? '') ?></strong>
          <small><?= e($_SESSION['user_email'] ?? '') ?></small>
        </div>
        <a href="<?= url('/users/edit/' . ($_SESSION['user_id'] ?? '')) ?>" class="sa-dropdown-item">
          <i class="ti ti-user"></i> Mon profil
        </a>
        <div class="sa-dropdown-divider"></div>
        <a href="<?= url('/logout') ?>" class="sa-dropdown-item sa-text-danger">
          <i class="ti ti-logout"></i> Se déconnecter
        </a>
      </div>
    </div>

  </div>
</header>
