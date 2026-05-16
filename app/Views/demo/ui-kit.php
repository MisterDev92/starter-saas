<?php component('alert', ['type' => 'info', 'message' => 'Mode démonstration — Bibliothèque de composants.', 'dismissible' => true]); ?>

<div class="sa-grid sa-grid-uikit">

  <!-- Ancres latérales -->
  <nav class="sa-uikit-nav">
    <ul>
      <?php foreach (['badges','alerts','avatars','buttons','cards','stats','forms','multiselect','charts','tables','logs','modals','timeline','pagination'] as $s): ?>
        <li><a href="#uikit-<?= $s ?>"><?= ucfirst($s) ?></a></li>
      <?php endforeach; ?>
    </ul>
  </nav>

  <div class="sa-uikit-content">

    <!-- Badges -->
    <section id="uikit-badges" class="sa-uikit-section">
      <h2>Badges</h2>
      <?php foreach (['primary','success','danger','warning','info','active','inactive','admin','user','pending'] as $t): ?>
        <?php component('badge', ['label' => $t, 'type' => $t]); ?>
      <?php endforeach; ?>
      <?php component('code-block', ['code' => "component('badge', ['label' => 'active', 'type' => 'active']);", 'language' => 'php', 'title' => 'Usage']); ?>
    </section>

    <!-- Alerts -->
    <section id="uikit-alerts" class="sa-uikit-section">
      <h2>Alertes</h2>
      <?php foreach (['info','success','warning','error'] as $t): ?>
        <?php component('alert', ['type' => $t, 'message' => 'Alerte de type ' . $t . '.', 'dismissible' => true]); ?>
      <?php endforeach; ?>
      <?php component('code-block', ['code' => "component('alert', ['type' => 'success', 'message' => 'Opération réussie.', 'dismissible' => true]);", 'language' => 'php', 'title' => 'Usage']); ?>
    </section>

    <!-- Avatars -->
    <section id="uikit-avatars" class="sa-uikit-section">
      <h2>Avatars</h2>
      <div class="sa-flex sa-gap-md sa-items-center">
        <?php foreach (['sm','md','lg'] as $sz): ?>
          <?php component('avatar', ['name' => 'Alice Martin', 'size' => $sz]); ?>
        <?php endforeach; ?>
      </div>
      <?php component('code-block', ['code' => "component('avatar', ['name' => 'Alice Martin', 'size' => 'md']);", 'language' => 'php', 'title' => 'Usage']); ?>
    </section>

    <!-- Buttons -->
    <section id="uikit-buttons" class="sa-uikit-section">
      <h2>Boutons</h2>
      <div class="sa-flex sa-gap-sm sa-flex-wrap">
        <?php foreach (['sa-btn-primary','sa-btn-success','sa-btn-danger','sa-btn-warning','sa-btn-outline','sa-btn-ghost'] as $cls): ?>
          <button class="sa-btn <?= $cls ?>"><?= $cls ?></button>
        <?php endforeach; ?>
        <button class="sa-btn sa-btn-primary sa-btn-sm">Small</button>
        <button class="sa-btn sa-btn-primary sa-btn-xs">XS</button>
      </div>
    </section>

    <!-- Cards -->
    <section id="uikit-cards" class="sa-uikit-section">
      <h2>Cards</h2>
      <div class="sa-grid sa-grid-2">
        <?php
        ob_start(); echo '<p class="sa-text-muted">Contenu de la card simple.</p>';
        $s1 = ob_get_clean();
        component('card', ['title' => 'Card basique', 'slot' => $s1]);

        ob_start(); echo '<p class="sa-text-muted">Contenu de la card collapsible.</p>';
        $s2 = ob_get_clean();
        component('card-collapsible', ['title' => 'Card collapsible (ouverte)', 'open' => true, 'slot' => $s2]);
        ?>
      </div>
      <?php component('code-block', ['code' => "ob_start(); ?>\n<p>Contenu</p>\n<?php \$slot = ob_get_clean();\ncomponent('card', ['title' => 'Titre', 'slot' => \$slot]);", 'language' => 'php', 'title' => 'Usage']); ?>
    </section>

    <!-- Stats -->
    <section id="uikit-stats" class="sa-uikit-section">
      <h2>Stat Cards</h2>
      <div class="sa-grid sa-grid-4">
        <?php component('stat-card', ['label' => 'Revenus',   'value' => '12 400 €', 'delta' => '+8%',  'delta_type' => 'up',   'icon' => 'currency-euro', 'color' => 'primary']); ?>
        <?php component('stat-card', ['label' => 'Users',     'value' => '1 284',    'delta' => '+12%', 'delta_type' => 'up',   'icon' => 'users',         'color' => 'success']); ?>
        <?php component('stat-card', ['label' => 'Tickets',   'value' => '24',       'delta' => '-3',   'delta_type' => 'down', 'icon' => 'ticket',        'color' => 'warning']); ?>
        <?php component('stat-card', ['label' => 'Churn',     'value' => '2,4 %',    'delta' => '-0,2%','delta_type' => 'down', 'icon' => 'trending-down', 'color' => 'danger']); ?>
      </div>
      <div class="sa-grid sa-grid-4 sa-mt-md">
        <?php component('stat-mini', ['label' => 'MRR',       'value' => '24 800 €', 'color' => 'primary']); ?>
        <?php component('stat-mini', ['label' => 'ARR',       'value' => '297 600 €','color' => 'success']); ?>
        <?php component('stat-mini', ['label' => 'Trial',     'value' => '38',       'color' => 'info']); ?>
        <?php component('stat-mini', ['label' => 'NPS',       'value' => '72',       'color' => 'warning']); ?>
      </div>
      <div class="sa-mt-md">
        <?php component('progress-bar', ['label' => 'Objectif mensuel', 'value' => 72, 'color' => 'primary']); ?>
        <?php component('progress-bar', ['label' => 'Rétention',        'value' => 94, 'color' => 'success']); ?>
      </div>
    </section>

    <!-- Forms -->
    <section id="uikit-forms" class="sa-uikit-section">
      <h2>Formulaires</h2>
      <div class="sa-grid sa-grid-2">
        <?php component('form-input',  ['name' => 'demo_text',  'label' => 'Input texte',  'placeholder' => 'Placeholder…']); ?>
        <?php component('form-input',  ['name' => 'demo_email', 'label' => 'Input email',  'type' => 'email', 'placeholder' => 'vous@exemple.com', 'required' => true]); ?>
        <?php component('form-select', ['name' => 'demo_sel',   'label' => 'Select',       'options' => ['a' => 'Option A', 'b' => 'Option B', 'c' => 'Option C'], 'selected' => 'b']); ?>
        <?php component('form-input',  ['name' => 'demo_err',   'label' => 'Avec erreur',  'value' => 'valeur incorrecte', 'error' => 'Ce champ est invalide.']); ?>
      </div>
      <?php component('form-toggle',  ['name' => 'demo_toggle', 'label' => 'Toggle actif',  'checked' => true,  'description' => 'Description du toggle.']); ?>
      <?php component('form-toggle',  ['name' => 'demo_tog2',   'label' => 'Toggle inactif','checked' => false]); ?>
      <?php component('form-upload',  ['name' => 'demo_file',   'label' => 'Upload',        'accept' => '.pdf,.jpg,.png', 'max_size' => '10 Mo']); ?>
      <?php component('form-multiselect', [
        'name'        => 'demo_tags',
        'label'       => 'Multi-select — Fonctionnalités',
        'placeholder' => 'Sélectionner des fonctionnalités…',
        'selected'    => ['auth', 'api'],
        'options'     => [
          'auth'         => 'Authentification',
          'api'          => 'API REST',
          'billing'      => 'Facturation',
          'notifications'=> 'Notifications',
          'analytics'    => 'Analytics',
          'exports'      => 'Exports PDF/CSV',
          'roles'        => 'Gestion des rôles',
          'webhooks'     => 'Webhooks',
        ],
      ]); ?>
      <?php component('form-errors',  ['errors' => ['Erreur exemple 1', 'Erreur exemple 2']]); ?>
    </section>

    <!-- Multi-select -->
    <section id="uikit-multiselect" class="sa-uikit-section">
      <h2>Multi-select</h2>

      <div class="sa-grid sa-grid-2 sa-mb-lg">
        <!-- Variante avec pré-sélection -->
        <?php component('form-multiselect', [
          'name'        => 'demo_ms1',
          'label'       => 'Fonctionnalités (2 pré-sélectionnées)',
          'placeholder' => 'Sélectionner des fonctionnalités…',
          'selected'    => ['auth', 'billing'],
          'options'     => [
            'auth'          => 'Authentification',
            'api'           => 'API REST',
            'billing'       => 'Facturation',
            'notifications' => 'Notifications',
            'analytics'     => 'Analytics',
            'exports'       => 'Exports PDF/CSV',
            'roles'         => 'Gestion des rôles',
            'webhooks'      => 'Webhooks',
          ],
        ]); ?>

        <!-- Variante vide + erreur -->
        <?php component('form-multiselect', [
          'name'        => 'demo_ms2',
          'label'       => 'Tags (vide, requis)',
          'placeholder' => 'Ajouter des tags…',
          'selected'    => [],
          'required'    => true,
          'error'       => 'Sélectionnez au moins un tag.',
          'options'     => [
            'urgent'    => 'Urgent',
            'bug'       => 'Bug',
            'feature'   => 'Nouvelle fonctionnalité',
            'design'    => 'Design',
            'backend'   => 'Back-end',
            'frontend'  => 'Front-end',
          ],
        ]); ?>
      </div>

      <?php component('code-block', [
        'title'    => 'Usage PHP',
        'language' => 'php',
        'code'     => "component('form-multiselect', [
  'name'        => 'categories',          // name[] en POST
  'label'       => 'Catégories',
  'placeholder' => 'Choisir…',
  'selected'    => ['php', 'js'],         // valeurs pré-cochées
  'required'    => true,
  'error'       => \$errors['categories'] ?? '',
  'options'     => [
    'php'  => 'PHP',
    'js'   => 'JavaScript',
    'css'  => 'CSS',
    'sql'  => 'SQL',
  ],
]);

// Récupération dans le controller (action POST) :
\$categories = \$_POST['categories'] ?? [];  // tableau de valeurs sélectionnées",
      ]); ?>
    </section>

    <!-- Charts -->
    <section id="uikit-charts" class="sa-uikit-section">
      <h2>Graphiques</h2>
      <div class="sa-grid sa-grid-2">
        <div class="sa-card"><div class="sa-card-body">
          <?php component('chart-line', ['id' => 'uiLine', 'labels' => ['Jan','Fév','Mar','Avr','Mai','Juin'], 'datasets' => [['label' => 'Série A', 'data' => [12,19,8,17,14,22], 'borderColor' => '#4f46e5', 'tension' => 0.4]], 'title' => 'Line']); ?>
        </div></div>
        <div class="sa-card"><div class="sa-card-body">
          <?php component('chart-bar', ['id' => 'uiBar', 'labels' => ['Jan','Fév','Mar','Avr','Mai','Juin'], 'datasets' => [['label' => 'Série A', 'data' => [12,19,8,17,14,22], 'backgroundColor' => '#4f46e5']], 'title' => 'Bar']); ?>
        </div></div>
        <div class="sa-card"><div class="sa-card-body">
          <?php component('chart-donut', ['id' => 'uiDonut', 'labels' => ['A','B','C'], 'values' => [40,35,25], 'title' => 'Donut']); ?>
        </div></div>
        <div class="sa-card"><div class="sa-card-body">
          <?php component('chart-area', ['id' => 'uiArea', 'labels' => ['Jan','Fév','Mar','Avr','Mai','Juin'], 'datasets' => [['label' => 'Trafic', 'data' => [120,190,180,270,240,320], 'borderColor' => '#22c55e', 'tension' => 0.4]], 'title' => 'Area']); ?>
        </div></div>
      </div>
    </section>

    <!-- Tables -->
    <section id="uikit-tables" class="sa-uikit-section">
      <h2>Tableaux</h2>
      <?php component('search-bar', ['placeholder' => 'Filtrer le tableau…', 'target' => '#uiTable tbody']); ?>
      <div class="sa-table-wrapper">
        <table class="sa-table sa-mt-sm" id="uiTable">
          <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Statut</th></tr></thead>
          <tbody>
            <?php foreach (['Alice Martin','Bob Dupont','Claire Moreau','David Bernard','Emma Petit'] as $i => $n): ?>
              <tr>
                <td><?= e($n) ?></td>
                <td><?= e(strtolower(str_replace(' ', '.', $n))) ?>@example.com</td>
                <td><?php component('badge', ['label' => $i === 0 ? 'admin' : 'user', 'type' => $i === 0 ? 'admin' : 'user']); ?></td>
                <td><?php component('badge', ['label' => 'Actif', 'type' => 'active']); ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php component('empty-state', ['message' => 'Exemple d\'état vide', 'icon' => 'inbox', 'action_label' => 'Créer', 'action_url' => '/demo']); ?>
    </section>

    <!-- Logs -->
    <section id="uikit-logs" class="sa-uikit-section">
      <h2>Logs</h2>
      <?php component('log-viewer', ['logs' => [
        ['timestamp' => date('Y-m-d H:i:s', strtotime('-5 min')), 'level' => 'INFO',  'message' => 'Application démarrée',       'context' => 'env=local'],
        ['timestamp' => date('Y-m-d H:i:s', strtotime('-3 min')), 'level' => 'WARN',  'message' => 'Tentative de connexion',      'context' => 'ip=192.168.1.1'],
        ['timestamp' => date('Y-m-d H:i:s', strtotime('-1 min')), 'level' => 'ERROR', 'message' => 'Exception non gérée',         'context' => 'line=84'],
      ]]); ?>
      <?php component('code-block', ['code' => "<?php\n\$code = 'echo \"Hello World\";';\ncomponent('code-block', [\n  'code'     => \$code,\n  'language' => 'php',\n  'title'    => 'Exemple PHP',\n]);", 'language' => 'php', 'title' => 'Code Block PHP']); ?>
      <?php component('json-viewer', ['data' => ['user' => ['id' => 1, 'name' => 'Alice Martin', 'roles' => ['admin','editor']], 'meta' => ['total' => 142, 'page' => 1]]]); ?>
    </section>

    <!-- Timeline -->
    <section id="uikit-timeline" class="sa-uikit-section">
      <h2>Timeline</h2>
      <?php component('timeline', ['events' => [
        ['icon' => 'user-plus',   'color' => 'success', 'title' => 'Compte créé',         'description' => 'Alice Martin a créé son compte.',      'date' => date('Y-m-d H:i:s', strtotime('-2 days'))],
        ['icon' => 'credit-card', 'color' => 'primary', 'title' => 'Abonnement Pro',       'description' => 'Souscription au plan Pro (49 €/mois).', 'date' => date('Y-m-d H:i:s', strtotime('-1 day'))],
        ['icon' => 'settings',    'color' => 'info',    'title' => 'Paramètres modifiés',  'description' => 'Email de contact mis à jour.',          'date' => date('Y-m-d H:i:s', strtotime('-2 hours'))],
      ]]); ?>
    </section>

    <!-- Modals -->
    <section id="uikit-modals" class="sa-uikit-section">
      <h2>Modals</h2>
      <button class="sa-btn sa-btn-primary" data-modal-target="#uikitModal">Ouvrir une modal</button>
      <?php ob_start(); ?>
        <p>Contenu de la modal. Peut contenir n'importe quel composant.</p>
        <?php component('alert', ['type' => 'info', 'message' => 'Alerte dans une modal.']); ?>
      <?php component('modal', ['id' => 'uikitModal', 'title' => 'Exemple de modal', 'size' => 'md', 'slot' => ob_get_clean()]); ?>
      <?php component('code-block', ['code' => "// Ouvrir via data-modal-target\n<button data-modal-target=\"#myModal\">Ouvrir</button>\n\n// Déclarer la modal\ncomponent('modal', ['id' => 'myModal', 'title' => 'Titre', 'slot' => \$content]);", 'language' => 'php', 'title' => 'Usage Modal']); ?>
    </section>

    <!-- Pagination -->
    <section id="uikit-pagination" class="sa-uikit-section">
      <h2>Pagination</h2>
      <?php component('pagination', ['current' => 3, 'total' => 10, 'base_url' => '/demo/ui-kit']); ?>
      <?php component('code-block', ['code' => "component('pagination', [\n  'current'  => \$page,\n  'total'    => \$totalPages,\n  'base_url' => '/users',\n]);", 'language' => 'php', 'title' => 'Usage']); ?>
    </section>

  </div><!-- .sa-uikit-content -->
</div><!-- .sa-grid-uikit -->
