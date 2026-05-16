<?php
// Props: $timestamp, $level (INFO|WARN|ERROR), $message, $context
$timestamp = $timestamp ?? '';
$level     = strtoupper($level ?? 'INFO');
$message   = $message   ?? '';
$context   = $context   ?? '';
$levelMap  = ['INFO' => 'info', 'WARN' => 'warning', 'ERROR' => 'danger', 'DEBUG' => 'muted'];
$cls       = $levelMap[$level] ?? 'muted';
?>
<div class="sa-log-line sa-log-<?= e(strtolower($level)) ?>" data-log-level="<?= e($level) ?>">
  <span class="sa-log-time"><?= e($timestamp) ?></span>
  <span class="sa-log-badge sa-badge sa-badge-<?= $cls ?>"><?= e($level) ?></span>
  <span class="sa-log-msg"><?= e($message) ?></span>
  <?php if ($context !== ''): ?>
    <span class="sa-log-ctx"><?= e($context) ?></span>
  <?php endif; ?>
</div>
