<?php
// Props: $placeholder, $target (sélecteur CSS du tableau/liste)
$placeholder = $placeholder ?? 'Rechercher…';
$target      = $target      ?? '.sa-table tbody';
?>
<div class="sa-search-bar">
  <i class="ti ti-search sa-search-icon"></i>
  <input type="text"
         class="sa-search-input"
         placeholder="<?= e($placeholder) ?>"
         data-search-target="<?= e($target) ?>"
         autocomplete="off">
</div>
