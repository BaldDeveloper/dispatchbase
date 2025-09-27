<?php
require_once __DIR__ . '/Database.php';

class DecedentData {
    private Database $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM decedent ORDER BY decedent_id DESC");
    }

    public function findById(int $decedent_id): ?array {
        $result = $this->db->query("SELECT * FROM decedent WHERE decedent_id = ?", [$decedent_id]);
        return $result[0] ?? null;
    }

    public function update(
        int $decedent_id,
        string $first_name,
        string $last_name,
        string $ethnicity,
        string $gender
    ): int {
        return $this->db->execute(
            "UPDATE decedent SET first_name = ?, last_name = ?, ethnicity = ?, gender = ? WHERE decedent_id = ?",
            [$first_name, $last_name, $ethnicity, $gender, $decedent_id]
        );
    }

    public function updateByTransportId(
        int $transport_id,
        string $first_name,
        string $last_name,
        string $ethnicity,
        string $gender
    ): int {
        return $this->db->execute(
            "UPDATE decedent SET first_name = ?, last_name = ?, ethnicity = ?, gender = ? WHERE transport_id = ?",
            [$first_name, $last_name, $ethnicity, $gender, $transport_id]
        );
    }

    public function delete(int $decedent_id): int {
        return $this->db->execute(
            "DELETE FROM decedent WHERE decedent_id = ?",
            [$decedent_id]
        );
    }

    public function deleteByTransportId(int $transport_id): int {
        return $this->db->execute(
            "DELETE FROM decedent WHERE transport_id = ?",
            [$transport_id]
        );
    }

    public function insertByTransportId(
        int $transport_id,
        string $first_name,
        string $last_name,
        string $ethnicity,
        string $gender
    ): int {
        return $this->db->insert(
            "INSERT INTO decedent (transport_id, first_name, last_name, ethnicity, gender) VALUES (?, ?, ?, ?, ?)",
            [$transport_id, $first_name, $last_name, $ethnicity, $gender]
        );
    }
}
