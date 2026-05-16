<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives.', 'dismissible' => true]); ?>

<div class="sa-grid sa-grid-4 sa-mb-lg">
  <?php foreach ($stats as $s): ?><?php component('stat-card', $s); ?><?php endforeach; ?>
</div>

<!-- Trafic area -->
<div class="sa-card sa-mb-lg">
  <div class="sa-card-header"><h3 class="sa-card-title">Trafic — 30 derniers jours</h3></div>
  <div class="sa-card-body">
    <?php component('chart-area', [
      'id'       => 'chartTraffic',
      'labels'   => $chart_traffic_labels,
      'datasets' => [[
        'label'           => 'Visiteurs',
        'data'            => $chart_traffic,
        'borderColor'     => '#4f46e5',
        'backgroundColor' => 'rgba(79,70,229,0.10)',
        'tension'         => 0.4,
        'fill'            => true,
      ]],
    ]); ?>
  </div>
</div>

<div class="sa-grid sa-grid-3 sa-mb-lg">
  <div class="sa-card">
    <div class="sa-card-header"><h3 class="sa-card-title">Sources de trafic</h3></div>
    <div class="sa-card-body">
      <?php component('chart-bar', [
        'id'       => 'chartSources',
        'labels'   => $chart_sources_labels,
        'datasets' => [[
          'label'           => 'Visiteurs',
          'data'            => $chart_sources,
          'backgroundColor' => '#4f46e5',
        ]],
      ]); ?>
    </div>
  </div>
  <div class="sa-card">
    <div class="sa-card-header"><h3 class="sa-card-title">Appareils</h3></div>
    <div class="sa-card-body">
      <?php component('chart-donut', [
        'id'     => 'chartDevices',
        'labels' => $chart_devices_labels,
        'values' => $chart_devices,
        'colors' => ['#4f46e5','#22c55e','#f59e0b'],
      ]); ?>
    </div>
  </div>
  <div class="sa-card">
    <div class="sa-card-header"><h3 class="sa-card-title">Pages populaires</h3></div>
    <div class="sa-card-body sa-p-0">
      <table class="sa-table sa-table-sm">
        <thead><tr><th>Page</th><th>Vues</th><th>Tps moy.</th></tr></thead>
        <tbody>
          <?php foreach ($top_pages as $p): ?>
            <tr>
              <td><code><?= e($p['url']) ?></code></td>
              <td><?= number_format($p['views'], 0, ',', ' ') ?></td>
              <td><?= e($p['time']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
