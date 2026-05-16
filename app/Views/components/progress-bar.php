<?php
// Props: $label, $value (0-100), $color
$label = $label ?? '';
$value = max(0, min(100, (int)($value ?? 0)));
$color = $color ?? 'primary';
?>
<div class="sa-progress-wrap">
  <?php if ($label !== ''): ?>
    <div class="sa-progress-header">
      <span class="sa-progress-label"><?= e($label) ?></span>
      <span class="sa-progress-percent"><?= $value ?>%</span>
    </div>
  <?php endif; ?>
  <div class="sa-progress">
    <div class="sa-progress-bar sa-bg-<?= e($color) ?>" style="width:<?= $value ?>%" role="progressbar" aria-valuenow="<?= $value ?>" aria-valuemin="0" aria-valuemax="100"></div>
  </div>
</div>
