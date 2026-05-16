<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives.', 'dismissible' => true]); ?>

<div class="sa-grid sa-grid-4 sa-mb-lg">
  <?php component('stat-card', ['label' => 'Total encaissé (mois)', 'value' => $total_month, 'icon' => 'cash',          'color' => 'success', 'delta' => '+18%', 'delta_type' => 'up']); ?>
  <?php component('stat-card', ['label' => 'Factures en attente',  'value' => '3',          'icon' => 'clock',          'color' => 'warning', 'delta' => '',      'delta_type' => 'up']); ?>
  <?php component('stat-card', ['label' => 'Paiements échoués',    'value' => '2',          'icon' => 'alert-circle',   'color' => 'danger',  'delta' => '',      'delta_type' => 'up']); ?>
  <?php component('stat-card', ['label' => 'Remboursements',       'value' => '1',          'icon' => 'arrow-back-up',  'color' => 'info',    'delta' => '',      'delta_type' => 'down']); ?>
</div>

<div class="sa-card">
  <div class="sa-card-header">
    <h3 class="sa-card-title">Factures</h3>
    <div class="sa-card-actions">
      <?php component('search-bar', ['placeholder' => 'Rechercher…', 'target' => '#invoicesTable tbody']); ?>
    </div>
  </div>
  <div class="sa-card-body sa-p-0">
    <table class="sa-table" id="invoicesTable">
      <thead>
        <tr>
          <th>N° Facture</th><th>Client</th><th>Montant</th><th>Statut</th><th>Date</th><th class="sa-th-actions">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $statusTypes = ['paid' => 'active', 'pending' => 'warning', 'failed' => 'danger', 'refunded' => 'info'];
        $statusLabels = ['paid' => 'Payée', 'pending' => 'En attente', 'failed' => 'Échouée', 'refunded' => 'Remboursée'];
        foreach ($invoices as $inv):
        ?>
          <tr>
            <td><code><?= e($inv['id']) ?></code></td>
            <td><?= e($inv['client']) ?></td>
            <td><strong><?= e($inv['amount']) ?></strong></td>
            <td><?php component('badge', ['label' => $statusLabels[$inv['status']] ?? $inv['status'], 'type' => $statusTypes[$inv['status']] ?? 'info']); ?></td>
            <td class="sa-text-muted"><?= e(format_date($inv['date'])) ?></td>
            <td class="sa-td-actions">
              <button class="sa-btn sa-btn-xs sa-btn-outline" title="Télécharger (démo)"><i class="ti ti-download"></i></button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
