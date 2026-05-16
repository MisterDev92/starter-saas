<?php

namespace Controllers;

use Core\Controller;

class DemoController extends Controller
{
    // GET /demo
    public function index(): void
    {
        $this->render('demo/index', [
            'page_title' => 'Démo — Index',
            'breadcrumb' => [['label' => 'Démo']],
        ]);
    }

    // GET /demo/ui-kit
    public function uiKit(): void
    {
        $this->render('demo/ui-kit', [
            'page_title' => 'UI Kit — Composants',
            'breadcrumb' => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'UI Kit']],
        ]);
    }

    // GET /demo/dashboard
    public function dashboard(): void
    {
        $this->render('demo/dashboard', [
            'page_title'   => 'Dashboard',
            'breadcrumb'   => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'Dashboard']],
            'stats' => [
                ['label' => 'Revenus MRR',       'value' => '24 800 €',  'delta' => '+12%', 'delta_type' => 'up',   'icon' => 'currency-euro',  'color' => 'primary'],
                ['label' => 'Users actifs',       'value' => '1 284',     'delta' => '+8%',  'delta_type' => 'up',   'icon' => 'users',          'color' => 'success'],
                ['label' => 'Nouveaux abonnés',   'value' => '143',       'delta' => '+3%',  'delta_type' => 'up',   'icon' => 'user-plus',      'color' => 'info'],
                ['label' => 'Churn rate',         'value' => '2,4 %',     'delta' => '-0,3%','delta_type' => 'down', 'icon' => 'trending-down',  'color' => 'warning'],
            ],
            'chart_labels'   => ['Jan','Fév','Mar','Avr','Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc'],
            'chart_revenue'  => [18200,19400,21000,20200,22800,23100,21900,24500,23800,25100,24000,24800],
            'chart_plans'    => [540, 320, 80],
            'chart_plan_labels' => ['Pro', 'Free', 'Enterprise'],
            'recent_users'  => $this->fakeUsers(5),
            'activities'    => $this->fakeActivities(5),
        ]);
    }

    // GET /demo/users
    public function users(): void
    {
        $this->render('demo/users-list', [
            'page_title' => 'Utilisateurs',
            'breadcrumb' => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'Utilisateurs']],
            'users'      => $this->fakeUsers(15),
        ]);
    }

    // GET /demo/user-detail
    public function userDetail(): void
    {
        $this->render('demo/user-detail', [
            'page_title' => 'Fiche utilisateur',
            'breadcrumb' => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'Utilisateurs', 'url' => '/demo/users'], ['label' => 'Fiche']],
            'user'       => $this->fakeUsers(1)[0],
            'activities' => $this->fakeActivities(10),
            'chart_logins' => [3, 5, 2, 7, 4, 6, 3, 8, 5, 4, 6, 7],
            'chart_weeks'  => ['S1','S2','S3','S4','S5','S6','S7','S8','S9','S10','S11','S12'],
        ]);
    }

    // GET /demo/subscriptions
    public function subscriptions(): void
    {
        $this->render('demo/subscriptions', [
            'page_title' => 'Abonnements',
            'breadcrumb' => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'Abonnements']],
            'stats' => [
                ['label' => 'MRR',          'value' => '24 800 €', 'delta' => '+12%',  'delta_type' => 'up',   'icon' => 'chart-line',   'color' => 'primary'],
                ['label' => 'ARR',          'value' => '297 600 €','delta' => '+12%',  'delta_type' => 'up',   'icon' => 'calendar',     'color' => 'success'],
                ['label' => 'Churn rate',   'value' => '2,4 %',    'delta' => '-0,3%', 'delta_type' => 'down', 'icon' => 'trending-down','color' => 'warning'],
                ['label' => 'Trials actifs','value' => '38',        'delta' => '+5',    'delta_type' => 'up',   'icon' => 'clock',        'color' => 'info'],
            ],
            'subscriptions'  => $this->fakeSubscriptions(12),
            'chart_mrr'      => [18200,19400,21000,20200,22800,24800],
            'chart_mrr_labels' => ['Jan','Fév','Mar','Avr','Mai','Juin'],
        ]);
    }

    // GET /demo/billing
    public function billing(): void
    {
        $this->render('demo/billing', [
            'page_title' => 'Facturation',
            'breadcrumb' => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'Facturation']],
            'invoices'   => $this->fakeInvoices(15),
            'total_month' => '8 640 €',
        ]);
    }

    // GET /demo/analytics
    public function analytics(): void
    {
        $this->render('demo/analytics', [
            'page_title'    => 'Analytics',
            'breadcrumb'    => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'Analytics']],
            'stats' => [
                ['label' => 'Visiteurs',       'value' => '12 843',  'delta' => '+18%', 'delta_type' => 'up',   'icon' => 'eye',          'color' => 'primary'],
                ['label' => 'Pages vues',      'value' => '48 291',  'delta' => '+22%', 'delta_type' => 'up',   'icon' => 'file-text',    'color' => 'success'],
                ['label' => 'Taux conversion', 'value' => '3,8 %',   'delta' => '+0,5%','delta_type' => 'up',   'icon' => 'target',       'color' => 'info'],
                ['label' => 'Taux rebond',     'value' => '42,1 %',  'delta' => '-3%',  'delta_type' => 'down', 'icon' => 'arrow-bounce', 'color' => 'warning'],
            ],
            'chart_traffic_labels'  => array_map(fn($i) => date('d/m', strtotime("-{$i} days")), range(29, 0)),
            'chart_traffic'         => [320,410,380,450,490,520,480,610,580,640,590,710,680,730,700,760,720,800,780,820,790,840,810,860,830,880,850,890,870,910],
            'chart_sources'         => [4200, 3100, 2800, 1900, 1200],
            'chart_sources_labels'  => ['Recherche organique','Accès direct','Réseaux sociaux','Emailing','Référents'],
            'chart_devices'         => [58, 35, 7],
            'chart_devices_labels'  => ['Desktop','Mobile','Tablette'],
            'top_pages'             => $this->fakeTopPages(),
        ]);
    }

    // GET /demo/logs
    public function logs(): void
    {
        $this->render('demo/logs', [
            'page_title' => 'Logs système',
            'breadcrumb' => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'Logs']],
            'logs'       => $this->fakeLogs(30),
        ]);
    }

    // GET /demo/settings
    public function settings(): void
    {
        $this->render('demo/settings', [
            'page_title' => 'Paramètres',
            'breadcrumb' => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'Paramètres']],
        ]);
    }

    // GET /demo/notifications
    public function notifications(): void
    {
        $notifs = $this->fakeNotifications(20);
        $this->render('demo/notifications', [
            'page_title'    => 'Notifications',
            'breadcrumb'    => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'Notifications']],
            'notifications' => $notifs,
            'unread_count'  => count(array_filter($notifs, fn($n) => !$n['read'])),
        ]);
    }

    // GET /demo/support
    public function support(): void
    {
        $this->render('demo/support', [
            'page_title' => 'Support',
            'breadcrumb' => [['label' => 'Démo', 'url' => '/demo'], ['label' => 'Support']],
            'stats' => [
                ['label' => 'Tickets ouverts',       'value' => '14',      'delta' => '+2',    'delta_type' => 'up',   'icon' => 'ticket',      'color' => 'warning'],
                ['label' => 'Temps réponse moyen',   'value' => '3,2 h',   'delta' => '-0,8h', 'delta_type' => 'down', 'icon' => 'clock',       'color' => 'info'],
                ['label' => 'Satisfaction client',   'value' => '94 %',    'delta' => '+2%',   'delta_type' => 'up',   'icon' => 'mood-happy',  'color' => 'success'],
                ['label' => 'Tickets résolus (7j)',  'value' => '48',      'delta' => '+12',   'delta_type' => 'up',   'icon' => 'check-circle','color' => 'primary'],
            ],
            'tickets' => $this->fakeTickets(14),
        ]);
    }

    // ─── Données fake ────────────────────────────────────────────────────────

    private function fakeUsers(int $count): array
    {
        $names   = ['Alice Martin','Bob Dupont','Claire Moreau','David Bernard','Emma Petit','François Dubois','Grace Leroy','Hugo Simon','Iris Thomas','Jules Girard','Karine Robert','Luc Richard','Marie Durand','Nicolas Morel','Olivia Laurent'];
        $roles   = ['admin','user','user','user','user','user','admin','user','user','user','user','user','user','user','user'];
        $statuses = [1,1,1,1,0,1,1,1,0,1,1,1,1,0,1];
        $plans   = ['Pro','Free','Enterprise','Pro','Free','Pro','Enterprise','Free','Pro','Free','Pro','Free','Pro','Free','Pro'];
        $users   = [];
        for ($i = 0; $i < $count; $i++) {
            $name = $names[$i % count($names)];
            $parts = explode(' ', $name);
            $users[] = [
                'id'         => $i + 1,
                'name'       => $name,
                'email'      => strtolower($parts[0]) . '.' . strtolower($parts[1]) . '@example.com',
                'role'       => $roles[$i % count($roles)],
                'is_active'  => $statuses[$i % count($statuses)],
                'plan'       => $plans[$i % count($plans)],
                'created_at' => date('Y-m-d H:i:s', strtotime('-' . (rand(1, 365)) . ' days')),
                'last_login' => date('Y-m-d H:i:s', strtotime('-' . (rand(0, 30))  . ' days')),
                'avatar'     => null,
            ];
        }
        return $users;
    }

    private function fakeActivities(int $count): array
    {
        $templates = [
            ['icon' => 'user-plus',     'color' => 'success', 'title' => 'Nouveau compte',        'desc' => 'Alice Martin a créé son compte'],
            ['icon' => 'credit-card',   'color' => 'primary', 'title' => 'Abonnement souscrit',   'desc' => 'Bob Dupont a souscrit au plan Pro'],
            ['icon' => 'alert-triangle','color' => 'warning', 'title' => 'Paiement échoué',        'desc' => 'Carte refusée pour Claire Moreau'],
            ['icon' => 'settings',      'color' => 'info',    'title' => 'Paramètres modifiés',    'desc' => 'Admin a modifié les paramètres SMTP'],
            ['icon' => 'user-minus',    'color' => 'danger',  'title' => 'Résiliation',            'desc' => 'David Bernard a résilié son abonnement'],
        ];
        $activities = [];
        for ($i = 0; $i < $count; $i++) {
            $tpl = $templates[$i % count($templates)];
            $activities[] = array_merge($tpl, [
                'date' => date('Y-m-d H:i:s', strtotime('-' . ($i * 3 + rand(0, 2)) . ' hours')),
            ]);
        }
        return $activities;
    }

    private function fakeSubscriptions(int $count): array
    {
        $plans    = ['Free','Pro','Enterprise'];
        $statuses = ['active','active','expired','cancelled','active','active','active','expired','active','active','cancelled','active'];
        $names    = ['Alice Martin','Bob Dupont','Claire Moreau','David Bernard','Emma Petit','François Dubois','Grace Leroy','Hugo Simon','Iris Thomas','Jules Girard','Karine Robert','Luc Richard'];
        $subs = [];
        for ($i = 0; $i < $count; $i++) {
            $subs[] = [
                'id'         => $i + 1,
                'user'       => $names[$i % count($names)],
                'plan'       => $plans[$i % count($plans)],
                'status'     => $statuses[$i % count($statuses)],
                'amount'     => $plans[$i % count($plans)] === 'Enterprise' ? '199 €' : ($plans[$i % count($plans)] === 'Pro' ? '49 €' : '0 €'),
                'renewal'    => date('Y-m-d', strtotime('+' . rand(1, 60) . ' days')),
            ];
        }
        return $subs;
    }

    private function fakeInvoices(int $count): array
    {
        $statuses = ['paid','paid','pending','paid','failed','paid','refunded','paid','paid','paid','pending','paid','paid','failed','paid'];
        $names    = ['Alice Martin','Bob Dupont','Claire Moreau','David Bernard','Emma Petit','François Dubois','Grace Leroy','Hugo Simon','Iris Thomas','Jules Girard','Karine Robert','Luc Richard','Marie Durand','Nicolas Morel','Olivia Laurent'];
        $amounts  = [49, 199, 49, 49, 199, 49, 49, 199, 49, 49, 49, 199, 49, 49, 49];
        $invoices = [];
        for ($i = 0; $i < $count; $i++) {
            $invoices[] = [
                'id'       => 'INV-' . str_pad((string)($i + 1001), 5, '0', STR_PAD_LEFT),
                'client'   => $names[$i % count($names)],
                'amount'   => $amounts[$i % count($amounts)] . ' €',
                'status'   => $statuses[$i % count($statuses)],
                'date'     => date('Y-m-d', strtotime('-' . ($i * 3) . ' days')),
            ];
        }
        return $invoices;
    }

    private function fakeTopPages(): array
    {
        return [
            ['url' => '/dashboard',    'views' => 8420, 'time' => '2:34'],
            ['url' => '/users',        'views' => 5210, 'time' => '1:52'],
            ['url' => '/pricing',      'views' => 4830, 'time' => '3:10'],
            ['url' => '/settings',     'views' => 3920, 'time' => '4:20'],
            ['url' => '/analytics',    'views' => 3540, 'time' => '2:48'],
            ['url' => '/login',        'views' => 3210, 'time' => '0:45'],
            ['url' => '/billing',      'views' => 2980, 'time' => '2:05'],
        ];
    }

    private function fakeLogs(int $count): array
    {
        $templates = [
            ['level' => 'INFO',  'message' => 'Connexion réussie',              'context' => 'user=alice@example.com ip=192.168.1.1'],
            ['level' => 'INFO',  'message' => 'Route dispatched: GET /dashboard','context' => 'ctrl=DashboardController time=12ms'],
            ['level' => 'WARN',  'message' => 'Tentative de connexion échouée', 'context' => 'email=hacker@spam.io ip=45.33.32.156'],
            ['level' => 'ERROR', 'message' => 'PDO Exception: SQLSTATE[42S02]', 'context' => 'table=undefined line=84'],
            ['level' => 'INFO',  'message' => 'Email envoyé via Mandrill',      'context' => 'to=bob@example.com subject=Invitation'],
            ['level' => 'WARN',  'message' => 'Session expirée',                'context' => 'user_id=42 last_seen=3600s ago'],
            ['level' => 'ERROR', 'message' => 'Fichier introuvable',            'context' => 'path=/app/Views/missing.php'],
            ['level' => 'INFO',  'message' => 'Utilisateur créé',               'context' => 'id=143 email=new@example.com'],
            ['level' => 'INFO',  'message' => 'Mot de passe réinitialisé',      'context' => 'user_id=88'],
            ['level' => 'WARN',  'message' => 'Upload refusé: type MIME invalide','context' => 'file=virus.exe mime=application/x-msdownload'],
        ];
        $logs = [];
        for ($i = 0; $i < $count; $i++) {
            $tpl = $templates[$i % count($templates)];
            $logs[] = array_merge($tpl, [
                'timestamp' => date('Y-m-d H:i:s', strtotime('-' . ($count - $i) . ' minutes')),
            ]);
        }
        return array_reverse($logs);
    }

    private function fakeNotifications(int $count): array
    {
        $templates = [
            ['icon' => 'user-plus',     'color' => 'success', 'title' => 'Nouveau compte',      'text' => 'Alice Martin vient de créer un compte.',       'type' => 'user'],
            ['icon' => 'credit-card',   'color' => 'primary', 'title' => 'Paiement reçu',       'text' => 'Facture INV-1042 réglée (49 €).',             'type' => 'system'],
            ['icon' => 'alert-triangle','color' => 'warning', 'title' => 'Paiement échoué',     'text' => 'Carte refusée pour Bob Dupont.',               'type' => 'system'],
            ['icon' => 'message-circle','color' => 'info',    'title' => 'Nouveau ticket',       'text' => 'Claire Moreau a ouvert un ticket #108.',       'type' => 'user'],
            ['icon' => 'shield-check',  'color' => 'success', 'title' => 'Sécurité',             'text' => 'Connexion depuis un nouvel appareil détectée.','type' => 'system'],
            ['icon' => 'user-minus',    'color' => 'danger',  'title' => 'Résiliation',          'text' => 'David Bernard a résilié son abonnement Pro.',  'type' => 'user'],
            ['icon' => 'bell',          'color' => 'info',    'title' => 'Rappel',               'text' => 'Rapport hebdomadaire disponible.',             'type' => 'system'],
        ];
        $notifs = [];
        for ($i = 0; $i < $count; $i++) {
            $tpl = $templates[$i % count($templates)];
            $notifs[] = array_merge($tpl, [
                'id'   => $i + 1,
                'date' => date('Y-m-d H:i:s', strtotime('-' . ($i * 2 + rand(0, 1)) . ' hours')),
                'read' => $i >= 7,
            ]);
        }
        return $notifs;
    }

    private function fakeTickets(int $count): array
    {
        $subjects    = ['Impossible de se connecter','Erreur paiement','Bug export CSV','Question facturation','Intégration API','Compte bloqué','Remboursement demandé','Performance lente','Email non reçu','Fonctionnalité manquante','Problème upload','2FA ne fonctionne pas','Mauvaise facture','Accès refusé','Demande devis Enterprise'];
        $priorities  = ['high','medium','low','urgent','medium','high','low','medium','high','low','urgent','medium','high','low','medium'];
        $statuses    = ['open','in_progress','resolved','open','open','in_progress','closed','open','resolved','open','in_progress','open','closed','open','open'];
        $users       = ['Alice M.','Bob D.','Claire M.','David B.','Emma P.','François D.','Grace L.','Hugo S.','Iris T.','Jules G.','Karine R.','Luc R.','Marie D.','Nicolas M.','Olivia L.'];
        $tickets = [];
        for ($i = 0; $i < $count; $i++) {
            $tickets[] = [
                'id'       => '#' . str_pad((string)($i + 101), 4, '0', STR_PAD_LEFT),
                'subject'  => $subjects[$i % count($subjects)],
                'user'     => $users[$i % count($users)],
                'priority' => $priorities[$i % count($priorities)],
                'status'   => $statuses[$i % count($statuses)],
                'date'     => date('Y-m-d', strtotime('-' . rand(0, 30) . ' days')),
            ];
        }
        return $tickets;
    }
}
