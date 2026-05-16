<?php
// Props: $errors (array de messages)
$errors = $errors ?? [];
if (empty($errors)) return;
?>
<div class="sa-alert sa-alert-error" role="alert">
  <i class="ti ti-alert-circle sa-alert-icon"></i>
  <div>
    <strong>Veuillez corriger les erreurs suivantes :</strong>
    <ul class="sa-error-list">
      <?php foreach ($errors as $err): ?>
        <li><?= e($err) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>
