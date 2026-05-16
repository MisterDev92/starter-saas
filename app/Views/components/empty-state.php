<?php
// Props: $message, $icon, $action_label, $action_url
$message      = $message      ?? 'Aucun élément trouvé.';
$icon         = $icon         ?? 'inbox';
$action_label = $action_label ?? '';
$action_url   = $action_url   ?? '';
?>
<div class="sa-empty-state">
  <div class="sa-empty-icon"><i class="ti ti-<?= e($icon) ?>"></i></div>
  <p class="sa-empty-message"><?= e($message) ?></p>
  <?php if ($action_label && $action_url): ?>
    <a href="<?= url($action_url) ?>" class="sa-btn sa-btn-primary"><?= e($action_label) ?></a>
  <?php endif; ?>
</div>
