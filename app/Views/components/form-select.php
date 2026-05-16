<?php
// Props: $name, $label, $options [value => label], $selected, $error
$name     = $name     ?? '';
$label    = $label    ?? '';
$options  = $options  ?? [];
$selected = $selected ?? '';
$error    = $error    ?? '';
?>
<div class="sa-form-group <?= $error ? 'sa-has-error' : '' ?>">
  <?php if ($label !== ''): ?>
    <label class="sa-label" for="<?= e($name) ?>"><?= e($label) ?></label>
  <?php endif; ?>
  <select id="<?= e($name) ?>" name="<?= e($name) ?>" class="sa-select <?= $error ? 'sa-input-error' : '' ?>">
    <?php foreach ($options as $val => $lbl): ?>
      <option value="<?= e($val) ?>" <?= (string)$val === (string)$selected ? 'selected' : '' ?>>
        <?= e($lbl) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <?php if ($error !== ''): ?>
    <span class="sa-field-error"><?= e($error) ?></span>
  <?php endif; ?>
</div>
