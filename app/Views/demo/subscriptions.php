<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives.', 'dismissible' => true]); ?>

<!-- Stats MRR -->
<div class="sa-grid sa-grid-4 sa-mb-lg">
  <?php foreach ($stats as $s): ?><?php component('stat-card', $s); ?><?php endforeach; ?>
</div>

<!-- Plans -->
<div class="sa-grid sa-grid-3 sa-mb-lg">
  <?php foreach ([
    ['name' => 'Free',       'price' => '0',   'color' => 'info',    'features' => ['1 utilisateur','5 projets','1 Go stockage','Support email']],
    ['name' => 'Pro',        'price' => '49',  'color' => 'primary', 'features' => ['10 utilisateurs','50 projets','50 Go stockage','Support prioritaire','API access'], 'popular' => true],
    ['name' => 'Enterprise', 'price' => '199', 'color' => 'warning', 'features' => ['Utilisateurs illimités','Projets illimités','500 Go stockage','SLA 99,9%','Onboarding dédié']],
  ] as $plan): ?>
    <div class="sa-card sa-plan-card <?= !empty($plan['popular']) ? 'sa-plan-popular' : '' ?>">
      <?php if (!empty($plan['popular'])): ?>
        <div class="sa-plan-badge">Populaire</div>
      <?php endif; ?>
      <div class="sa-card-body">
        <div class="sa-plan-name sa-text-<?= e($plan['color']) ?>"><?= e($plan['name']) ?></div>
        <div class="sa-plan-price"><span class="sa-plan-amount"><?= e($plan['price']) ?> €</span><small>/mois</small></div>
        <ul class="sa-plan-features">
          <?php foreach ($plan['features'] as $f): ?>
            <li><i class="ti ti-check sa-text-success"></i> <?= e($f) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- MRR chart + abonnés -->
<div class="sa-grid sa-grid-2">
  <div class="sa-card">
    <div class="sa-card-header"><h3 class="sa-card-title">Évolution MRR</h3></div>
    <div class="sa-card-body">
      <?php component('chart-area', [
        'id'       => 'chartMrr',
        'labels'   => $chart_mrr_labels,
        'datasets' => [['label' => 'MRR (€)', 'data' => $chart_mrr, 'borderColor' => '#4f46e5', 'tension' => 0.4]],
      ]); ?>
    </div>
  </div>
  <div class="sa-card">
    <div class="sa-card-header"><h3 class="sa-card-title">Abonnés actifs</h3></div>
    <div class="sa-card-body sa-p-0">
      <table class="sa-table">
        <thead><tr><th>Utilisateur</th><th>Plan</th><th>Montant</th><th>Statut</th><th>Renouvellement</th></tr></thead>
        <tbody>
          <?php foreach ($subscriptions as $sub): ?>
            <tr>
              <td><?= e($sub['user']) ?></td>
              <td><?php component('badge', ['label' => $sub['plan'], 'type' => 'primary']); ?></td>
              <td><?= e($sub['amount']) ?></td>
              <td><?php
                $st = ['active' => 'active', 'expired' => 'inactive', 'cancelled' => 'danger'];
                $sl = ['active' => 'Actif', 'expired' => 'Expiré', 'cancelled' => 'Annulé'];
                component('badge', ['label' => $sl[$sub['status']] ?? $sub['status'], 'type' => $st[$sub['status']] ?? 'info']);
              ?></td>
              <td class="sa-text-muted"><?= e(format_date($sub['renewal'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
