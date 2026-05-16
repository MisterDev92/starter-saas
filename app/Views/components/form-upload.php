<?php
// Props: $name, $label, $accept, $max_size
$name     = $name     ?? 'file';
$label    = $label    ?? 'Fichier';
$accept   = $accept   ?? '';
$max_size = $max_size ?? '10 Mo';
?>
<div class="sa-form-group">
  <?php if ($label !== ''): ?>
    <label class="sa-label"><?= e($label) ?></label>
  <?php endif; ?>
  <div class="sa-upload-zone" data-upload-zone>
    <input type="file"
           id="<?= e($name) ?>"
           name="<?= e($name) ?>"
           class="sa-upload-input"
           <?= $accept ? 'accept="' . e($accept) . '"' : '' ?>>
    <div class="sa-upload-body">
      <i class="ti ti-cloud-upload sa-upload-icon"></i>
      <p class="sa-upload-text">Glissez un fichier ici ou <span class="sa-link">parcourir</span></p>
      <small class="sa-text-muted">Max <?= e($max_size) ?><?= $accept ? ' · ' . e($accept) : '' ?></small>
    </div>
    <div class="sa-upload-preview" style="display:none"></div>
  </div>
</div>
