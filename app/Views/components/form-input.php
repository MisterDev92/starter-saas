<?php
// Props: $name, $label, $type, $value, $placeholder, $error, $required
$name        = $name        ?? '';
$label       = $label       ?? '';
$type        = $type        ?? 'text';
$value       = $value       ?? '';
$placeholder = $placeholder ?? '';
$error       = $error       ?? '';
$required    = $required    ?? false;
?>
<div class="sa-form-group <?= $error ? 'sa-has-error' : '' ?>">
  <?php if ($label !== ''): ?>
    <label class="sa-label" for="<?= e($name) ?>">
      <?= e($label) ?>
      <?php if ($required): ?><span class="sa-required" aria-hidden="true">*</span><?php endif; ?>
    </label>
  <?php endif; ?>
  <input
    type="<?= e($type) ?>"
    id="<?= e($name) ?>"
    name="<?= e($name) ?>"
    value="<?= e($value) ?>"
    placeholder="<?= e($placeholder) ?>"
    class="sa-input <?= $error ? 'sa-input-error' : '' ?>"
    <?= $required ? 'required' : '' ?>
  >
  <?php if ($error !== ''): ?>
    <span class="sa-field-error"><?= e($error) ?></span>
  <?php endif; ?>
</div>
