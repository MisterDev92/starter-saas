<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives.', 'dismissible' => true]); ?>

<!-- Stats -->
<div class="sa-grid sa-grid-4 sa-mb-lg">
  <?php foreach ($stats as $s): ?>
    <?php component('stat-card', $s); ?>
  <?php endforeach; ?>
</div>

<!-- Charts -->
<div class="sa-grid sa-grid-2 sa-mb-lg">
  <div class="sa-card">
    <div class="sa-card-header"><h3 class="sa-card-title">Revenus 12 mois</h3></div>
    <div class="sa-card-body">
      <?php component('chart-line', [
        'id'       => 'chartRevenue',
        'labels'   => $chart_labels,
        'datasets' => [[
          'label'           => 'MRR (€)',
          'data'            => $chart_revenue,
          'borderColor'     => '#4f46e5',
          'backgroundColor' => 'rgba(79,70,229,0.08)',
          'fill'            => true,
          'tension'         => 0.4,
        ]],
      ]); ?>
    </div>
  </div>
  <div class="sa-card">
    <div class="sa-card-header"><h3 class="sa-card-title">Répartition des plans</h3></div>
    <div class="sa-card-body">
      <?php component('chart-donut', [
        'id'     => 'chartPlans',
        'labels' => $chart_plan_labels,
        'values' => $chart_plans,
        'colors' => ['#4f46e5','#22c55e','#f59e0b'],
      ]); ?>
    </div>
  </div>
</div>

<!-- Recent users & Activities -->
<div class="sa-grid sa-grid-2">
  <div class="sa-card">
    <div class="sa-card-header">
      <h3 class="sa-card-title">Derniers inscrits</h3>
      <a href="<?= url('/demo/users') ?>" class="sa-link sa-text-sm">Voir tous</a>
    </div>
    <div class="sa-card-body sa-p-0">
      <table class="sa-table">
        <thead><tr><th>Utilisateur</th><th>Plan</th><th>Date</th></tr></thead>
        <tbody>
          <?php foreach ($recent_users as $u): ?>
            <tr>
              <td><div class="sa-user-cell"><?php component('avatar', ['name' => $u['name'], 'size' => 'sm']); ?><span><?= e($u['name']) ?></span></div></td>
              <td><?php component('badge', ['label' => $u['plan'], 'type' => 'primary']); ?></td>
              <td class="sa-text-muted"><?= e(format_date($u['created_at'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="sa-card">
    <div class="sa-card-header"><h3 class="sa-card-title">Activité récente</h3></div>
    <div class="sa-card-body">
      <?php component('timeline', ['events' => array_map(fn($a) => [
        'icon'        => $a['icon'],
        'color'       => $a['color'],
        'title'       => $a['title'],
        'description' => $a['desc'],
        'date'        => $a['date'],
      ], $activities)]); ?>
    </div>
  </div>
</div>
