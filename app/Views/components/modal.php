<?php
// Props: $id, $title, $size (sm|md|lg)
// Le contenu ($slot) est injecté dans le body
$id    = $id    ?? 'modal-' . substr(md5(microtime()), 0, 6);
$title = $title ?? '';
$size  = $size  ?? 'md';
?>
<div class="sa-modal-backdrop" id="<?= e($id) ?>" data-modal aria-hidden="true">
  <div class="sa-modal sa-modal-<?= e($size) ?>" role="dialog" aria-modal="true">
    <div class="sa-modal-header">
      <h4 class="sa-modal-title"><?= e($title) ?></h4>
      <button class="sa-modal-close" data-modal-close aria-label="Fermer">
        <i class="ti ti-x"></i>
      </button>
    </div>
    <div class="sa-modal-body">
      <?= $slot ?? '' ?>
    </div>
  </div>
</div>
