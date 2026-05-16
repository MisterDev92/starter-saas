<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — données fictives.', 'dismissible' => true]); ?>

<div class="sa-card">
  <div class="sa-card-header">
    <h3 class="sa-card-title">Utilisateurs (<?= count($users) ?>)</h3>
    <div class="sa-card-actions">
      <?php component('search-bar', ['placeholder' => 'Rechercher…', 'target' => '#demoUsersTable tbody']); ?>
    </div>
  </div>

  <!-- Filtres -->
  <div class="sa-card-toolbar">
    <button class="sa-filter-btn active" data-filter-col="3" data-filter-val="">Tous</button>
    <button class="sa-filter-btn" data-filter-col="3" data-filter-val="admin">Admins</button>
    <button class="sa-filter-btn" data-filter-col="3" data-filter-val="user">Users</button>
    <button class="sa-filter-btn" data-filter-col="4" data-filter-val="Inactif">Inactifs</button>
  </div>

  <div class="sa-card-body sa-p-0">
    <div class="sa-table-wrapper">
      <table class="sa-table" id="demoUsersTable">
        <thead>
          <tr>
            <th>Utilisateur</th>
            <th>Email</th>
            <th>Plan</th>
            <th>Rôle</th>
            <th>Statut</th>
            <th>Inscription</th>
            <th class="sa-th-actions">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <tr>
              <td><div class="sa-user-cell"><?php component('avatar', ['name' => $u['name'], 'size' => 'sm']); ?><span><?= e($u['name']) ?></span></div></td>
              <td><?= e($u['email']) ?></td>
              <td><?php component('badge', ['label' => $u['plan'], 'type' => $u['plan'] === 'Enterprise' ? 'warning' : ($u['plan'] === 'Pro' ? 'primary' : 'info')]); ?></td>
              <td><?php component('badge', ['label' => $u['role'], 'type' => $u['role'] === 'admin' ? 'admin' : 'user']); ?></td>
              <td><?php component('badge', ['label' => $u['is_active'] ? 'Actif' : 'Inactif', 'type' => $u['is_active'] ? 'active' : 'inactive']); ?></td>
              <td class="sa-text-muted"><?= e(format_date($u['created_at'])) ?></td>
              <td class="sa-td-actions">
                <a href="<?= url('/demo/user-detail') ?>" class="sa-btn sa-btn-xs sa-btn-outline" title="Voir"><i class="ti ti-eye"></i></a>
                <a href="#" class="sa-btn sa-btn-xs sa-btn-outline-primary" title="Éditer"><i class="ti ti-pencil"></i></a>
                <button class="sa-btn sa-btn-xs sa-btn-outline-danger" data-confirm="Supprimer cet utilisateur (démo) ?" title="Supprimer"><i class="ti ti-trash"></i></button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div class="sa-card-footer">
    <?php component('pagination', ['current' => 1, 'total' => 3, 'base_url' => '/demo/users']); ?>
  </div>
</div>
