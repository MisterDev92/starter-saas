<?php
// Props: $title, $open (bool)
$title = $title ?? '';
$open  = $open  ?? true;
$uid   = 'collapse-' . substr(md5($title . microtime()), 0, 8);
?>
<div class="sa-card sa-card-collapsible <?= $open ? 'open' : '' ?>" data-collapsible>
  <div class="sa-card-header sa-card-collapsible-header" data-toggle-collapse="<?= $uid ?>">
    <h3 class="sa-card-title"><?= e($title) ?></h3>
    <button class="sa-collapse-btn" aria-expanded="<?= $open ? 'true' : 'false' ?>">
      <i class="ti ti-chevron-down"></i>
    </button>
  </div>
  <div class="sa-card-body" id="<?= $uid ?>" <?= $open ? '' : 'style="display:none"' ?>>
    <?= $slot ?? '' ?>
  </div>
</div>
