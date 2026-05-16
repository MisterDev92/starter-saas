<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives.', 'dismissible' => true]); ?>

<div class="sa-grid sa-grid-4 sa-mb-lg">
  <?php foreach ($stats as $s): ?><?php component('stat-card', $s); ?><?php endforeach; ?>
</div>

<div class="sa-card">
  <div class="sa-card-header">
    <h3 class="sa-card-title">Tickets support</h3>
    <?php component('search-bar', ['placeholder' => 'Rechercher un ticket…', 'target' => '#ticketsTable tbody']); ?>
  </div>
  <div class="sa-card-body sa-p-0">
    <table class="sa-table" id="ticketsTable">
      <thead>
        <tr><th>ID</th><th>Sujet</th><th>Utilisateur</th><th>Priorité</th><th>Statut</th><th>Date</th><th class="sa-th-actions">Action</th></tr>
      </thead>
      <tbody>
        <?php
        $prioTypes  = ['low' => 'info',    'medium' => 'warning', 'high' => 'danger', 'urgent' => 'danger'];
        $prioLabels = ['low' => 'Basse',   'medium' => 'Moyenne', 'high' => 'Haute',  'urgent' => 'Urgente'];
        $statTypes  = ['open' => 'warning','in_progress' => 'primary', 'resolved' => 'active', 'closed' => 'inactive'];
        $statLabels = ['open' => 'Ouvert', 'in_progress' => 'En cours', 'resolved' => 'Résolu', 'closed' => 'Fermé'];
        foreach ($tickets as $ticket):
        ?>
          <tr>
            <td><code><?= e($ticket['id']) ?></code></td>
            <td><?= e($ticket['subject']) ?></td>
            <td><?= e($ticket['user']) ?></td>
            <td><?php component('badge', ['label' => $prioLabels[$ticket['priority']] ?? $ticket['priority'], 'type' => $prioTypes[$ticket['priority']] ?? 'info']); ?></td>
            <td><?php component('badge', ['label' => $statLabels[$ticket['status']] ?? $ticket['status'],   'type' => $statTypes[$ticket['status']] ?? 'info']); ?></td>
            <td class="sa-text-muted"><?= e(format_date($ticket['date'])) ?></td>
            <td class="sa-td-actions">
              <button class="sa-btn sa-btn-xs sa-btn-outline"
                      data-modal-target="#ticketModal"
                      data-ticket-subject="<?= e($ticket['subject']) ?>"
                      title="Voir le ticket">
                <i class="ti ti-eye"></i>
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal ticket -->
<?php ob_start(); ?>
  <p class="sa-text-muted sa-mb-md">Fil de réponses (démonstration)</p>
  <?php
  $fakeThread = [
    ['author' => 'Alice M.',    'role' => 'user',    'time' => 'Il y a 2h',   'msg' => 'Bonjour, je n\'arrive pas à me connecter depuis ce matin.'],
    ['author' => 'Support',     'role' => 'support', 'time' => 'Il y a 1h45', 'msg' => 'Bonjour Alice, pouvez-vous vider le cache de votre navigateur et réessayer ?'],
    ['author' => 'Alice M.',    'role' => 'user',    'time' => 'Il y a 1h30', 'msg' => 'J\'ai essayé, ça ne fonctionne toujours pas.'],
    ['author' => 'Support',     'role' => 'support', 'time' => 'Il y a 1h',   'msg' => 'Je réinitialise votre session, essayez à nouveau dans quelques minutes.'],
  ];
  foreach ($fakeThread as $msg):
  ?>
    <div class="sa-thread-msg sa-thread-<?= e($msg['role']) ?>">
      <div class="sa-thread-header">
        <strong><?= e($msg['author']) ?></strong>
        <small class="sa-text-muted"><?= e($msg['time']) ?></small>
      </div>
      <div class="sa-thread-body"><?= e($msg['msg']) ?></div>
    </div>
  <?php endforeach; ?>
  <hr class="sa-divider">
  <textarea class="sa-input sa-textarea" rows="3" placeholder="Écrire une réponse…"></textarea>
  <div class="sa-form-actions sa-mt-sm">
    <button class="sa-btn sa-btn-primary" data-confirm="Envoyer (démo) ?">Envoyer la réponse</button>
    <button class="sa-btn sa-btn-ghost" data-confirm="Marquer résolu (démo) ?">Marquer comme résolu</button>
  </div>
<?php $modalContent = ob_get_clean();

component('modal', [
  'id'    => 'ticketModal',
  'title' => 'Ticket support',
  'size'  => 'lg',
  'slot'  => $modalContent,
]);
?>
