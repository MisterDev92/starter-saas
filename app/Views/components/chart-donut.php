<?php
// Props: $id, $labels, $values, $colors, $title
$id     = $id     ?? 'chart-' . substr(md5(microtime()), 0, 6);
$labels = $labels ?? [];
$values = $values ?? [];
$colors = $colors ?? ['#4f46e5','#22c55e','#f59e0b','#ef4444','#3b82f6','#8b5cf6'];
$title  = $title  ?? '';
?>
<div class="sa-chart-wrap sa-chart-donut-wrap">
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
    type: 'doughnut',
    data: {
      labels: <?= json_encode($labels) ?>,
      datasets: [{ data: <?= json_encode($values) ?>, backgroundColor: <?= json_encode(array_slice($colors, 0, count($values))) ?>, borderWidth: 2 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
  });
})();
</script>
