<?php
// Props: $events [['date', 'title', 'description', 'icon', 'color']]
$events = $events ?? [];
?>
<div class="sa-timeline">
  <?php foreach ($events as $event): ?>
    <div class="sa-timeline-item">
      <div class="sa-timeline-dot sa-bg-<?= e($event['color'] ?? 'primary') ?>">
        <i class="ti ti-<?= e($event['icon'] ?? 'point') ?>"></i>
      </div>
      <div class="sa-timeline-body">
        <div class="sa-timeline-header">
          <strong><?= e($event['title'] ?? '') ?></strong>
          <small class="sa-text-muted"><?= e(format_date($event['date'] ?? '', 'd/m/Y H:i')) ?></small>
        </div>
        <?php if (!empty($event['description'])): ?>
          <p class="sa-timeline-desc"><?= e($event['description']) ?></p>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>
