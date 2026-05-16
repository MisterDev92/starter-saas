<?php

namespace Controllers;

use Core\Controller;
use Core\MailService;
use Models\User;

class AuthController extends Controller
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    // GET /login
    public function loginForm(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->render('auth/login', ['flash' => $this->getFlash()], 'auth');
    }

    // POST /login
    public function login(): void
    {
        csrf_verify();

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $errors   = [];

        if ($email === '')    $errors[] = 'Email requis.';
        if ($password === '') $errors[] = 'Mot de passe requis.';

        if (empty($errors)) {
            $user = $this->users->findByEmail($email);
            if ($user && password_verify($password, $user['password']) && $user['is_active']) {
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_name']  = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role']  = $user['role'];
                $this->users->updateLastLogin((int) $user['id']);
                $this->redirect('/dashboard');
            }
            $errors[] = 'Email ou mot de passe incorrect.';
        }

        $this->render('auth/login', ['errors' => $errors, 'old' => $_POST, 'flash' => null], 'auth');
    }

    // GET /logout
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }

    // GET /register
    public function registerForm(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect('/dashboard');
        }
        $this->render('auth/register', ['flash' => $this->getFlash()], 'auth');
    }

    // POST /register
    public function register(): void
    {
        csrf_verify();

        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');
        $errors   = [];

        if ($name === '')    $errors[] = 'Nom requis.';
        if ($email === '')   $errors[] = 'Email requis.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
        if (strlen($password) < 8) $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';

        if (empty($errors) && $this->users->findByEmail($email)) {
            $errors[] = 'Cette adresse email est déjà utilisée.';
        }

        if (empty($errors)) {
            $this->users->create([
                'name'     => $name,
                'email'    => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role'     => 'user',
            ]);
            $this->flash('success', 'Compte créé. Vous pouvez vous connecter.');
            $this->redirect('/login');
        }

        $this->render('auth/register', ['errors' => $errors, 'old' => $_POST, 'flash' => null], 'auth');
    }

    // GET /forgot-password
    public function forgotForm(): void
    {
        $this->render('auth/forgot', ['flash' => $this->getFlash()], 'auth');
    }

    // POST /forgot-password
    public function forgot(): void
    {
        csrf_verify();

        $email = trim($_POST['email'] ?? '');

        if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user = $this->users->findByEmail($email);
            if ($user) {
                $token   = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+15 minutes'));
                $this->users->storeResetToken((int) $user['id'], $token, $expires);
                $resetUrl = url('/reset-password?token=' . urlencode($token));
                (new MailService())->sendPasswordReset($user['email'], $user['name'], $resetUrl);
            }
        }

        // Toujours le même message (anti-énumération)
        $this->flash('success', 'Si un compte correspond à cet email, un lien de réinitialisation a été envoyé.');
        $this->redirect('/forgot-password');
    }

    // GET /reset-password
    public function resetForm(): void
    {
        $token = $_GET['token'] ?? '';
        $user  = $token !== '' ? $this->users->findByResetToken($token) : null;

        if (!$user) {
            $this->flash('error', 'Lien invalide ou expiré.');
            $this->redirect('/forgot-password');
        }

        $this->render('auth/reset', ['token' => $token, 'flash' => $this->getFlash()], 'auth');
    }

    // POST /reset-password
    public function reset(): void
    {
        csrf_verify();

        $token    = $_POST['token']    ?? '';
        $password = $_POST['password'] ?? '';
        $errors   = [];

        $user = $token !== '' ? $this->users->findByResetToken($token) : null;

        if (!$user) {
            $this->flash('error', 'Lien invalide ou expiré.');
            $this->redirect('/forgot-password');
        }

        if (strlen($password) < 8) {
            $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
        }

        if (empty($errors)) {
            $this->users->update((int) $user['id'], [
                'password' => password_hash($password, PASSWORD_BCRYPT),
            ]);
            $this->users->clearResetToken((int) $user['id']);
            $this->flash('success', 'Mot de passe mis à jour. Connectez-vous.');
            $this->redirect('/login');
        }

        $this->render('auth/reset', ['token' => $token, 'errors' => $errors, 'flash' => null], 'auth');
    }
}
