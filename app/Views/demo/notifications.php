<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives.', 'dismissible' => true]); ?>

<div class="sa-card">
  <div class="sa-card-header">
    <h3 class="sa-card-title">
      Notifications
      <?php if ($unread_count > 0): ?>
        <span class="sa-badge sa-badge-primary sa-ml-sm"><?= $unread_count ?> non lues</span>
      <?php endif; ?>
    </h3>
    <div class="sa-card-actions">
      <div class="sa-btn-group" id="notifFilters">
        <button class="sa-btn sa-btn-sm sa-btn-outline active" data-notif-filter="all">Toutes</button>
        <button class="sa-btn sa-btn-sm sa-btn-outline" data-notif-filter="unread">Non lues</button>
        <button class="sa-btn sa-btn-sm sa-btn-outline" data-notif-filter="system">Système</button>
        <button class="sa-btn sa-btn-sm sa-btn-outline" data-notif-filter="user">Utilisateurs</button>
      </div>
      <button class="sa-btn sa-btn-sm sa-btn-ghost" data-confirm="Tout marquer comme lu (démo) ?">
        <i class="ti ti-checks"></i> Tout lire
      </button>
    </div>
  </div>
  <div class="sa-card-body sa-p-0">
    <?php foreach ($notifications as $notif): ?>
      <div class="sa-notif-row <?= $notif['read'] ? '' : 'sa-unread' ?>"
           data-notif-type="<?= e($notif['type']) ?>">
        <span class="sa-notif-icon sa-text-<?= e($notif['color'] ?? 'info') ?>">
          <i class="ti ti-<?= e($notif['icon'] ?? 'bell') ?>"></i>
        </span>
        <div class="sa-notif-content">
          <div class="sa-notif-title">
            <?= e($notif['title']) ?>
            <?php if (!$notif['read']): ?>
              <span class="sa-notif-dot"></span>
            <?php endif; ?>
          </div>
          <div class="sa-notif-text"><?= e($notif['text']) ?></div>
          <div class="sa-notif-meta">
            <span class="sa-text-muted"><?= e(format_date($notif['date'], 'd/m/Y H:i')) ?></span>
            <?php component('badge', ['label' => $notif['type'], 'type' => $notif['type'] === 'system' ? 'info' : 'user']); ?>
          </div>
        </div>
        <?php if (!$notif['read']): ?>
          <button class="sa-btn sa-btn-xs sa-btn-ghost sa-notif-mark-read" title="Marquer comme lu">
            <i class="ti ti-check"></i>
          </button>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</div>
