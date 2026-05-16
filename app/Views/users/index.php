<div class="sa-card">
  <div class="sa-card-header">
    <h3 class="sa-card-title">Utilisateurs</h3>
    <div class="sa-card-actions">
      <?php component('search-bar', ['placeholder' => 'Rechercher un utilisateur…', 'target' => '#usersTable tbody']); ?>
      <a href="<?= url('/users/create') ?>" class="sa-btn sa-btn-primary">
        <i class="ti ti-user-plus"></i> Nouvel utilisateur
      </a>
    </div>
  </div>
  <div class="sa-card-body sa-p-0">
    <?php if (empty($users)): ?>
      <?php component('empty-state', ['message' => 'Aucun utilisateur trouvé.', 'icon' => 'users', 'action_label' => 'Créer un utilisateur', 'action_url' => '/users/create']); ?>
    <?php else: ?>
      <div class="sa-table-wrapper">
        <table class="sa-table" id="usersTable">
          <thead>
            <tr>
              <th>Utilisateur</th>
              <th>Email</th>
              <th>Rôle</th>
              <th>Statut</th>
              <th>Inscrit le</th>
              <th class="sa-th-actions">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td>
                  <div class="sa-user-cell">
                    <?php component('avatar', ['name' => $user['name'], 'size' => 'sm']); ?>
                    <span><?= e($user['name']) ?></span>
                  </div>
                </td>
                <td><?= e($user['email']) ?></td>
                <td><?php component('badge', ['label' => $user['role'], 'type' => $user['role'] === 'admin' ? 'admin' : 'user']); ?></td>
                <td><?php component('badge', ['label' => $user['is_active'] ? 'Actif' : 'Inactif', 'type' => $user['is_active'] ? 'active' : 'inactive']); ?></td>
                <td><?= e(format_date($user['created_at'])) ?></td>
                <td class="sa-td-actions">
                  <a href="<?= url('/users/edit/' . $user['id']) ?>" class="sa-btn sa-btn-xs sa-btn-outline-primary" title="Modifier">
                    <i class="ti ti-pencil"></i>
                  </a>
                  <?php if ((int)$user['id'] !== (int)($_SESSION['user_id'] ?? 0)): ?>
                    <form method="POST" action="<?= url('/users/delete/' . $user['id']) ?>" class="sa-inline-form" data-confirm="Supprimer <?= e($user['name']) ?> ?">
                      <?= csrf_field() ?>
                      <button type="submit" class="sa-btn sa-btn-xs sa-btn-outline-danger" title="Supprimer">
                        <i class="ti ti-trash"></i>
                      </button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
