<?php
// Props: $id, $base_url, $can_delete (bool)
$id         = $id         ?? 0;
$base_url   = $base_url   ?? '';
$can_delete = $can_delete ?? true;
?>
<div class="sa-table-actions-btns">
  <a href="<?= url($base_url . '/' . $id) ?>" class="sa-btn sa-btn-xs sa-btn-outline" title="Voir">
    <i class="ti ti-eye"></i>
  </a>
  <a href="<?= url($base_url . '/edit/' . $id) ?>" class="sa-btn sa-btn-xs sa-btn-outline-primary" title="Modifier">
    <i class="ti ti-pencil"></i>
  </a>
  <?php if ($can_delete): ?>
    <form method="POST" action="<?= url($base_url . '/delete/' . $id) ?>" class="sa-inline-form" data-confirm="Supprimer cet élément ?">
      <?= csrf_field() ?>
      <button type="submit" class="sa-btn sa-btn-xs sa-btn-outline-danger" title="Supprimer">
        <i class="ti ti-trash"></i>
      </button>
    </form>
  <?php endif; ?>
</div>
