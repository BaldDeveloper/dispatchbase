<?php
require_once __DIR__ . '/Database.php';

class PouchData {
    private Database $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM pouch ORDER BY pouch_id DESC");
    }

    public function findById(int $pouch_id): ?array {
        $result = $this->db->query("SELECT * FROM pouch WHERE pouch_id = ?", [$pouch_id]);
        return $result[0] ?? null;
    }

    public function findByType(string $pouchType): ?array {
        $result = $this->db->query("SELECT * FROM pouch WHERE pouch_type = ?", [$pouchType]);
        return $result[0] ?? null;
    }

    public function create(string $pouchType): int {
        return $this->db->insert(
            "INSERT INTO pouch (pouch_type) VALUES (?)",
            [$pouchType]
        );
    }

    public function update(int $pouch_id, string $pouchType): int {
        return $this->db->execute(
            "UPDATE pouch SET pouch_type = ? WHERE pouch_id = ?",
            [$pouchType, $pouch_id]
        );
    }

    public function delete(int $pouch_id): int {
        return $this->db->execute(
            "DELETE FROM pouch WHERE pouch_id = ?",
            [$pouch_id]
        );
    }
}
