# readme-structure.md — Référence technique complète

> Document de référence interne — mettre à jour à chaque évolution du projet.
> Ne pas versionner si des informations sensibles y sont ajoutées.

---

## Sommaire

1. [Contexte du projet](#1-contexte-du-projet)
2. [Stack technique](#2-stack-technique)
3. [Flux d'une requête de bout en bout](#3-flux-dune-requête-de-bout-en-bout)
4. [Router](#4-router)
5. [Controller](#5-controller)
6. [Model et Database](#6-model-et-database)
7. [Système de vues et layouts](#7-système-de-vues-et-layouts)
8. [Composants UI](#8-composants-ui)
9. [Authentification et sessions](#9-authentification-et-sessions)
10. [Protection CSRF](#10-protection-csrf)
11. [Envoi d'emails — Mandrill](#11-envoi-demails--mandrill)
12. [Helpers globaux](#12-helpers-globaux)
13. [Système CSS — Design system](#13-système-css--design-system)
14. [JavaScript — Fonctions globales](#14-javascript--fonctions-globales)
15. [Configuration — .env](#15-configuration--env)
16. [Autoloader](#16-autoloader)
17. [Sécurité](#17-sécurité)
18. [Modules en place](#18-modules-en-place)
19. [Journal des évolutions](#19-journal-des-évolutions)

---

## 1. Contexte du projet

| Clé | Valeur |
|---|---|
| Nom du projet | _(à remplir)_ |
| Client | _(à remplir)_ |
| Démarré le | _(à remplir)_ |
| URL locale | _(à remplir)_ |
| URL production | _(à remplir)_ |
| BDD locale | _(à remplir)_ |
| BDD production | _(à remplir)_ |
| Hébergeur | _(à remplir)_ |
| Accès FTP / SSH | _(à remplir)_ |

---

## 2. Stack technique

| Composant | Choix | Version | Raison |
|---|---|---|---|
| Langage back | PHP | 7.4+ | Compatibilité hébergement mutualisé |
| Base de données | MySQL / MariaDB | — | Standard |
| Accès BDD | PDO | natif PHP | Sécurité requêtes préparées |
| UI Framework | Tabler | 1.0.0-beta20 (CDN) | SaaS-ready, Bootstrap-based |
| Icônes | Tabler Icons | 2.47.0 (CDN webfont) | Cohérent avec Tabler |
| Graphiques | Chart.js | 4.4.4 (CDN) | Léger, flexible |
| Coloration code | Highlight.js | 11.9.0 (CDN) | Zéro config |
| Typographie | Inter + Fira Code | Google Fonts | Design system |
| JS | Vanilla JS | ES5 compatible | Zéro dépendance |
| Emails | Mandrill | lib locale `/mandrill/` | Déjà en place |
| Routing | Custom `Router.php` | — | Simple, suffisant |
| Sessions | PHP native | — | Pas besoin de lib |

**Pas de :**
- Composer / vendor
- npm / node_modules
- Framework (Symfony, Laravel...)
- jQuery (sauf contrainte Tabler)
- Bundler (esbuild, webpack...)

---

## 3. Flux d'une requête de bout en bout

```
1. Navigateur envoie : GET /users/edit/42

2. Apache mod_rewrite (.htaccess) redirige vers public/index.php

3. public/index.php :
   - require config/config.php
     → parse_ini_file(.env) → define() des constantes
     → session_start()
     → spl_autoload_register() → autoloader PSR-4
     → require app/Core/Helpers.php
   - instancie Core\Router
   - require routes.php → $router->get('/users/edit/{id}', ...)
   - appelle $router->dispatch('GET', '/users/edit/42')

4. Router::dispatch() :
   - nettoie l'URI, retire le basePath de APP_URL
   - boucle sur les routes
   - match '/users/edit/{id}' → extrait ['42']
   - instancie Controllers\UserController
   - appelle middleware : $ctrl->requireAuth()
     → si non connecté : redirect('/login') + exit
   - appelle $ctrl->edit('42')

5. UserController::edit('42') :
   - $this->users->findById(42) → PDO SELECT * FROM users WHERE id = 42
   - $this->render('users/edit', ['user' => $row, ...])

6. Controller::render('users/edit', $data) :
   - extract($data) → $user, $page_title, etc. disponibles
   - include app/Views/users/edit.php
   - ob_start() / ob_get_clean() → $content = HTML de la vue
   - include app/Views/layouts/admin.php → injecte $content

7. Layout admin.php :
   - charge CDN CSS en <head>
   - component('sidebar', ...) → include app/Views/components/sidebar.php
   - component('topbar', ...) → include app/Views/components/topbar.php
   - echo $content
   - charge CDN JS en fin de body
   - admin-custom.js → DOMContentLoaded → init*()

8. Navigateur reçoit le HTML complet
```

---

## 4. Router

**Fichier :** `app/Core/Router.php`

### Enregistrement des routes

```php
// routes.php
$router->get('/chemin',           'MonController', 'maMethode', ['middleware']);
$router->post('/chemin',          'MonController', 'maMethode', []);
$router->put('/chemin/{id}',      'MonController', 'maMethode', []);
$router->delete('/chemin/{id}',   'MonController', 'maMethode', []);
```

### Paramètres dynamiques

La notation `{param}` dans le chemin extrait la valeur et la passe en argument positionnel à la méthode :

```php
// Route déclarée :
$router->get('/users/edit/{id}', 'UserController', 'edit', ['requireAuth']);

// Méthode dans le controller :
public function edit(string $id): void   // $id = '42'
```

### Middlewares

Les middlewares sont des méthodes publiques du Controller appelées avant l'action :

```php
public function requireAuth(): void     // redirige vers /login si non connecté
public function requireAdmin(): void    // redirige vers /dashboard si role ≠ 'admin'
```

Pour en ajouter un custom, créer une méthode public dans Controller.php ou dans le controller enfant.

### Support du method override

Pour les formulaires HTML qui ne supportent que GET/POST, le Router détecte `$_POST['_method']` :

```html
<form method="POST" action="...">
  <input type="hidden" name="_method" value="DELETE">
</form>
```

### Page 404

Si aucune route ne matche, le Router appelle `app/Views/errors/404.php`.

---

## 5. Controller

**Fichier :** `app/Core/Controller.php`

Toute classe controller doit étendre `Controller` :

```php
namespace Controllers;
use Core\Controller;

class MonController extends Controller { ... }
```

### render()

```php
$this->render(string $view, array $data = [], string $layout = 'admin'): void
```

- `$view` : chemin relatif à `app/Views/`, les `.` sont convertis en `/`
  - `'users/edit'` → `app/Views/users/edit.php`
- `$data` : tableau extrait avec `extract()` → variables disponibles dans la vue
- `$layout` : `'admin'` | `'auth'` | `'print'`

**Mécanisme :**
1. `extract($data)` rend toutes les clés disponibles comme variables PHP
2. `ob_start()` → `include` la vue → `ob_get_clean()` → `$content`
3. `include` le layout qui affiche `$content`

### redirect()

```php
$this->redirect('/dashboard');  // utilise url() en interne → APP_URL + chemin
```

Appelle `exit` après le header Location.

### json()

```php
$this->json(['status' => 'ok', 'data' => $items], 200);
```

Pose le header `Content-Type: application/json`, encode et termine.

### Flash messages

```php
// Dans une action POST avant redirect :
$this->flash('success', 'Enregistré avec succès.');
$this->redirect('/users');

// Dans la vue (ou en passant 'flash' => $this->getFlash() au render) :
// Le layout admin.php récupère automatiquement le flash de session
```

Types disponibles : `success` | `error` | `info` | `warning`

---

## 6. Model et Database

### Database (Singleton PDO)

**Fichier :** `app/Core/Database.php`

```php
$pdo = Core\Database::getInstance();
// Retourne toujours la même instance PDO
// Options : ERRMODE_EXCEPTION, FETCH_ASSOC, EMULATE_PREPARES = false
```

Une seule connexion par requête HTTP. Credentials lus depuis les constantes `.env`.

### Model (classe abstraite)

**Fichier :** `app/Core/Model.php`

```php
namespace Models;
use Core\Model;

class Projet extends Model
{
    protected string $table = 'projets';  // ← obligatoire
}
```

**Méthodes disponibles sans rien écrire :**

```php
$m = new Projet();

$m->findAll()                        // SELECT * ORDER BY id DESC
$m->findAll('created_at', 'ASC')     // ORDER BY created_at ASC
$m->findById(42)                     // SELECT * WHERE id = 42 → array|null
$m->findBy('statut', 'actif')        // SELECT * WHERE statut = 'actif' → array[]
$m->create(['titre' => 'X', ...])    // INSERT → retourne (int) lastInsertId
$m->update(42, ['titre' => 'Y'])     // UPDATE WHERE id = 42 → bool
$m->delete(42)                       // DELETE WHERE id = 42 → bool
$m->count()                          // SELECT COUNT(*) → int
```

**Règles :**
- Toutes les requêtes utilisent des requêtes préparées PDO — jamais de concaténation SQL
- Pour des requêtes complexes (JOIN, filtres dynamiques...), écrire une méthode custom dans le Model enfant en utilisant `$this->db` (instance PDO)

**Exemple de méthode custom :**

```php
public function findAvecAuteur(): array
{
    $stmt = $this->db->prepare("
        SELECT p.*, u.name AS auteur_name
        FROM {$this->table} p
        LEFT JOIN users u ON u.id = p.user_id
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll();
}
```

---

## 7. Système de vues et layouts

### Layouts disponibles

| Layout | Fichier | Usage |
|---|---|---|
| `admin` | `app/Views/layouts/admin.php` | Toutes les pages BO (sidebar + topbar) |
| `auth` | `app/Views/layouts/auth.php` | Login, register, forgot password |
| `print` | `app/Views/layouts/print.php` | Export / impression |

### Variables injectées automatiquement dans le layout admin

Ces variables sont disponibles dans le layout sans les passer explicitement :

| Variable | Source | Usage |
|---|---|---|
| `$content` | Controller::render() | HTML de la vue |
| `$_SESSION['user_name']` | Session | Nom dans la topbar |
| `$_SESSION['user_role']` | Session | Filtrage menu admin |
| `$_SESSION['_flash']` | Session | Message flash auto |
| `APP_NAME` | Constante .env | Titre et branding |

### Variables conventionnelles à passer au render()

```php
$this->render('section/vue', [
    'page_title' => 'Titre de la page',        // <title> + topbar
    'breadcrumb' => [                           // Fil d'ariane
        ['label' => 'Dashboard', 'url' => '/dashboard'],
        ['label' => 'Section'],                 // Dernier sans url
    ],
    'flash'      => $this->getFlash(),          // Message flash si pas de redirect
    // ... données métier
]);
```

### Organisation des vues

```
app/Views/
├── layouts/          → gabarits HTML complets
├── components/       → fragments réutilisables (appelés via component())
├── errors/           → 404.php
├── auth/             → login, register, forgot, reset
├── dashboard/        → index
├── users/            → index, create, edit
├── demo/             → 11 pages de démonstration
└── [module]/         → à créer pour chaque nouveau module
```

---

## 8. Composants UI

**Fichier d'appel :** `app/Core/Helpers.php` → fonction `component()`

```php
function component(string $name, array $props = []): void
{
    $path = APP_PATH . '/Views/components/' . $name . '.php';
    extract($props);   // chaque clé devient une variable PHP dans le composant
    include $path;
}
```

### Convention d'un composant

```php
// app/Views/components/mon-composant.php
<?php
// Toujours définir des valeurs par défaut pour toutes les props
$titre = $titre ?? '';
$type  = $type  ?? 'primary';
?>
<div class="sa-mon-composant sa-mon-composant-<?= e($type) ?>">
  <?= e($titre) ?>
</div>
```

### Liste complète des composants

| Composant | Props principales |
|---|---|
| `sidebar` | `$menu_items`, `$user` |
| `topbar` | `$page_title`, `$notifications` |
| `breadcrumb` | `$items` [['label','url']] |
| `stat-card` | `$label`, `$value`, `$delta`, `$delta_type`, `$icon`, `$color` |
| `stat-mini` | `$label`, `$value`, `$color` |
| `progress-bar` | `$label`, `$value` (0-100), `$color` |
| `table` | `$headers`, `$rows`, `$actions` |
| `table-actions` | `$id`, `$base_url`, `$can_delete` |
| `empty-state` | `$message`, `$icon`, `$action_label`, `$action_url` |
| `chart-line` | `$id`, `$labels`, `$datasets`, `$title` |
| `chart-bar` | `$id`, `$labels`, `$datasets`, `$title` |
| `chart-donut` | `$id`, `$labels`, `$values`, `$colors`, `$title` |
| `chart-area` | `$id`, `$labels`, `$datasets`, `$title` |
| `form-input` | `$name`, `$label`, `$type`, `$value`, `$placeholder`, `$error`, `$required` |
| `form-select` | `$name`, `$label`, `$options`, `$selected`, `$error` |
| `form-toggle` | `$name`, `$label`, `$checked`, `$description` |
| `form-upload` | `$name`, `$label`, `$accept`, `$max_size` |
| `form-errors` | `$errors` (tableau de messages) |
| `card` | `$title`, `$footer`, `$class`, `$slot` |
| `card-collapsible` | `$title`, `$open`, `$slot` |
| `alert` | `$type`, `$message`, `$dismissible` |
| `badge` | `$label`, `$type` |
| `avatar` | `$name`, `$avatar_url`, `$size` |
| `modal` | `$id`, `$title`, `$size`, `$slot` |
| `log-line` | `$timestamp`, `$level`, `$message`, `$context` |
| `log-viewer` | `$logs` |
| `code-block` | `$code`, `$language`, `$title` |
| `json-viewer` | `$data` |
| `pagination` | `$current`, `$total`, `$base_url` |
| `search-bar` | `$placeholder`, `$target` |
| `tabs` | `$tabs` [['label','icon','content']] |
| `timeline` | `$events` [['icon','color','title','description','date']] |
| `tooltip` | _(via attribut HTML `data-tooltip="..."`)_ |

### Couleurs disponibles

`primary` | `success` | `danger` | `warning` | `info`

### Tailles disponibles

`sm` | `md` | `lg` (avatars, modals...)
`xs` | `sm` (boutons)

---

## 9. Authentification et sessions

### Démarrage de session

`config/config.php` démarre la session avec :
- HttpOnly = true
- SameSite = Lax
- Secure = true uniquement si `APP_ENV=production`

### Structure de la session

```php
$_SESSION = [
    'user_id'    => 42,
    'user_name'  => 'Alice Martin',
    'user_email' => 'alice@exemple.com',
    'user_role'  => 'admin',   // 'admin' | 'user'
    '_csrf_token' => 'abc123...',
    '_flash'     => ['type' => 'success', 'message' => '...'],
];
```

### Rôles

| Rôle | Accès |
|---|---|
| `user` | Dashboard, son propre profil |
| `admin` | Tout + gestion des utilisateurs |

### Flux login

```
POST /login
  → csrf_verify()
  → User::findByEmail($email)
  → password_verify($password, $hash)
  → is_active = 1
  → $_SESSION = [user_id, user_name, user_email, user_role]
  → User::updateLastLogin()
  → redirect('/dashboard')
```

### Flux forgot password

```
POST /forgot-password
  → csrf_verify()
  → User::findByEmail($email)  [si trouvé uniquement, sinon même message]
  → token = bin2hex(random_bytes(32))
  → expires = +15 minutes
  → User::storeResetToken(id, token, expires)
  → MailService::sendPasswordReset(email, name, url)
  → flash('success', message neutre)  [anti-énumération]

GET /reset-password?token=xxx
  → User::findByResetToken(token)  [vérifie expires > NOW()]
  → si invalide : flash + redirect /forgot-password

POST /reset-password
  → password_hash(password, PASSWORD_BCRYPT)
  → User::update(id, ['password' => hash])
  → User::clearResetToken(id)
  → redirect('/login')
```

### Flux création compte (invitation)

```
POST /users/store  [admin uniquement]
  → Créer le compte avec mot de passe temporaire aléatoire
  → Générer token (24h)
  → User::storeResetToken()
  → MailService::sendInvitation()  → lien vers /reset-password?token=...
```

---

## 10. Protection CSRF

**Fichier :** `app/Core/Helpers.php`

Chaque formulaire POST doit inclure le token :

```php
// Dans la vue (formulaire)
<form method="POST" action="...">
  <?= csrf_field() ?>   <!-- <input type="hidden" name="csrf_token" value="..."> -->
  ...
</form>
```

```php
// En début d'action POST dans le controller
csrf_verify();   // 403 + die() si token invalide ou absent
```

Le token est généré via `bin2hex(random_bytes(32))` et stocké en session. La comparaison utilise `hash_equals()` (résistant aux timing attacks).

---

## 11. Envoi d'emails — Mandrill

**Fichier :** `app/Core/MailService.php`
**Lib :** `/mandrill/src/Mandrill.php` (bibliothèque locale)

### Configuration dans .env

```ini
MANDRILL_KEY=votre_cle_api
MAIL_FROM=expediteur@domaine.com
MAIL_FROM_NAME=Nom Expéditeur
```

### Utilisation

```php
$mail = new Core\MailService();

// Email d'invitation (création de compte)
$mail->sendInvitation($email, $nom, $url);      // lien valable 24h

// Email de réinitialisation de mot de passe
$mail->sendPasswordReset($email, $nom, $url);   // lien valable 15min
```

### Ajouter un nouveau type d'email

Dans `MailService.php`, ajouter une méthode publique :

```php
public function sendFacture(string $toEmail, string $toName, string $pdfUrl): bool
{
    return $this->send(
        $toEmail,
        $toName,
        'Votre facture est disponible',
        $this->buildHtml(
            'Nouvelle facture',
            'Votre facture du mois est disponible.',
            'Télécharger la facture',
            $pdfUrl,
            'Disponible pendant 30 jours.'
        )
    );
}
```

Les emails sont loggés dans `/logs/` si `APP_ENV=production`.

---

## 12. Helpers globaux

**Fichier :** `app/Core/Helpers.php` — chargé automatiquement pour toutes les requêtes.

| Fonction | Signature | Description |
|---|---|---|
| `e()` | `e(string $val): string` | `htmlspecialchars()` — toujours utiliser pour afficher des données |
| `url()` | `url(string $path = ''): string` | `APP_URL + /chemin` |
| `asset()` | `asset(string $path): string` | `APP_URL + /assets/chemin` |
| `component()` | `component(string $name, array $props): void` | Inclut un composant UI |
| `csrf_token()` | `csrf_token(): string` | Génère ou retourne le token session |
| `csrf_field()` | `csrf_field(): string` | Retourne le `<input hidden>` CSRF |
| `csrf_verify()` | `csrf_verify(): void` | Vérifie le token, `die(403)` si invalide |
| `active()` | `active(string $path): string` | Retourne `'active'` si URL courante correspond |
| `format_date()` | `format_date(string $date, string $format = 'd/m/Y'): string` | Formate une date BDD |
| `format_money()` | `format_money(float $amount, string $currency = '€'): string` | Formate un montant |
| `truncate()` | `truncate(string $text, int $length = 100): string` | Coupe un texte avec `…` |

---

## 13. Système CSS — Design system

**Fichier :** `public/assets/css/admin-custom.css`

### Variables CSS (`:root`)

Toutes les valeurs sont des variables CSS — ne jamais hardcoder couleurs ou espacements dans les composants.

```css
/* Couleurs */
--sa-primary, --sa-primary-light, --sa-primary-dark
--sa-danger, --sa-success, --sa-warning, --sa-info

/* Backgrounds */
--sa-bg-sidebar, --sa-bg-main, --sa-bg-card, --sa-bg-hover

/* Textes */
--sa-text-main, --sa-text-muted, --sa-text-light, --sa-text-inverse

/* Bordures / effets */
--sa-border
--sa-radius-sm (4px), --sa-radius (8px), --sa-radius-lg (12px)
--sa-shadow-sm, --sa-shadow, --sa-shadow-lg

/* Espacements */
--sa-space-xs (4px), --sa-space-sm (8px), --sa-space-md (16px)
--sa-space-lg (24px), --sa-space-xl (32px)

/* Typographie */
--sa-font (Inter), --sa-font-mono (Fira Code)
--sa-text-sm (0.875rem), --sa-text-base (1rem), --sa-text-lg (1.125rem)
--sa-text-xl (1.25rem), --sa-text-2xl (1.5rem)

/* Transition */
--sa-transition (0.2s ease)
```

### Dark mode

Activé par `body.dark` (JS) — les variables sont surchargées :

```css
body.dark {
  --sa-bg-main:  #0f172a;
  --sa-bg-card:  #1e293b;
  /* ... */
}
```

La préférence est sauvegardée dans `localStorage` (`sa-dark: '1' | '0'`).

### Nomenclature des classes

Toutes les classes custom sont préfixées `.sa-` pour éviter les conflits avec Tabler :

```
.sa-card          → carte
.sa-btn           → bouton
.sa-badge         → badge
.sa-alert         → alerte
.sa-table         → tableau
.sa-input         → champ input
.sa-nav-link      → lien de navigation sidebar
.sa-stat-card     → card KPI
...
```

### Grilles utilitaires

```css
.sa-grid            → display: grid
.sa-grid-2          → 2 colonnes égales
.sa-grid-3          → 3 colonnes égales
.sa-grid-4          → 4 colonnes égales
.sa-grid-3-1        → 1fr 2fr (profil + contenu)
.sa-grid-uikit      → 180px + 1fr (nav latérale + contenu)
```

### Ajouter des styles pour un nouveau module

Ajouter une section à la fin de `admin-custom.css` :

```css
/* ─── Mon module ─────────────────────────────────────── */
.sa-monmodule-header { ... }
.sa-monmodule-card   { ... }
```

Ne jamais modifier les sections existantes pour ajouter du code spécifique à un module.

---

## 14. JavaScript — Fonctions globales

**Fichier :** `public/assets/js/admin-custom.js`

Toutes les fonctions sont nommées et appelées dans un seul `DOMContentLoaded` à la fin du fichier.

| Fonction | Ce qu'elle fait |
|---|---|
| `initTooltips()` | Active les `[data-tooltip]` (CSS-only) |
| `initModals()` | Gère ouverture/fermeture via `data-modal-target="#id"` |
| `initCollapsibles()` | Toggle des `[data-collapsible]` |
| `initDarkMode()` | Toggle `body.dark` + localStorage |
| `initSidebarMobile()` | Toggle sidebar sur mobile |
| `initSearchFilter()` | Filtre live sur tableaux via `.sa-search-input` |
| `initSortableTable()` | Tri colonnes sur `.sa-th-sortable` |
| `initDeleteConfirm()` | `confirm()` avant `form[data-confirm]` |
| `initDismissAlerts()` | Ferme les alertes `[data-dismiss-alert]` |
| `initHighlight()` | Coloration syntaxique via highlight.js |
| `initJsonViewer()` | Expand/collapse pour `[data-json-viewer]` |
| `initCopyButtons()` | Copie code/log via `[data-copy-code]` |
| `initFormValidation()` | Validation basique côté client (required, email) |
| `initDropdowns()` | Gère les menus dropdown `[data-dropdown]` |
| `initTabs()` | Switching onglets `.sa-tab-btn` |
| `initUploadZones()` | Drag & drop + preview upload |
| `initNotifFilter()` | Filtre notifications `[data-notif-filter]` |
| `initLogFilter()` | Filtre logs `[data-log-filter]` |

### Ajouter un comportement JS

```js
// Dans admin-custom.js, ajouter la fonction nommée :
function initMonComportement() {
  document.querySelectorAll('[data-mon-trigger]').forEach(function(el) {
    el.addEventListener('click', function() {
      // ...
    });
  });
}

// Puis l'appeler dans le DOMContentLoaded existant :
document.addEventListener('DOMContentLoaded', function() {
  // ... fonctions existantes ...
  initMonComportement();  // ← ajouter ici
});
```

---

## 15. Configuration — .env

**Fichier :** `.env` (jamais commité — listé dans `.gitignore`)
**Template :** `.env.example` (commité, valeurs vides)

Chargé par `config/config.php` via `parse_ini_file()`. Chaque clé devient une constante PHP via `define()`.

| Constante | Type | Description |
|---|---|---|
| `APP_ENV` | `local` \| `production` | Mode d'exécution |
| `APP_URL` | URL | Base de l'application (sans `/` final) |
| `APP_NAME` | string | Nom affiché dans le BO |
| `DB_HOST` | string | Hôte MySQL |
| `DB_NAME` | string | Nom de la base |
| `DB_USER` | string | Utilisateur MySQL |
| `DB_PASS` | string | Mot de passe MySQL |
| `DB_CHARSET` | string | `utf8mb4` |
| `MAIL_FROM` | email | Adresse expéditeur |
| `MAIL_FROM_NAME` | string | Nom expéditeur |
| `MANDRILL_KEY` | string | Clé API Mandrill |

**Comportement selon `APP_ENV` :**

```
local      → display_errors = 1, error_reporting = E_ALL
production → display_errors = 0, log_errors = 1, logs dans /logs/app.log
           → cookies secure (HTTPS only)
```

---

## 16. Autoloader

**Fichier :** `config/config.php`

```php
spl_autoload_register(function (string $class): void {
    $file = APP_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
```

**Convention de nommage :**

| Namespace\Classe | Fichier |
|---|---|
| `Core\Router` | `app/Core/Router.php` |
| `Core\Controller` | `app/Core/Controller.php` |
| `Controllers\UserController` | `app/Controllers/UserController.php` |
| `Models\User` | `app/Models/User.php` |

Pas besoin de `require_once` dans le code — l'autoloader s'en charge dès qu'une classe est instanciée.

---

## 17. Sécurité

| Mécanisme | Implémentation |
|---|---|
| Injection SQL | PDO + requêtes préparées partout, jamais de concaténation |
| XSS | `e()` = `htmlspecialchars()` sur toutes les variables dans les vues |
| CSRF | Token en session sur tous les formulaires POST |
| Passwords | `password_hash(... PASSWORD_BCRYPT)` + `password_verify()` |
| Sessions | HttpOnly, SameSite=Lax, Secure en production |
| Accès fichiers | `.htaccess` bloque `app/`, `config/`, `sql/`, `.env` |
| Uploads | Validation MIME + extension (si upload ajouté) |
| Accès routes | Middleware `requireAuth` / `requireAdmin` par route |
| Reset token | 32 octets aléatoires, expiration 15 min (forgot) / 24h (invitation) |
| Anti-énumération | Forgot password : même message si email trouvé ou non |

---

## 18. Modules en place

### Auth

| Route | Méthode | Controller | Action |
|---|---|---|---|
| `/login` | GET | AuthController | loginForm |
| `/login` | POST | AuthController | login |
| `/logout` | GET | AuthController | logout |
| `/register` | GET | AuthController | registerForm |
| `/register` | POST | AuthController | register |
| `/forgot-password` | GET | AuthController | forgotForm |
| `/forgot-password` | POST | AuthController | forgot |
| `/reset-password` | GET | AuthController | resetForm |
| `/reset-password` | POST | AuthController | reset |

### Dashboard

| Route | Méthode | Controller | Action | Auth |
|---|---|---|---|---|
| `/dashboard` | GET | DashboardController | index | ✓ |
| `/` | GET | DashboardController | index | ✓ |

### Utilisateurs

| Route | Méthode | Controller | Action | Auth |
|---|---|---|---|---|
| `/users` | GET | UserController | index | admin |
| `/users/create` | GET | UserController | create | admin |
| `/users/store` | POST | UserController | store | admin |
| `/users/edit/{id}` | GET | UserController | edit | admin |
| `/users/update/{id}` | POST | UserController | update | admin |
| `/users/delete/{id}` | POST | UserController | destroy | admin |

### Démo (pas d'auth)

`/demo`, `/demo/ui-kit`, `/demo/dashboard`, `/demo/users`, `/demo/user-detail`, `/demo/subscriptions`, `/demo/billing`, `/demo/analytics`, `/demo/logs`, `/demo/settings`, `/demo/notifications`, `/demo/support`

---

## 19. Journal des évolutions

> Mettre à jour cette section à chaque modification significative.

| Date | Description | Fichiers modifiés |
|---|---|---|
| _(à remplir)_ | Initialisation du starter | Tous |
| | | |

---

_Document généré avec le starter — à maintenir à jour tout au long du projet._
