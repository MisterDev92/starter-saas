<?php
// Props : $name, $label, $options [value => label], $selected [], $placeholder, $error, $required
$name        = $name        ?? '';
$label       = $label       ?? '';
$options     = $options     ?? [];
$selected    = $selected    ?? [];
$placeholder = $placeholder ?? 'Sélectionner…';
$error       = $error       ?? '';
$required    = $required    ?? false;
$uid         = 'ms-' . substr(md5($name . microtime()), 0, 6);
?>
<div class="sa-form-group <?= $error ? 'sa-has-error' : '' ?>">
  <?php if ($label !== ''): ?>
    <label class="sa-label" for="<?= e($uid) ?>">
      <?= e($label) ?>
      <?php if ($required): ?><span class="sa-required" aria-hidden="true">*</span><?php endif; ?>
    </label>
  <?php endif; ?>

  <div class="sa-multiselect <?= $error ? 'sa-input-error' : '' ?>"
       id="<?= e($uid) ?>"
       data-multiselect
       data-name="<?= e($name) ?>">

    <!-- Inputs cachés pour la soumission du formulaire -->
    <div class="sa-ms-hidden-inputs">
      <?php foreach ($selected as $val): ?>
        <input type="hidden" name="<?= e($name) ?>[]" value="<?= e($val) ?>">
      <?php endforeach; ?>
    </div>

    <!-- Zone de contrôle (ce que l'utilisateur voit) -->
    <div class="sa-ms-control" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox">
      <div class="sa-ms-tags">
        <?php foreach ($selected as $val): ?>
          <?php if (isset($options[$val])): ?>
            <span class="sa-ms-tag" data-ms-tag="<?= e($val) ?>">
              <?= e($options[$val]) ?>
              <button type="button" class="sa-ms-tag-remove" data-ms-remove="<?= e($val) ?>" aria-label="Retirer">
                <i class="ti ti-x"></i>
              </button>
            </span>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
      <?php if (empty($selected)): ?>
        <span class="sa-ms-placeholder"><?= e($placeholder) ?></span>
      <?php endif; ?>
      <i class="ti ti-chevron-down sa-ms-arrow"></i>
    </div>

    <!-- Dropdown -->
    <div class="sa-ms-dropdown" role="listbox" aria-multiselectable="true">

      <!-- Recherche -->
      <div class="sa-ms-search-wrap">
        <i class="ti ti-search sa-ms-search-icon"></i>
        <input type="text"
               class="sa-ms-search-input"
               placeholder="Rechercher…"
               autocomplete="off">
      </div>

      <!-- Options -->
      <div class="sa-ms-options">
        <?php foreach ($options as $val => $lbl): ?>
          <label class="sa-ms-option <?= in_array((string)$val, array_map('strval', $selected), true) ? 'checked' : '' ?>"
                 data-ms-label="<?= e(strtolower($lbl)) ?>">
            <input type="checkbox"
                   class="sa-ms-checkbox"
                   value="<?= e($val) ?>"
                   <?= in_array((string)$val, array_map('strval', $selected), true) ? 'checked' : '' ?>>
            <span class="sa-ms-check-icon"><i class="ti ti-check"></i></span>
            <span class="sa-ms-option-label"><?= e($lbl) ?></span>
          </label>
        <?php endforeach; ?>
        <div class="sa-ms-no-results" style="display:none">Aucun résultat</div>
      </div>

      <!-- Actions -->
      <div class="sa-ms-footer">
        <button type="button" class="sa-ms-btn" data-ms-all>Tout sélectionner</button>
        <button type="button" class="sa-ms-btn sa-ms-btn-clear" data-ms-clear>Effacer</button>
      </div>

    </div><!-- .sa-ms-dropdown -->
  </div><!-- .sa-multiselect -->

  <?php if ($error !== ''): ?>
    <span class="sa-field-error"><?= e($error) ?></span>
  <?php endif; ?>
</div>
