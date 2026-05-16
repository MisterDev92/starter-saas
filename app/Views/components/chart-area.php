<?php
// Props: $id, $labels, $datasets, $title
$id       = $id       ?? 'chart-' . substr(md5(microtime()), 0, 6);
$labels   = $labels   ?? [];
$datasets = $datasets ?? [];
$title    = $title    ?? '';
// Forcer fill pour chaque dataset
foreach ($datasets as &$ds) {
    $ds['fill'] = $ds['fill'] ?? true;
    if (!isset($ds['backgroundColor'])) {
        $ds['backgroundColor'] = 'rgba(79,70,229,0.12)';
    }
}
unset($ds);
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
    options: { responsive: true, plugins: { legend: { display: <?= count($datasets) > 1 ? 'true' : 'false' ?> } }, scales: { y: { beginAtZero: true } }, elements: { line: { tension: 0.4 } } }
  });
})();
</script>
