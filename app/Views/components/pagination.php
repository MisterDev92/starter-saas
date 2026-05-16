<?php
// Props: $current (int), $total (int), $base_url (string)
$current  = (int)($current  ?? 1);
$total    = (int)($total    ?? 1);
$base_url = $base_url ?? '';
if ($total <= 1) return;
?>
<nav class="sa-pagination" aria-label="Pagination">
  <a href="<?= url($base_url . '?page=' . max(1, $current - 1)) ?>"
     class="sa-page-btn <?= $current <= 1 ? 'disabled' : '' ?>"
     aria-label="Page précédente">
    <i class="ti ti-chevron-left"></i>
  </a>

  <?php
  $range = 2;
  $start = max(1, $current - $range);
  $end   = min($total, $current + $range);
  if ($start > 1): ?>
    <a href="<?= url($base_url . '?page=1') ?>" class="sa-page-btn">1</a>
    <?php if ($start > 2): ?><span class="sa-page-ellipsis">…</span><?php endif; ?>
  <?php endif; ?>

  <?php for ($i = $start; $i <= $end; $i++): ?>
    <a href="<?= url($base_url . '?page=' . $i) ?>"
       class="sa-page-btn <?= $i === $current ? 'active' : '' ?>"><?= $i ?></a>
  <?php endfor; ?>

  <?php if ($end < $total): ?>
    <?php if ($end < $total - 1): ?><span class="sa-page-ellipsis">…</span><?php endif; ?>
    <a href="<?= url($base_url . '?page=' . $total) ?>" class="sa-page-btn"><?= $total ?></a>
  <?php endif; ?>

  <a href="<?= url($base_url . '?page=' . min($total, $current + 1)) ?>"
     class="sa-page-btn <?= $current >= $total ? 'disabled' : '' ?>"
     aria-label="Page suivante">
    <i class="ti ti-chevron-right"></i>
  </a>
</nav>
