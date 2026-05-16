<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= isset($page_title) ? e($page_title) . ' — ' : '' ?><?= e(APP_NAME) ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= asset('css/admin-custom.css') ?>">
  <style>
    @media print {
      .sa-print-no-print { display: none !important; }
      body { -webkit-print-color-adjust: exact; }
    }
  </style>
</head>
<body class="sa-print-body">

<header class="sa-print-header sa-print-no-print">
  <strong><?= e(APP_NAME) ?></strong>
  <span><?= isset($page_title) ? e($page_title) : '' ?></span>
  <span><?= date('d/m/Y H:i') ?></span>
  <button onclick="window.print()" class="sa-btn sa-btn-sm sa-btn-primary">Imprimer</button>
</header>

<div class="sa-print-content">
  <?= $content ?>
</div>

</body>
</html>
