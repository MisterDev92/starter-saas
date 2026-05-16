<?php
// Props: $items [['label' => '', 'url' => ''], ...]
$items = $items ?? [];
if (empty($items)) return;
?>
<nav class="sa-breadcrumb" aria-label="Fil d'ariane">
  <ol class="sa-breadcrumb-list">
    <li class="sa-breadcrumb-item">
      <a href="<?= url('/dashboard') ?>"><i class="ti ti-home"></i></a>
    </li>
    <?php foreach ($items as $i => $item): ?>
      <li class="sa-breadcrumb-item <?= $i === count($items) - 1 ? 'active' : '' ?>">
        <?php if (!empty($item['url']) && $i < count($items) - 1): ?>
          <a href="<?= url($item['url']) ?>"><?= e($item['label']) ?></a>
        <?php else: ?>
          <span><?= e($item['label']) ?></span>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>
