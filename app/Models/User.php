<?php

namespace Models;

use Core\Model;
use Core\Database;
use PDO;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public function storeResetToken(int $id, string $token, string $expires): void
    {
        $this->update($id, [
            'password_reset_token'   => $token,
            'password_reset_expires' => $expires,
        ]);
    }

    public function findByResetToken(string $token): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE password_reset_token = :token
               AND password_reset_expires > NOW()
             LIMIT 1"
        );
        $stmt->execute([':token' => $token]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public function clearResetToken(int $id): void
    {
        $this->update($id, [
            'password_reset_token'   => null,
            'password_reset_expires' => null,
        ]);
    }

    public function updateLastLogin(int $id): void
    {
        $this->update($id, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function countActive(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE is_active = 1");
        return (int) $stmt->fetchColumn();
    }
}
