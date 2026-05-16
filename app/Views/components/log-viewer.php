<?php
// Props: $logs (tableau de log-line props)
$logs = $logs ?? [];
?>
<div class="sa-log-viewer">
  <div class="sa-log-toolbar">
    <div class="sa-log-filters">
      <button class="sa-log-filter active" data-log-filter="ALL">Tous</button>
      <button class="sa-log-filter" data-log-filter="INFO">INFO</button>
      <button class="sa-log-filter" data-log-filter="WARN">WARN</button>
      <button class="sa-log-filter" data-log-filter="ERROR">ERROR</button>
    </div>
    <button class="sa-btn sa-btn-sm sa-btn-outline" data-copy-target=".sa-log-body" title="Copier les logs">
      <i class="ti ti-copy"></i> Copier
    </button>
  </div>
  <div class="sa-log-body">
    <?php foreach ($logs as $log): ?>
      <?php component('log-line', $log); ?>
    <?php endforeach; ?>
    <?php if (empty($logs)): ?>
      <div class="sa-log-empty">Aucun log disponible.</div>
    <?php endif; ?>
  </div>
</div>
