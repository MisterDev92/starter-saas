<?php

namespace Controllers;

use Core\Controller;
use Models\User;

class DashboardController extends Controller
{
    // GET /dashboard
    public function index(): void
    {
        $users = new User();

        $this->render('dashboard/index', [
            'page_title'   => 'Dashboard',
            'breadcrumb'   => [['label' => 'Dashboard']],
            'total_users'  => $users->count(),
            'active_users' => $users->countActive(),
            'flash'        => $this->getFlash(),
        ]);
    }
}
