# PROMPT — Refactorisation Starter Base SaaS


---

```
Tu es un expert PHP senior et UI/UX developer.
Ton rôle est d'auditer ce projet existant et de le refactoriser 
en un starter propre, cohérent et réutilisable pour construire 
des SaaS rapidement.

════════════════════════════════════════
ÉTAPE 1 — AUDIT (ne touche rien encore)
════════════════════════════════════════

Lis tous les fichiers du projet et produis un rapport structuré :

- Point d'entrée actuel (index.php ? .htaccess ? autre ?)
- Présence ou non d'un Router et comment il fonctionne
- Structure des Controllers (héritage ? classes standalone ?)
- Structure des Models (PDO direct ? abstraction ?)
- Gestion de la config (en dur ? fichier séparé ? .env ?)
- Gestion des assets (CSS/JS — où et comment ?)
- Ce qui peut être gardé tel quel
- Ce qui doit être réécrit
- Ce qui doit être supprimé

Attends ma validation explicite avant de passer à l'étape 2.

════════════════════════════════════════
ÉTAPE 2 — STRUCTURE CIBLE
════════════════════════════════════════

Une fois validé, réorganise le projet selon cette structure exacte,
sans en dévier :

base-saas/
├── public/
│   ├── index.php                    ← front controller unique
│   ├── .htaccess                    ← redirige tout vers index.php
│   └── assets/
│       ├── css/
│       │   └── admin-custom.css     ← variables + surcharges + composants
│       └── js/
│           └── admin-custom.js      ← comportements globaux vanilla JS
│
├── app/
│   ├── Core/
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── Model.php
│   │   ├── Database.php
│   │   └── Helpers.php              ← fonctions globales (component(), e(), ...)
│   │
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── UserController.php
│   │   ├── DashboardController.php
│   │   └── DemoController.php       ← contrôleur pour les pages fake
│   │
│   ├── Models/
│   │   └── User.php
│   │
│   └── Views/
│       ├── layouts/
│       │   ├── admin.php            ← layout principal sidebar + topbar
│       │   ├── auth.php             ← layout centré login/register
│       │   └── print.php           ← layout épuré export/impression
│       │
│       ├── components/
│       │   ├── sidebar.php
│       │   ├── topbar.php
│       │   ├── breadcrumb.php
│       │   ├── stat-card.php
│       │   ├── stat-mini.php
│       │   ├── progress-bar.php
│       │   ├── table.php
│       │   ├── table-actions.php
│       │   ├── empty-state.php
│       │   ├── chart-line.php
│       │   ├── chart-bar.php
│       │   ├── chart-donut.php
│       │   ├── chart-area.php
│       │   ├── form-input.php
│       │   ├── form-select.php
│       │   ├── form-toggle.php
│       │   ├── form-upload.php
│       │   ├── form-errors.php
│       │   ├── card.php
│       │   ├── card-collapsible.php
│       │   ├── alert.php
│       │   ├── badge.php
│       │   ├── avatar.php
│       │   ├── modal.php
│       │   ├── log-line.php
│       │   ├── log-viewer.php
│       │   ├── code-block.php
│       │   ├── json-viewer.php
│       │   ├── pagination.php
│       │   ├── search-bar.php
│       │   ├── tabs.php
│       │   ├── timeline.php
│       │   └── tooltip.php
│       │
│       ├── auth/
│       │   ├── login.php
│       │   └── register.php
│       │
│       ├── dashboard/
│       │   └── index.php
│       │
│       ├── users/
│       │   ├── index.php
│       │   ├── create.php
│       │   └── edit.php
│       │
│       └── demo/
│           ├── ui-kit.php           ← tous les composants sur une page
│           ├── dashboard.php        ← dashboard fake données réalistes
│           ├── users-list.php       ← liste users fake avec actions
│           ├── user-detail.php      ← fiche user fake complète
│           ├── subscriptions.php    ← page abonnements/plans fake
│           ├── billing.php          ← page facturation fake
│           ├── analytics.php        ← page stats/graphiques fake
│           ├── logs.php             ← page logs système fake
│           ├── settings.php         ← page paramètres fake avec toggles
│           ├── notifications.php    ← centre de notifications fake
│           └── support.php          ← page support/tickets fake
│
├── config/
│   └── config.php                   ← lit le .env et définit les constantes
│
├── routes.php                       ← toutes les routes déclarées ici
├── .env                             ← valeurs locales (jamais commité)
├── .env.example                     ← template versionné sur Git
├── .gitignore
├── README.md
└── sql/
    └── schema.sql


════════════════════════════════════════
ÉTAPE 3 — RÈGLES BACK-END
════════════════════════════════════════

── ROUTEUR ──────────────────────────────

- Un seul Router.php qui gère GET, POST, PUT, DELETE
- Toutes les routes déclarées dans routes.php uniquement
- Zéro logique de routing dans .htaccess
- Support des paramètres dynamiques : /users/edit/{id}
- Méthode middleware() pour protéger des routes : requireAuth, requireAdmin
- En cas de 404, afficher une page d'erreur custom

Exemple d'utilisation dans routes.php :
$router->get('/dashboard', 'DashboardController', 'index', ['requireAuth']);
$router->get('/users', 'UserController', 'index', ['requireAuth', 'requireAdmin']);
$router->get('/users/edit/{id}', 'UserController', 'edit', ['requireAuth']);
$router->post('/users/update/{id}', 'UserController', 'update', ['requireAuth']);
$router->get('/demo/dashboard', 'DemoController', 'dashboard');


── CONFIG & ENVIRONNEMENT ───────────────

- Fichier .env à la racine, jamais commité
- Fichier .env.example avec toutes les clés sans valeurs, commité
- config.php lit le .env avec parse_ini_file()
- Zéro valeur en dur dans le code
- Constantes disponibles partout via define() :

  APP_ENV        (local | production)
  APP_URL        (http://localhost/base-saas)
  APP_NAME       (Mon SaaS)
  DB_HOST
  DB_NAME
  DB_USER
  DB_PASS
  DB_CHARSET     (utf8mb4)
  MAIL_HOST
  MAIL_PORT
  MAIL_USER
  MAIL_PASS
  MAIL_FROM

- En production (APP_ENV=production) :
  * HTTPS forcé via .htaccess
  * Erreurs PHP masquées (display_errors = 0)
  * Logs activés dans /logs/app.log


── .HTACCESS ────────────────────────────

- Compatible Apache mutualisé et VPS
- mod_rewrite : tout redirige vers public/index.php
- Les assets (css, js, images, fonts) sont servis directement
- HTTPS forcé si APP_ENV = production
- Protection : interdit l'accès direct aux dossiers app/, config/, sql/


── BASE DE DONNÉES ──────────────────────

- Pattern Singleton dans Database.php
- PDO uniquement, jamais mysqli
- PDO::ERRMODE_EXCEPTION activé
- PDO::FETCH_ASSOC par défaut
- Requêtes préparées obligatoires, zéro concaténation SQL
- Toutes les tables en utf8mb4_unicode_ci


── CONTROLLERS ──────────────────────────

Classe abstraite Controller.php avec :
- render(string $view, array $data, string $layout)
  * layout par défaut : 'admin'
  * fait extract($data) avant include
  * inclut automatiquement le bon layout
- redirect(string $path)
- json(array $data, int $code = 200)       ← pour futures routes API
- requireAuth()                             ← redirige vers /login si non connecté
- requireAdmin()                            ← redirige vers /dashboard si non admin
- flash(string $type, string $message)      ← stocke un message en session
- getFlash()                                ← récupère et vide le message flash

Chaque controller hérite de Controller.


── MODELS ───────────────────────────────

Classe abstraite Model.php avec :
- $db accessible via Database::getInstance()
- findAll(string $orderBy = 'id', string $dir = 'DESC') : array
- findById(int $id) : array|false
- findBy(string $column, mixed $value) : array
- create(array $data) : int              ← retourne lastInsertId
- update(int $id, array $data) : bool
- delete(int $id) : bool
- count() : int

Chaque model hérite de Model et peut surcharger ces méthodes.


── AUTH ─────────────────────────────────

- Session PHP native
- password_hash() / password_verify()
- Roles en BDD : 'admin' | 'user'
- À la connexion : stocker en session id, name, email, role
- Déconnexion : détruire la session complète
- Register : validation email unique, mot de passe min 8 caractères
- Protection CSRF sur tous les formulaires POST :
  * Générer un token dans la session
  * Vérifier le token à chaque POST
  * Helper csrf_token() et csrf_field() dans Helpers.php


── HELPERS ──────────────────────────────

Fichier Core/Helpers.php chargé automatiquement, contenant :

- e(string $value) : string
  → htmlspecialchars() raccourci, utilisé dans toutes les vues

- component(string $name, array $props = []) : void
  → résout le chemin views/components/{name}.php
  → fait extract($props) et inclut le fichier

- asset(string $path) : string
  → retourne APP_URL . '/assets/' . $path

- url(string $path) : string
  → retourne APP_URL . $path

- csrf_field() : string
  → retourne <input type="hidden" name="csrf_token" value="...">

- csrf_token() : string
  → génère ou retourne le token session

- active(string $path) : string
  → retourne 'active' si l'URL courante correspond au $path
  → utilisé dans le sidebar pour marquer l'item actif

- format_date(string $date, string $format = 'd/m/Y') : string
- format_money(float $amount, string $currency = '€') : string
- truncate(string $text, int $length = 100) : string


── SQL ──────────────────────────────────

Fichier sql/schema.sql avec :

CREATE TABLE users (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100) NOT NULL,
  email       VARCHAR(150) NOT NULL UNIQUE,
  password    VARCHAR(255) NOT NULL,
  role        ENUM('admin','user') NOT NULL DEFAULT 'user',
  avatar      VARCHAR(255) DEFAULT NULL,
  is_active   TINYINT(1) NOT NULL DEFAULT 1,
  last_login  DATETIME DEFAULT NULL,
  created_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP 
              ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

- Insérer 2 utilisateurs de test :
  * admin@test.com / password: admin1234 / role: admin
  * user@test.com  / password: user1234  / role: user


════════════════════════════════════════
ÉTAPE 4 — RÈGLES FRONT-END & DESIGN SYSTEM
════════════════════════════════════════

── BASE TECHNIQUE ───────────────────────

- [ADMINLTE / TABLER] chargé via CDN
- admin-custom.css pour toutes les surcharges et composants custom
- admin-custom.js pour tous les comportements, vanilla JS uniquement
- Chart.js via CDN pour les graphiques
- highlight.js via CDN pour la coloration syntaxique
- Zéro jQuery sauf contrainte du framework UI choisi


── VARIABLES CSS ────────────────────────

Dans admin-custom.css, définir en premier :

:root {
  /* Couleurs principales */
  --sa-primary:        #4f46e5;
  --sa-primary-light:  #818cf8;
  --sa-primary-dark:   #3730a3;
  --sa-danger:         #ef4444;
  --sa-success:        #22c55e;
  --sa-warning:        #f59e0b;
  --sa-info:           #3b82f6;

  /* Backgrounds */
  --sa-bg-sidebar:     #1e1e2d;
  --sa-bg-main:        #f1f5f9;
  --sa-bg-card:        #ffffff;
  --sa-bg-hover:       #f8fafc;

  /* Textes */
  --sa-text-main:      #1e293b;
  --sa-text-muted:     #64748b;
  --sa-text-light:     #94a3b8;
  --sa-text-inverse:   #ffffff;

  /* Bordures & effets */
  --sa-border:         #e2e8f0;
  --sa-radius-sm:      4px;
  --sa-radius:         8px;
  --sa-radius-lg:      12px;
  --sa-shadow-sm:      0 1px 3px rgba(0,0,0,0.06);
  --sa-shadow:         0 2px 8px rgba(0,0,0,0.08);
  --sa-shadow-lg:      0 8px 24px rgba(0,0,0,0.10);

  /* Espacements */
  --sa-space-xs:       4px;
  --sa-space-sm:       8px;
  --sa-space-md:       16px;
  --sa-space-lg:       24px;
  --sa-space-xl:       32px;

  /* Typographie */
  --sa-font:           'Inter', system-ui, sans-serif;
  --sa-font-mono:      'Fira Code', 'Courier New', monospace;
  --sa-text-sm:        0.875rem;
  --sa-text-base:      1rem;
  --sa-text-lg:        1.125rem;
  --sa-text-xl:        1.25rem;
  --sa-text-2xl:       1.5rem;

  /* Transitions */
  --sa-transition:     0.2s ease;
}

/* Dark mode */
body.dark {
  --sa-bg-main:        #0f172a;
  --sa-bg-card:        #1e293b;
  --sa-bg-hover:       #273344;
  --sa-text-main:      #f1f5f9;
  --sa-text-muted:     #94a3b8;
  --sa-border:         #334155;
}


── COMPOSANTS UI ────────────────────────

Chaque composant dans views/components/ reçoit ses données 
via un tableau $props extrait automatiquement par component().

Toutes les classes custom sont préfixées .sa-
Chaque composant a sa section dédiée dans admin-custom.css

NAVIGATION :

sidebar.php
- Logo + nom du SaaS en haut (APP_NAME)
- Items de menu avec icône, label, lien
- Détection automatique de l'item actif via active()
- Section séparée pour les items admin (visibles si role = admin)
- Collapse sur mobile
- Props : $menu_items (tableau), $user (session)

topbar.php
- Titre de la page courante
- Bouton toggle sidebar mobile
- Dropdown notifications avec badge compteur
- Dropdown profil user : avatar, nom, liens profil/déconnexion
- Bouton dark mode toggle
- Props : $page_title, $notifications (tableau)

breadcrumb.php
- Fil d'ariane généré automatiquement
- Props : $items [['label' => '', 'url' => ''], ...]


STATS & KPI :

stat-card.php
- Grande carte avec : icône, label, valeur, variation (delta + flèche)
- Couleur de l'icône selon $color (primary/success/danger/warning)
- Props : $label, $value, $delta, $delta_type (up|down), $icon, $color

stat-mini.php
- Version compacte, 4 par ligne
- Props : $label, $value, $color

progress-bar.php
- Barre avec label, pourcentage, couleur
- Props : $label, $value (0-100), $color


TABLEAUX :

table.php
- Tableau responsive avec header, body, footer
- Tri par colonne (JS)
- Intégration pagination
- Props : $headers (tableau), $rows (tableau), $actions (bool)

table-actions.php
- Colonne boutons : Voir / Éditer / Supprimer
- Supprimer déclenche une confirmation JS
- Props : $id, $base_url, $can_delete (bool)

empty-state.php
- Affiché quand tableau vide
- Icône + message + bouton optionnel
- Props : $message, $icon, $action_label, $action_url


GRAPHIQUES (Chart.js) :

Chaque composant graphique :
- Génère un canvas avec id unique
- Initialise le chart en JS uniquement si le canvas existe dans le DOM
- Données passées en PHP puis encodées en json_encode() pour le JS
- Responsive par défaut, tooltips activés

chart-line.php   → Props : $id, $labels, $datasets, $title
chart-bar.php    → Props : $id, $labels, $datasets, $title
chart-donut.php  → Props : $id, $labels, $values, $colors, $title
chart-area.php   → Props : $id, $labels, $datasets, $title


FORMULAIRES :

form-input.php
- Input avec label, placeholder, type, nom, valeur, message d'erreur
- Props : $name, $label, $type, $value, $placeholder, $error, $required

form-select.php
- Select avec label, options, valeur sélectionnée
- Props : $name, $label, $options [value => label], $selected, $error

form-toggle.php
- Toggle switch on/off avec label et état
- Props : $name, $label, $checked, $description

form-upload.php
- Zone drag & drop avec aperçu fichier
- Props : $name, $label, $accept, $max_size

form-errors.php
- Bloc récap de toutes les erreurs de validation
- Props : $errors (tableau)


CONTENUS :

card.php
- Bloc générique : header optionnel, body, footer optionnel
- Props : $title, $footer, $class

card-collapsible.php
- Card avec toggle ouvert/fermé en JS
- Props : $title, $open (bool par défaut)

alert.php
- Message contextuel avec icône et bouton fermer
- Props : $type (info|success|error|warning), $message, $dismissible

badge.php
- Étiquette inline colorée
- Props : $label, $type (active|inactive|pending|admin|user)

avatar.php
- Initiales colorées ou image si avatar présent
- Props : $name, $avatar_url, $size (sm|md|lg)

modal.php
- Fenêtre modale avec id unique, titre, corps, boutons
- Ouverture via data-modal-target="#id" en HTML
- Props : $id, $title, $size (sm|md|lg)


LOGS & TECHNIQUE :

log-line.php
- Une ligne de log : timestamp, niveau (INFO/WARN/ERROR), message
- Couleur selon le niveau
- Props : $timestamp, $level, $message, $context

log-viewer.php
- Bloc scrollable avec filtre par niveau (JS)
- Bouton copier tout le log
- Props : $logs (tableau de log-line)

code-block.php
- Bloc code avec coloration highlight.js
- Bouton copier dans le presse-papier
- Props : $code, $language, $title

json-viewer.php
- JSON formaté et coloré
- Expandable/collapsible
- Props : $data (tableau PHP encodé en JSON)


NAVIGATION & UX :

pagination.php
- Liens prev/next + numéros de page
- Props : $current, $total, $base_url

search-bar.php
- Input recherche avec filtre live sur tableau (JS)
- Props : $placeholder, $target (sélecteur CSS du tableau)

tabs.php
- Onglets avec contenu switchable en JS sans rechargement
- Props : $tabs [['label' => '', 'content' => '']]

timeline.php
- Fil chronologique vertical
- Props : $events [['date', 'title', 'description', 'icon', 'color']]

tooltip.php
- Infobulle au survol via attribut data-tooltip
- Géré globalement en JS, pas besoin de composant par instance


── LAYOUTS ──────────────────────────────

layouts/admin.php :
- Charge les CDN en haut (CSS)
- Inclut sidebar.php et topbar.php
- Zone principale avec <?= $content ?>
- Flash messages affichés automatiquement si présents en session
- Charge les CDN en bas (JS) + admin-custom.js
- Initialisation globale Chart.js, highlight.js, tooltips, modals

layouts/auth.php :
- Page centrée, fond dégradé
- Logo + APP_NAME
- Card blanche avec le formulaire
- Pas de sidebar ni topbar

layouts/print.php :
- CSS minimal, blanc, sans navigation
- En-tête avec APP_NAME + date d'impression
- Optimisé @media print


── JAVASCRIPT GLOBAL ────────────────────

admin-custom.js doit contenir ces fonctions nommées :

initTooltips()           → active tous les [data-tooltip]
initModals()             → gère ouverture/fermeture modals
initCollapsibles()       → gère les cards collapsibles
initDarkMode()           → toggle dark + sauvegarde localStorage
initSidebarMobile()      → toggle sidebar sur mobile
initSearchFilter()       → filtre live sur tableaux
initSortableTable()      → tri colonnes par clic header
initDeleteConfirm()      → confirmation avant suppression
initDismissAlerts()      → fermeture des alertes
initCharts()             → stub appelé par chaque page avec ses données
initHighlight()          → coloration syntaxique highlight.js
initJsonViewer()         → expand/collapse json-viewer
initCopyButtons()        → copier code/log dans presse-papier
initFormValidation()     → validation côté client basique

Appel global en bas de layouts/admin.php :
document.addEventListener('DOMContentLoaded', function() {
  initTooltips();
  initModals();
  initCollapsibles();
  initDarkMode();
  initSidebarMobile();
  initSearchFilter();
  initSortableTable();
  initDeleteConfirm();
  initDismissAlerts();
  initHighlight();
  initJsonViewer();
  initCopyButtons();
  initFormValidation();
});


════════════════════════════════════════
ÉTAPE 5 — PAGES DE DÉMONSTRATION (FAKE)
════════════════════════════════════════

Toutes les pages demo/ utilisent des données statiques faker 
codées en dur dans le DemoController.
Elles sont accessibles sans authentification pour prévisualiser 
le BO rapidement.
Chaque page doit être réaliste, avec suffisamment de données 
pour évaluer le rendu.
Ajouter un bandeau en haut : "Mode démonstration — données fictives"

Routes demo à créer dans routes.php :
$router->get('/demo',                  'DemoController', 'index');
$router->get('/demo/ui-kit',           'DemoController', 'uiKit');
$router->get('/demo/dashboard',        'DemoController', 'dashboard');
$router->get('/demo/users',            'DemoController', 'users');
$router->get('/demo/user-detail',      'DemoController', 'userDetail');
$router->get('/demo/subscriptions',    'DemoController', 'subscriptions');
$router->get('/demo/billing',          'DemoController', 'billing');
$router->get('/demo/analytics',        'DemoController', 'analytics');
$router->get('/demo/logs',             'DemoController', 'logs');
$router->get('/demo/settings',         'DemoController', 'settings');
$router->get('/demo/notifications',    'DemoController', 'notifications');
$router->get('/demo/support',          'DemoController', 'support');

CONTENU DE CHAQUE PAGE DEMO :

demo/index → Index des démos
- Grille de cards avec lien vers chaque page demo
- Description courte de chaque page
- Bouton retour vers /dashboard

demo/ui-kit → Bibliothèque de composants
- TOUS les composants affichés avec titre de section
- Chaque composant montré en plusieurs variantes (couleurs, tailles)
- Le code PHP d'appel affiché en dessous via code-block.php
- Menu ancre latéral pour naviguer entre sections

demo/dashboard → Dashboard SaaS exemple
- 4 stat-cards : Revenus, Users actifs, Nouveaux abonnés, Churn
- 2 graphiques : Revenus sur 12 mois (line), Répartition plans (donut)
- Tableau 5 derniers users inscrits
- Timeline 5 dernières activités
- Bloc alertes système

demo/users → Liste utilisateurs
- 15 users fake avec : avatar, nom, email, role, statut, date inscription
- Barre de recherche live
- Filtres : tous / admin / user / inactifs
- Pagination (fake)
- Actions : voir / éditer / désactiver / supprimer

demo/user-detail → Fiche utilisateur
- Avatar grand format + infos principales + badge rôle
- Onglets : Informations / Activité / Abonnement / Logs
- Timeline d'activité sur 30 jours
- Graphique connexions par semaine

demo/subscriptions → Gestion abonnements
- 3 plans tarifaires avec features (Free / Pro / Enterprise)
- Tableau abonnés par plan avec statut (actif/expiré/annulé)
- stat-cards : MRR, ARR, Churn rate, Trial actifs
- Graphique évolution MRR sur 6 mois

demo/billing → Facturation
- Tableau factures : numéro, client, montant, statut, date
- Statuts : payée / en attente / échouée / remboursée
- Filtre par statut
- Bouton télécharger (fake)
- Total encaissé du mois en stat-card

demo/analytics → Statistiques
- stat-cards : Visiteurs, Pages vues, Taux conversion, Taux rebond
- Graphique trafic 30 jours (area)
- Graphique sources trafic (bar)
- Graphique devices (donut : desktop/mobile/tablet)
- Tableau pages les plus visitées

demo/logs → Logs système
- log-viewer avec 30 lignes fake (mix INFO / WARN / ERROR)
- Filtre par niveau
- Filtre par date
- Bouton vider les logs (fake avec confirm)
- Bouton exporter (fake)

demo/settings → Paramètres
- Onglets : Général / Email / Sécurité / Intégrations / Danger zone
- Général : nom du SaaS, URL, langue, timezone
- Email : SMTP host/port/user, email expéditeur, email test
- Sécurité : 2FA toggle, durée session, IP whitelist
- Intégrations : toggles Stripe / Mailchimp / Slack / Zapier avec clé API
- Danger zone : vider le cache, reset BDD, supprimer le compte

demo/notifications → Centre de notifications
- Liste 20 notifications fake avec : icône, titre, texte, date, lu/non lu
- Filtres : toutes / non lues / système / utilisateurs
- Bouton tout marquer comme lu
- Badge compteur non lus dans la topbar

demo/support → Tickets support
- Tableau tickets : id, sujet, user, priorité, statut, date
- Priorités : low / medium / high / urgent (badges colorés)
- Statuts : ouvert / en cours / résolu / fermé
- Fiche ticket au clic (modal) avec fil de réponses fake
- stat-cards : tickets ouverts, temps réponse moyen, satisfaction


════════════════════════════════════════
ÉTAPE 6 — FICHIERS OBLIGATOIRES
════════════════════════════════════════

.env.example :
APP_ENV=local
APP_URL=http://localhost/base-saas
APP_NAME=Mon SaaS
DB_HOST=localhost
DB_NAME=base_saas
DB_USER=root
DB_PASS=
DB_CHARSET=utf8mb4
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USER=
MAIL_PASS=
MAIL_FROM=noreply@monsaas.com

.gitignore :
.env
/vendor/
/logs/
*.log
.DS_Store
Thumbs.db
/public/uploads/

README.md doit contenir :

# Installation locale
1. Cloner le repo
2. Copier .env.example en .env et remplir les valeurs
3. Importer sql/schema.sql dans la BDD
4. Configurer l'hôte virtuel pour pointer vers /public
5. Accéder à http://localhost/base-saas/demo pour vérifier

# Déploiement production
1. Uploader les fichiers (hors .env, hors sql/)
2. Créer le .env sur le serveur avec APP_ENV=production
3. Importer sql/schema.sql sur la BDD de prod
4. Vérifier que mod_rewrite est activé
5. Accéder au domaine — le .htaccess force HTTPS automatiquement

# Ajouter un module
1. Créer app/Models/MonModule.php qui extends Model
2. Créer app/Controllers/MonModuleController.php qui extends Controller
3. Créer app/Views/mon-module/ avec les vues nécessaires
4. Ajouter les routes dans routes.php
5. Ajouter l'item dans le menu sidebar.php


════════════════════════════════════════
ÉTAPE 7 — VÉRIFICATION FINALE
════════════════════════════════════════

Avant de terminer, vérifie chaque point :

BACK-END
- [ ] Aucune valeur en dur dans le code (BDD, URL, clés)
- [ ] Toutes les routes déclarées dans routes.php uniquement
- [ ] Aucun fichier PHP accessible directement hors public/
- [ ] Chaque Controller hérite de Controller.php
- [ ] Chaque Model hérite de Model.php
- [ ] Requêtes préparées partout, zéro concaténation SQL
- [ ] e() ou htmlspecialchars() sur toutes les variables dans les vues
- [ ] Token CSRF présent sur tous les formulaires POST
- [ ] .env absent = erreur bloquante dans config.php
- [ ] Passage local → prod = uniquement changer .env

FRONT-END
- [ ] Toutes les variables CSS définies dans :root
- [ ] Tous les composants fonctionnent via component()
- [ ] Zéro style inline dans les vues
- [ ] Toutes les classes custom préfixées .sa-
- [ ] Dark mode fonctionnel via body.dark
- [ ] Responsive validé : sidebar collapse sur mobile
- [ ] Charts initialisés uniquement si canvas présent dans le DOM
- [ ] Confirmation JS avant toute action destructrice
- [ ] Toutes les fonctions JS nommées et appelées dans DOMContentLoaded

PAGES DEMO
- [ ] Toutes les routes /demo/* accessibles sans auth
- [ ] Bandeau "Mode démonstration" présent sur chaque page
- [ ] Données suffisamment réalistes pour évaluer le rendu
- [ ] ui-kit.php montre bien TOUS les composants

Si un point échoue, corriger avant de terminer.
Produire un rapport final listant ce qui a été créé, modifié, supprimé.
```
