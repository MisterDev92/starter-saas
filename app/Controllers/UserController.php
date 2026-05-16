<?php

namespace Controllers;

use Core\Controller;
use Core\MailService;
use Models\User;

class UserController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    // GET /users
    public function index(): void
    {
        $this->render('users/index', [
            'page_title' => 'Utilisateurs',
            'breadcrumb' => [['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Utilisateurs']],
            'users'      => $this->users->findAll('created_at', 'DESC'),
            'flash'      => $this->getFlash(),
        ]);
    }

    // GET /users/create
    public function create(): void
    {
        $this->render('users/create', [
            'page_title' => 'Nouvel utilisateur',
            'breadcrumb' => [['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Utilisateurs', 'url' => '/users'], ['label' => 'Nouveau']],
        ]);
    }

    // POST /users/store
    public function store(): void
    {
        csrf_verify();

        $name  = trim($_POST['name']  ?? '');
        $email = trim($_POST['email'] ?? '');
        $role  = $_POST['role']       ?? 'user';
        $errors = [];

        if ($name  === '') $errors[] = 'Nom requis.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
        if (!in_array($role, ['admin', 'user'], true)) $role = 'user';
        if (empty($errors) && $this->users->findByEmail($email)) $errors[] = 'Email déjà utilisé.';

        if (empty($errors)) {
            // Création sans mot de passe → envoi d'invitation
            $tempPassword = bin2hex(random_bytes(16));
            $userId = $this->users->create([
                'name'     => $name,
                'email'    => $email,
                'password' => password_hash($tempPassword, PASSWORD_BCRYPT),
                'role'     => $role,
            ]);

            // Générer un token d'invitation (24h)
            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));
            $this->users->storeResetToken($userId, $token, $expires);
            $setupUrl = url('/reset-password?token=' . urlencode($token));
            (new MailService())->sendInvitation($email, $name, $setupUrl);

            $this->flash('success', 'Utilisateur créé. Un email d\'invitation a été envoyé.');
            $this->redirect('/users');
        }

        $this->render('users/create', [
            'page_title' => 'Nouvel utilisateur',
            'breadcrumb' => [['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Utilisateurs', 'url' => '/users'], ['label' => 'Nouveau']],
            'errors'     => $errors,
            'old'        => $_POST,
        ]);
    }

    // GET /users/edit/{id}
    public function edit(string $id): void
    {
        $user = $this->users->findById((int) $id);
        if (!$user) {
            $this->flash('error', 'Utilisateur introuvable.');
            $this->redirect('/users');
        }

        $this->render('users/edit', [
            'page_title' => 'Modifier ' . e($user['name']),
            'breadcrumb' => [['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Utilisateurs', 'url' => '/users'], ['label' => 'Modifier']],
            'user'       => $user,
            'flash'      => $this->getFlash(),
        ]);
    }

    // POST /users/update/{id}
    public function update(string $id): void
    {
        csrf_verify();

        $user = $this->users->findById((int) $id);
        if (!$user) {
            $this->redirect('/users');
        }

        $name      = trim($_POST['name']      ?? '');
        $email     = trim($_POST['email']     ?? '');
        $role      = $_POST['role']            ?? 'user';
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        $errors    = [];

        if ($name  === '') $errors[] = 'Nom requis.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
        if (!in_array($role, ['admin', 'user'], true)) $role = 'user';

        // Vérifier unicité email (hors l'utilisateur lui-même)
        if (empty($errors)) {
            $existing = $this->users->findByEmail($email);
            if ($existing && (int) $existing['id'] !== (int) $id) {
                $errors[] = 'Email déjà utilisé par un autre compte.';
            }
        }

        if (empty($errors)) {
            $data = ['name' => $name, 'email' => $email, 'role' => $role, 'is_active' => $is_active];

            if (!empty($_POST['password']) && strlen($_POST['password']) >= 8) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_BCRYPT);
            }

            $this->users->update((int) $id, $data);
            $this->flash('success', 'Utilisateur mis à jour.');
            $this->redirect('/users');
        }

        $this->render('users/edit', [
            'page_title' => 'Modifier ' . e($user['name']),
            'breadcrumb' => [['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Utilisateurs', 'url' => '/users'], ['label' => 'Modifier']],
            'user'       => array_merge($user, $_POST),
            'errors'     => $errors,
            'flash'      => null,
        ]);
    }

    // POST /users/delete/{id}
    public function destroy(string $id): void
    {
        csrf_verify();

        $userId = (int) $id;

        // Empêcher l'auto-suppression
        if ($userId === (int) ($_SESSION['user_id'] ?? 0)) {
            $this->flash('error', 'Impossible de supprimer votre propre compte.');
            $this->redirect('/users');
        }

        $this->users->delete($userId);
        $this->flash('success', 'Utilisateur supprimé.');
        $this->redirect('/users');
    }
}
