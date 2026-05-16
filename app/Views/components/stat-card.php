<?php
// Props: $label, $value, $delta, $delta_type (up|down), $icon, $color (primary|success|danger|warning|info)
$label      = $label      ?? '';
$value      = $value      ?? '0';
$delta      = $delta      ?? '';
$delta_type = $delta_type ?? 'up';
$icon       = $icon       ?? 'chart-bar';
$color      = $color      ?? 'primary';
?>
<div class="sa-stat-card sa-card">
  <div class="sa-stat-card-body">
    <div class="sa-stat-card-info">
      <div class="sa-stat-label"><?= e($label) ?></div>
      <div class="sa-stat-value"><?= e($value) ?></div>
      <?php if ($delta !== ''): ?>
        <div class="sa-stat-delta sa-stat-delta-<?= e($delta_type) ?>">
          <i class="ti ti-trend-<?= $delta_type === 'up' ? 'up' : 'down' ?>-2"></i>
          <?= e($delta) ?>
        </div>
      <?php endif; ?>
    </div>
    <div class="sa-stat-icon sa-stat-icon-<?= e($color) ?>">
      <i class="ti ti-<?= e($icon) ?>"></i>
    </div>
  </div>
</div>
