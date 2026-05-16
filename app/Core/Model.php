<?php

namespace Core;

use PDO;

abstract class Model
{
    protected PDO $db;
    protected string $table = '';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findAll(string $orderBy = 'id', string $dir = 'DESC'): array
    {
        $dir  = strtoupper($dir) === 'ASC' ? 'ASC' : 'DESC';
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY {$orderBy} {$dir}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @return array|null
     */
    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row !== false ? $row : null;
    }

    public function findBy(string $column, $value): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} = :value");
        $stmt->execute([':value' => $value]);
        return $stmt->fetchAll();
    }

    public function create(array $data): int
    {
        $cols   = implode(', ', array_keys($data));
        $places = ':' . implode(', :', array_keys($data));
        $stmt   = $this->db->prepare("INSERT INTO {$this->table} ({$cols}) VALUES ({$places})");
        $params = [];
        foreach ($data as $k => $v) {
            $params[':' . $k] = $v;
        }
        $stmt->execute($params);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $sets   = implode(', ', array_map(fn($k) => "{$k} = :{$k}", array_keys($data)));
        $stmt   = $this->db->prepare("UPDATE {$this->table} SET {$sets} WHERE id = :id");
        $params = [':id' => $id];
        foreach ($data as $k => $v) {
            $params[':' . $k] = $v;
        }
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table}");
        return (int) $stmt->fetchColumn();
    }
}
