<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives.', 'dismissible' => true]); ?>

<div class="sa-card">
  <div class="sa-card-header">
    <h3 class="sa-card-title">Logs système</h3>
    <div class="sa-card-actions">
      <button class="sa-btn sa-btn-sm sa-btn-outline-danger" data-confirm="Vider les logs (démo) ?">
        <i class="ti ti-trash"></i> Vider
      </button>
      <button class="sa-btn sa-btn-sm sa-btn-outline">
        <i class="ti ti-download"></i> Exporter
      </button>
    </div>
  </div>
  <div class="sa-card-body">
    <?php component('log-viewer', ['logs' => $logs]); ?>
  </div>
</div>
