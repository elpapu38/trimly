<?php
// ============================================================
//  app/Models/BaseModel.php
// ============================================================
namespace App\Models;

use PDO;

abstract class BaseModel
{
    protected PDO    $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // ── Lectura ───────────────────────────────────────────────

    public function find(int $id): array|false
    {
        $st = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1");
        $st->execute([$id]);
        return $st->fetch();
    }

    public function findBy(string $column, mixed $value): array|false
    {
        $st = $this->db->prepare("SELECT * FROM {$this->table} WHERE `$column` = ? LIMIT 1");
        $st->execute([$value]);
        return $st->fetch();
    }

    public function all(string $orderBy = 'id', string $dir = 'ASC'): array
    {
        $dir = strtoupper($dir) === 'DESC' ? 'DESC' : 'ASC';
        return $this->db->query("SELECT * FROM {$this->table} ORDER BY `$orderBy` $dir")->fetchAll();
    }

    public function paginate(int $page, int $perPage, string $where = '1', array $params = []): array
    {
        $offset = ($page - 1) * $perPage;
        $total  = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE $where");
        $total->execute($params);
        $count  = (int) $total->fetchColumn();

        $st = $this->db->prepare("SELECT * FROM {$this->table} WHERE $where LIMIT $perPage OFFSET $offset");
        $st->execute($params);

        return [
            'data'         => $st->fetchAll(),
            'total'        => $count,
            'per_page'     => $perPage,
            'current_page' => $page,
            'last_page'    => (int) ceil($count / $perPage),
        ];
    }

    // ── Escritura ─────────────────────────────────────────────

    public function insert(array $data): int
    {
        $cols        = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $st          = $this->db->prepare("INSERT INTO {$this->table} ($cols) VALUES ($placeholders)");
        $st->execute(array_values($data));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $set = implode(', ', array_map(fn($k) => "`$k` = ?", array_keys($data)));
        $st  = $this->db->prepare("UPDATE {$this->table} SET $set WHERE {$this->primaryKey} = ?");
        return $st->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool
    {
        $st = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $st->execute([$id]);
    }

    // ── Utilidades ────────────────────────────────────────────

    public function exists(string $column, mixed $value, ?int $excludeId = null): bool
    {
        $sql = "SELECT 1 FROM {$this->table} WHERE `$column` = ?";
        $params = [$value];
        if ($excludeId !== null) {
            $sql    .= " AND {$this->primaryKey} != ?";
            $params[] = $excludeId;
        }
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return (bool) $st->fetchColumn();
    }

    public function raw(string $sql, array $params = []): array
    {
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function rawOne(string $sql, array $params = []): array|false
    {
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetch();
    }

    public function count(string $where = '1', array $params = []): int
    {
        $st = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE $where");
        $st->execute($params);
        return (int) $st->fetchColumn();
    }
}
