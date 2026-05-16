<?php
// Props: $label, $value, $color
$label = $label ?? '';
$value = $value ?? '0';
$color = $color ?? 'primary';
?>
<div class="sa-stat-mini sa-card">
  <div class="sa-stat-mini-value sa-text-<?= e($color) ?>"><?= e($value) ?></div>
  <div class="sa-stat-mini-label"><?= e($label) ?></div>
</div>
