<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 — Page non trouvée</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Inter', sans-serif; background: #f1f5f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
    .box { text-align: center; padding: 48px; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,.08); max-width: 420px; }
    .code { font-size: 5rem; font-weight: 700; color: #4f46e5; line-height: 1; }
    h1 { font-size: 1.25rem; color: #1e293b; margin: 12px 0 8px; }
    p { color: #64748b; font-size: .95rem; margin: 0 0 24px; }
    a { display: inline-block; padding: 10px 24px; background: #4f46e5; color: #fff; text-decoration: none; border-radius: 8px; font-weight: 600; }
  </style>
</head>
<body>
<div class="box">
  <div class="code">404</div>
  <h1>Page non trouvée</h1>
  <p>La page que vous cherchez n'existe pas ou a été déplacée.</p>
  <a href="<?= url('/dashboard') ?>">Retour au dashboard</a>
</div>
</body>
</html>
