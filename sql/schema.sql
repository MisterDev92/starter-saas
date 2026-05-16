-- Base SaaS — Schéma initial
-- Charset : utf8mb4_unicode_ci

CREATE TABLE IF NOT EXISTS users (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(100)                        NOT NULL,
  email       VARCHAR(150)                        NOT NULL UNIQUE,
  password    VARCHAR(255)                        NOT NULL,
  role        ENUM('admin','user')                NOT NULL DEFAULT 'user',
  avatar      VARCHAR(255)                        DEFAULT NULL,
  is_active   TINYINT(1)                          NOT NULL DEFAULT 1,
  last_login  DATETIME                            DEFAULT NULL,
  password_reset_token   VARCHAR(64)             DEFAULT NULL,
  password_reset_expires DATETIME                DEFAULT NULL,
  created_at  DATETIME                            NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME                            NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Utilisateurs de test
-- admin1234 et user1234 hashés avec password_hash(..., PASSWORD_BCRYPT)
INSERT INTO users (name, email, password, role) VALUES
  ('Admin', 'admin@test.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
  ('User',  'user@test.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');
-- Mot de passe par défaut: password  (à changer en production !)
-- Pour admin1234/user1234, utiliser php -r "echo password_hash('admin1234', PASSWORD_BCRYPT);"
