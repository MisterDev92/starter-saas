<?php
// Props: $id, $labels (array), $datasets (array), $title
$id       = $id       ?? 'chart-' . substr(md5(microtime()), 0, 6);
$labels   = $labels   ?? [];
$datasets = $datasets ?? [];
$title    = $title    ?? '';
?>
<div class="sa-chart-wrap">
  <?php if ($title !== ''): ?>
    <div class="sa-chart-title"><?= e($title) ?></div>
  <?php endif; ?>
  <canvas id="<?= e($id) ?>"></canvas>
</div>
<script>
(function() {
  var el = document.getElementById('<?= e($id) ?>');
  if (!el || typeof Chart === 'undefined') return;
  new Chart(el, {
    type: 'line',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: <?= json_encode($datasets) ?>
    },
    options: { responsive: true, plugins: { legend: { display: <?= count($datasets) > 1 ? 'true' : 'false' ?> }, title: { display: false } }, scales: { y: { beginAtZero: false } } }
  });
})();
</script>
