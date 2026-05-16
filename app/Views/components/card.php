<?php
// Props: $title, $footer, $class
// Le contenu est passé via ob + component() ou directement en HTML dans $slot
$title  = $title  ?? '';
$footer = $footer ?? '';
$class  = $class  ?? '';
?>
<div class="sa-card <?= e($class) ?>">
  <?php if ($title !== ''): ?>
    <div class="sa-card-header">
      <h3 class="sa-card-title"><?= e($title) ?></h3>
    </div>
  <?php endif; ?>
  <div class="sa-card-body">
    <?= $slot ?? '' ?>
  </div>
  <?php if ($footer !== ''): ?>
    <div class="sa-card-footer"><?= $footer ?></div>
  <?php endif; ?>
</div>
