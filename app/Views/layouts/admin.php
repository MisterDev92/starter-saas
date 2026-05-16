<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? e($page_title) . ' — ' : '' ?><?= e(APP_NAME) ?></title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">

  <!-- Tabler CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.47.0/tabler-icons.min.css">
  <!-- Highlight.js -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/github.min.css" id="hljs-theme">

  <!-- Chart.js (en head pour que les composants chart-*.php puissent l'utiliser) -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  <!-- Highlight.js JS (nécessaire avant le contenu pour hljs.highlightElement) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

  <!-- Custom -->
  <link rel="stylesheet" href="<?= asset('css/admin-custom.css') ?>">
</head>
<body class="sa-body">

<div class="sa-layout">

  <?php component('sidebar', [
    'menu_items' => $menu_items ?? [],
    'user'       => [
      'name'  => $_SESSION['user_name']  ?? 'Invité',
      'email' => $_SESSION['user_email'] ?? '',
      'role'  => $_SESSION['user_role']  ?? 'user',
    ],
  ]); ?>

  <div class="sa-main-wrapper">

    <?php component('topbar', [
      'page_title'    => $page_title    ?? '',
      'notifications' => $notifications ?? [],
    ]); ?>

    <main class="sa-main">

      <?php if (isset($breadcrumb) && count($breadcrumb)): ?>
        <?php component('breadcrumb', ['items' => $breadcrumb]); ?>
      <?php endif; ?>

      <?php
        // Flash message automatique depuis session
        $flash = $flash ?? null;
        if (!$flash && !empty($_SESSION['_flash'])) {
          $flash = $_SESSION['_flash'];
          unset($_SESSION['_flash']);
        }
        if ($flash):
      ?>
        <?php component('alert', ['type' => $flash['type'], 'message' => $flash['message'], 'dismissible' => true]); ?>
      <?php endif; ?>

      <?= $content ?>

    </main>

  </div><!-- .sa-main-wrapper -->
</div><!-- .sa-layout -->

<!-- Tabler JS -->
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
<!-- Custom -->
<script src="<?= asset('js/admin-custom.js') ?>"></script>


</body>
</html>
