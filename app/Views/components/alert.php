<?php
// Props: $type (info|success|error|warning), $message, $dismissible (bool)
$type        = $type        ?? 'info';
$message     = $message     ?? '';
$dismissible = $dismissible ?? false;

$icons = ['info' => 'info-circle', 'success' => 'circle-check', 'error' => 'alert-circle', 'warning' => 'alert-triangle'];
$icon  = $icons[$type] ?? 'info-circle';
?>
<div class="sa-alert sa-alert-<?= e($type) ?>" role="alert" data-alert>
  <i class="ti ti-<?= e($icon) ?> sa-alert-icon"></i>
  <span class="sa-alert-message"><?= e($message) ?></span>
  <?php if ($dismissible): ?>
    <button class="sa-alert-close" data-dismiss-alert aria-label="Fermer">
      <i class="ti ti-x"></i>
    </button>
  <?php endif; ?>
</div>
