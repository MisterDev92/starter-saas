# Base SaaS — Starter PHP MVC

Starter PHP 7.4+ · PDO · Sessions natives · Tabler UI (CDN) · Chart.js · Vanilla JS

---

## Sommaire

1. [Installation locale](#installation-locale)
2. [Déploiement production](#déploiement-production)
3. [Architecture du projet](#architecture-du-projet)
4. [Comment fonctionne le MVC](#comment-fonctionne-le-mvc)
5. [Ajouter une fonctionnalité — guide complet](#ajouter-une-fonctionnalité--guide-complet)
6. [Référence : composants UI](#référence--composants-ui)
7. [Comptes de test](#comptes-de-test)

---

## Installation locale

```bash
# 1. Cloner le repo
git clone ...

# 2. Copier et remplir le .env
cp .env.example .env

# 3. Créer la base de données et importer le schéma
# Dans phpMyAdmin : créer la base "base_saas" puis importer sql/schema.sql

# 4. Copier le dossier /mandrill/ (depuis le projet source)

# 5. Configurer un virtual host Apache pointant vers /public
# OU accéder directement via http://localhost/votre-projet/public/
```

**`.env` minimum à remplir :**

```ini
APP_URL=http://localhost/votre-projet/public
APP_NAME=Mon SaaS

DB_HOST=localhost
DB_NAME=base_saas
DB_USER=root
DB_PASS=

MAIL_FROM=votre@email.com
MANDRILL_KEY=votre_cle_mandrill
```

Accéder à `/demo` pour vérifier sans BDD que tout est en place.

---

## Déploiement production

```
1. Uploader les fichiers (sans .env, sans sql/)
2. Créer le .env sur le serveur avec APP_ENV=production
3. Importer sql/schema.sql sur la BDD de prod
4. Vérifier que mod_rewrite est activé
5. Décommenter la règle HTTPS dans .htaccess
```

En production, `APP_ENV=production` active automatiquement :
- Masquage des erreurs PHP
- Logs dans `/logs/app.log`
- Cookies HTTPS uniquement

---

## Architecture du projet

```
/
├── public/                  ← Seul dossier accessible via le web
│   ├── index.php            ← Front controller (point d'entrée unique)
│   ├── .htaccess            ← Redirige tout vers index.php
│   └── assets/
│       ├── css/admin-custom.css
│       └── js/admin-custom.js
│
├── app/
│   ├── Core/
│   │   ├── Router.php       ← Dispatch les requêtes vers les controllers
│   │   ├── Controller.php   ← Classe de base : render, redirect, flash...
│   │   ├── Model.php        ← Classe de base : findAll, findById, create...
│   │   ├── Database.php     ← Singleton PDO
│   │   ├── Helpers.php      ← Fonctions globales : e(), url(), component()...
│   │   └── MailService.php  ← Envoi d'emails via Mandrill
│   │
│   ├── Controllers/         ← Un fichier par section
│   ├── Models/              ← Un fichier par table
│   └── Views/
│       ├── layouts/         ← admin.php, auth.php, print.php
│       ├── components/      ← 30 composants réutilisables
│       └── [section]/       ← Vues propres à chaque section
│
├── config/config.php        ← Charge le .env, démarre la session, autoloader
├── routes.php               ← Toutes les routes déclarées ici
└── sql/schema.sql           ← Schéma de la base
```

---

## Comment fonctionne le MVC

### Le flux d'une requête

```
Navigateur → GET /projets
        ↓
public/index.php          charge config, instancie Router, charge routes.php
        ↓
Router::dispatch()        cherche la route qui match "/projets"
        ↓
ProjetController::index() récupère les données via le Model
        ↓
Projet::findAll()         exécute SELECT * FROM projets
        ↓
Controller::render()      capture la vue dans $content, injecte dans le layout
        ↓
Navigateur               reçoit le HTML final
```

### Les 3 responsabilités

| Couche | Fichier | Rôle |
|---|---|---|
| **Model** | `app/Models/Projet.php` | Parle à la BDD, retourne des tableaux |
| **Controller** | `app/Controllers/ProjetController.php` | Reçoit la requête, appelle le Model, passe les données à la vue |
| **View** | `app/Views/projets/index.php` | Affiche les données, ne contient aucune logique |

---

## Ajouter une fonctionnalité — guide complet

On va créer un module **Projets** complet : liste, création, édition, suppression.

### Étape 1 — La table SQL

Ajouter dans `sql/schema.sql` et créer en BDD :

```sql
CREATE TABLE projets (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  titre       VARCHAR(255) NOT NULL,
  description TEXT,
  statut      ENUM('actif','archive') NOT NULL DEFAULT 'actif',
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Étape 2 — Le Model

Créer `app/Models/Projet.php` :

```php
<?php

namespace Models;

use Core\Model;

class Projet extends Model
{
    protected string $table = 'projets';

    // findAll, findById, create, update, delete, count
    // sont déjà disponibles via la classe parente Model.

    // Méthode custom si besoin :
    public function findActifs(): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE statut = 'actif' ORDER BY created_at DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
```

**Ce que la classe parente `Model` fournit déjà :**

| Méthode | Ce qu'elle fait |
|---|---|
| `findAll('created_at', 'DESC')` | `SELECT *` trié |
| `findById(int $id)` | `SELECT * WHERE id = ?` |
| `findBy('statut', 'actif')` | `SELECT * WHERE colonne = ?` |
| `create(['titre' => 'X', ...])` | `INSERT` → retourne l'id |
| `update(int $id, ['titre' => 'Y'])` | `UPDATE WHERE id = ?` |
| `delete(int $id)` | `DELETE WHERE id = ?` |
| `count()` | `SELECT COUNT(*)` |

### Étape 3 — Le Controller

Créer `app/Controllers/ProjetController.php` :

```php
<?php

namespace Controllers;

use Core\Controller;
use Models\Projet;

class ProjetController extends Controller
{
    private Projet $projets;

    public function __construct()
    {
        $this->projets = new Projet();
    }

    // GET /projets
    public function index(): void
    {
        $this->render('projets/index', [
            'page_title' => 'Projets',
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/dashboard'],
                ['label' => 'Projets'],
            ],
            'projets' => $this->projets->findAll('created_at', 'DESC'),
            'flash'   => $this->getFlash(),
        ]);
    }

    // GET /projets/create
    public function create(): void
    {
        $this->render('projets/create', [
            'page_title' => 'Nouveau projet',
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/dashboard'],
                ['label' => 'Projets',   'url' => '/projets'],
                ['label' => 'Nouveau'],
            ],
        ]);
    }

    // POST /projets/store
    public function store(): void
    {
        csrf_verify();

        $titre       = trim($_POST['titre']       ?? '');
        $description = trim($_POST['description'] ?? '');
        $errors      = [];

        if ($titre === '') {
            $errors[] = 'Le titre est requis.';
        }

        if (empty($errors)) {
            $this->projets->create([
                'titre'       => $titre,
                'description' => $description,
                'statut'      => 'actif',
            ]);
            $this->flash('success', 'Projet créé avec succès.');
            $this->redirect('/projets');
        }

        $this->render('projets/create', [
            'page_title' => 'Nouveau projet',
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/dashboard'],
                ['label' => 'Projets',   'url' => '/projets'],
                ['label' => 'Nouveau'],
            ],
            'errors' => $errors,
            'old'    => $_POST,
        ]);
    }

    // GET /projets/edit/{id}
    public function edit(string $id): void
    {
        $projet = $this->projets->findById((int) $id);
        if (!$projet) {
            $this->flash('error', 'Projet introuvable.');
            $this->redirect('/projets');
        }

        $this->render('projets/edit', [
            'page_title' => 'Modifier ' . e($projet['titre']),
            'breadcrumb' => [
                ['label' => 'Dashboard', 'url' => '/dashboard'],
                ['label' => 'Projets',   'url' => '/projets'],
                ['label' => 'Modifier'],
            ],
            'projet' => $projet,
        ]);
    }

    // POST /projets/update/{id}
    public function update(string $id): void
    {
        csrf_verify();

        $titre       = trim($_POST['titre']       ?? '');
        $description = trim($_POST['description'] ?? '');
        $statut      = $_POST['statut']            ?? 'actif';
        $errors      = [];

        if ($titre === '') {
            $errors[] = 'Le titre est requis.';
        }
        if (!in_array($statut, ['actif', 'archive'], true)) {
            $statut = 'actif';
        }

        if (empty($errors)) {
            $this->projets->update((int) $id, [
                'titre'       => $titre,
                'description' => $description,
                'statut'      => $statut,
            ]);
            $this->flash('success', 'Projet mis à jour.');
            $this->redirect('/projets');
        }

        $projet = $this->projets->findById((int) $id);
        $this->render('projets/edit', [
            'page_title' => 'Modifier',
            'breadcrumb' => [],
            'projet'     => array_merge($projet ?? [], $_POST),
            'errors'     => $errors,
        ]);
    }

    // POST /projets/delete/{id}
    public function destroy(string $id): void
    {
        csrf_verify();
        $this->projets->delete((int) $id);
        $this->flash('success', 'Projet supprimé.');
        $this->redirect('/projets');
    }
}
```

**Ce que la classe parente `Controller` fournit :**

| Méthode | Ce qu'elle fait |
|---|---|
| `$this->render('section/vue', $data)` | Affiche la vue dans le layout admin |
| `$this->render('section/vue', $data, 'auth')` | Affiche dans le layout auth |
| `$this->redirect('/chemin')` | Redirige |
| `$this->json(['key' => 'val'])` | Répond en JSON (routes API) |
| `$this->flash('success', 'Message')` | Stocke un message flash en session |
| `$this->getFlash()` | Récupère et vide le flash |
| `$this->requireAuth()` | Bloque si non connecté |
| `$this->requireAdmin()` | Bloque si pas admin |

### Étape 4 — Les routes

Dans `routes.php`, ajouter :

```php
// ─── Projets ─────────────────────────────────────────
$router->get('/projets',              'ProjetController', 'index',   ['requireAuth']);
$router->get('/projets/create',       'ProjetController', 'create',  ['requireAuth']);
$router->post('/projets/store',       'ProjetController', 'store',   ['requireAuth']);
$router->get('/projets/edit/{id}',    'ProjetController', 'edit',    ['requireAuth']);
$router->post('/projets/update/{id}', 'ProjetController', 'update',  ['requireAuth']);
$router->post('/projets/delete/{id}', 'ProjetController', 'destroy', ['requireAuth']);
```

**Syntaxe des routes :**

```php
$router->get('/chemin/{param}', 'MonController', 'maMethode', ['requireAuth']);
//                    ↑ paramètre dynamique passé en argument à la méthode
//                                                              ↑ middlewares

// Middlewares disponibles
['requireAuth']                    // doit être connecté
['requireAuth', 'requireAdmin']    // doit être admin
```

### Étape 5 — Les vues

Créer `app/Views/projets/index.php` :

```php
<div class="sa-card">
  <div class="sa-card-header">
    <h3 class="sa-card-title">Projets</h3>
    <div class="sa-card-actions">
      <?php component('search-bar', ['placeholder' => 'Rechercher…', 'target' => '#projetsTable tbody']); ?>
      <a href="<?= url('/projets/create') ?>" class="sa-btn sa-btn-primary">
        <i class="ti ti-plus"></i> Nouveau
      </a>
    </div>
  </div>
  <div class="sa-card-body sa-p-0">
    <?php if (empty($projets)): ?>
      <?php component('empty-state', ['message' => 'Aucun projet.', 'icon' => 'folder',
        'action_label' => 'Créer', 'action_url' => '/projets/create']); ?>
    <?php else: ?>
      <table class="sa-table" id="projetsTable">
        <thead>
          <tr><th>Titre</th><th>Statut</th><th>Date</th><th class="sa-th-actions">Actions</th></tr>
        </thead>
        <tbody>
          <?php foreach ($projets as $p): ?>
            <tr>
              <td><?= e($p['titre']) ?></td>
              <td><?php component('badge', ['label' => ucfirst($p['statut']), 'type' => $p['statut'] === 'actif' ? 'active' : 'inactive']); ?></td>
              <td class="sa-text-muted"><?= e(format_date($p['created_at'])) ?></td>
              <td class="sa-td-actions">
                <a href="<?= url('/projets/edit/' . $p['id']) ?>" class="sa-btn sa-btn-xs sa-btn-outline-primary">
                  <i class="ti ti-pencil"></i>
                </a>
                <form method="POST" action="<?= url('/projets/delete/' . $p['id']) ?>"
                      class="sa-inline-form" data-confirm="Supprimer ce projet ?">
                  <?= csrf_field() ?>
                  <button type="submit" class="sa-btn sa-btn-xs sa-btn-outline-danger">
                    <i class="ti ti-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
```

Créer `app/Views/projets/create.php` :

```php
<?php $errors = $errors ?? []; $old = $old ?? []; ?>

<div class="sa-card sa-card-form">
  <div class="sa-card-header">
    <h3 class="sa-card-title">Nouveau projet</h3>
    <a href="<?= url('/projets') ?>" class="sa-btn sa-btn-ghost"><i class="ti ti-arrow-left"></i> Retour</a>
  </div>
  <div class="sa-card-body">
    <?php component('form-errors', ['errors' => $errors]); ?>
    <form method="POST" action="<?= url('/projets/store') ?>" novalidate>
      <?= csrf_field() ?>
      <?php component('form-input', ['name' => 'titre', 'label' => 'Titre', 'value' => $old['titre'] ?? '', 'required' => true]); ?>
      <div class="sa-form-group">
        <label class="sa-label" for="description">Description</label>
        <textarea id="description" name="description" class="sa-input sa-textarea" rows="4"><?= e($old['description'] ?? '') ?></textarea>
      </div>
      <div class="sa-form-actions">
        <button type="submit" class="sa-btn sa-btn-primary">Créer</button>
        <a href="<?= url('/projets') ?>" class="sa-btn sa-btn-ghost">Annuler</a>
      </div>
    </form>
  </div>
</div>
```

### Étape 6 — Ajouter au menu sidebar

Dans `app/Views/components/sidebar.php`, compléter `$defaultMenu` :

```php
$defaultMenu = [
    ['label' => 'Dashboard',   'icon' => 'layout-dashboard', 'url' => '/dashboard', 'admin' => false],
    ['label' => 'Projets',     'icon' => 'folder',           'url' => '/projets',   'admin' => false], // ← nouveau
    ['label' => 'Utilisateurs','icon' => 'users',            'url' => '/users',     'admin' => true],
    ['label' => '──────',      'icon' => '',                 'url' => '',           'admin' => false, 'divider' => true],
    ['label' => 'Démo',        'icon' => 'player-play',      'url' => '/demo',      'admin' => false],
];
```

**Propriétés disponibles pour un item de menu :**

| Propriété | Type | Description |
|---|---|---|
| `label` | string | Texte affiché |
| `icon` | string | Nom Tabler Icons (sans `ti-`) |
| `url` | string | Chemin de la route |
| `admin` | bool | `true` = visible admins uniquement |
| `badge` | string | Badge numérique optionnel |
| `badge_type` | string | Couleur du badge (`danger`, `primary`...) |
| `divider` | bool | `true` = affiche un séparateur |

---

## Référence : composants UI

Tous les composants s'appellent via `component('nom', ['prop' => 'valeur'])`.
La page `/demo/ui-kit` les montre tous en situation avec leur code PHP.

### Composants fréquents

```php
// Alerte flash
component('alert', ['type' => 'success', 'message' => 'Enregistré !', 'dismissible' => true]);
// types : info | success | warning | error

// Badge coloré
component('badge', ['label' => 'Actif', 'type' => 'active']);
// types : active | inactive | pending | primary | success | danger | warning | info | admin | user

// Avatar initiales
component('avatar', ['name' => 'Alice Martin', 'size' => 'md']);
// sizes : sm | md | lg

// Stat card KPI
component('stat-card', [
  'label' => 'Utilisateurs', 'value' => '1 284',
  'delta' => '+8%', 'delta_type' => 'up',
  'icon'  => 'users', 'color' => 'primary',
]);

// Champ input
component('form-input', [
  'name' => 'email', 'label' => 'Email', 'type' => 'email',
  'value' => $old['email'] ?? '', 'error' => $errors['email'] ?? '',
  'required' => true,
]);

// Select
component('form-select', [
  'name'     => 'statut',
  'label'    => 'Statut',
  'options'  => ['actif' => 'Actif', 'archive' => 'Archivé'],
  'selected' => 'actif',
]);

// Toggle on/off
component('form-toggle', [
  'name'    => 'is_active',
  'label'   => 'Compte actif',
  'checked' => true,
]);

// Pagination
component('pagination', ['current' => $page, 'total' => $totalPages, 'base_url' => '/projets']);

// Barre de recherche live (filtre JS sans rechargement)
component('search-bar', ['placeholder' => 'Rechercher…', 'target' => '#monTableau tbody']);

// État vide
component('empty-state', [
  'message'      => 'Aucun élément.',
  'icon'         => 'folder',
  'action_label' => 'Créer',
  'action_url'   => '/projets/create',
]);
```

### Fonctions helpers disponibles partout dans les vues

```php
e($valeur)                         // échappe le HTML — toujours utiliser pour afficher des données
url('/projets')                    // URL complète depuis APP_URL
asset('css/admin-custom.css')      // URL d'un asset public
csrf_field()                       // <input type="hidden" name="csrf_token" value="...">
csrf_verify()                      // vérifie le token CSRF — appeler au début de toute action POST
format_date('2026-01-15')          // → "15/01/2026"
format_date('2026-01-15', 'd/m/Y H:i')
format_money(49.90)                // → "49,90 €"
truncate('Texte long...', 80)      // coupe à N caractères avec "…"
active('/projets')                 // retourne "active" si l'URL courante correspond (pour les menus)
component('nom', ['prop' => 'v'])  // inclut un composant UI avec ses props
```

---

## Comptes de test

| Email | Mot de passe | Rôle |
|---|---|---|
| admin@test.com | admin1234 | admin |
| user@test.com  | user1234  | user  |

> Changer ces mots de passe avant tout déploiement.
> Hash via : `php -r "echo password_hash('monmdp', PASSWORD_BCRYPT);"`
