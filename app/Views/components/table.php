<?php
// Props: $headers (array of strings), $rows (array of arrays), $actions (bool)
$headers = $headers ?? [];
$rows    = $rows    ?? [];
$actions = $actions ?? false;
?>
<div class="sa-table-wrapper">
  <table class="sa-table" data-sortable>
    <thead>
      <tr>
        <?php foreach ($headers as $header): ?>
          <th class="sa-th-sortable"><?= e($header) ?> <i class="ti ti-selector sa-sort-icon"></i></th>
        <?php endforeach; ?>
        <?php if ($actions): ?>
          <th class="sa-th-actions">Actions</th>
        <?php endif; ?>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($rows)): ?>
        <tr>
          <td colspan="<?= count($headers) + ($actions ? 1 : 0) ?>" class="sa-table-empty">
            Aucune donnée à afficher.
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($rows as $row): ?>
          <tr>
            <?php foreach ($row as $key => $cell): ?>
              <?php if ($key === '_actions') continue; ?>
              <td><?= $cell ?></td>
            <?php endforeach; ?>
            <?php if ($actions && isset($row['_actions'])): ?>
              <td class="sa-td-actions"><?= $row['_actions'] ?></td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
