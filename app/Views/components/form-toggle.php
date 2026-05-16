<?php
// Props: $name, $label, $checked (bool), $description
$name        = $name        ?? '';
$label       = $label       ?? '';
$checked     = $checked     ?? false;
$description = $description ?? '';
?>
<div class="sa-form-group sa-toggle-group">
  <label class="sa-toggle-label">
    <input type="checkbox"
           id="<?= e($name) ?>"
           name="<?= e($name) ?>"
           value="1"
           class="sa-toggle-input"
           <?= $checked ? 'checked' : '' ?>>
    <span class="sa-toggle-switch"></span>
    <span class="sa-toggle-text"><?= e($label) ?></span>
  </label>
  <?php if ($description !== ''): ?>
    <p class="sa-toggle-description"><?= e($description) ?></p>
  <?php endif; ?>
</div>
