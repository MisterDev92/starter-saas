<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? e($page_title) . ' — ' : '' ?><?= e(APP_NAME) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css">
  <link rel="stylesheet" href="<?= asset('css/admin-custom.css') ?>">
</head>
<body class="sa-auth-body">

<div class="sa-auth-wrapper">

  <div class="sa-auth-header">
    <a href="<?= url('/') ?>" class="sa-auth-logo">
      <span class="sa-logo-icon"><i class="ti ti-hexagon-letter-s"></i></span>
      <span class="sa-logo-name"><?= e(APP_NAME) ?></span>
    </a>
  </div>

  <div class="sa-auth-card">
    <?= $content ?>
  </div>

  <p class="sa-auth-footer">
    &copy; <?= date('Y') ?> <?= e(APP_NAME) ?>
  </p>

</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
<script src="<?= asset('js/admin-custom.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  initDismissAlerts();
  initFormValidation();
});
</script>
</body>
</html>
