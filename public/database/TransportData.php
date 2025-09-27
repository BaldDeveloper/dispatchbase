<?php
require_once __DIR__ . '/Database.php';

class TransportData {
    private Database $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    // Insert a new record into transport
    public function create(
        int $firmId,
        string $firmDate,
        string $firmAccountType
    ): int {
        $sql = "INSERT INTO transport (
            firm_id, firm_date, firm_account_type
        ) VALUES (
            ?, ?, ?
        )";
        $params = [
            $firmId,
            $firmDate,
            $firmAccountType
        ];
        // Clear the error log before each run
        @file_put_contents(__DIR__ . '/../../database/db_error.log', '');
        error_log('SQL: ' . $sql);
        error_log('PARAM COUNT: ' . count($params));
        foreach ($params as $i => $param) {
            error_log("Param $i type: " . gettype($param) . ", value: " . var_export($param, true));
        }
        error_log('PARAMS: ' . json_encode($params));
        return $this->db->insert($sql, $params);
    }

    public function getAll(): array {
        // Join transport with decedent to get first and last name
        $sql = "SELECT t.*, d.first_name AS decedent_first_name, d.last_name AS decedent_last_name
                FROM transport t
                LEFT JOIN decedent d ON t.transport_id = d.transport_id
                ORDER BY t.transport_id DESC";
        return $this->db->query($sql);
    }

    public function findById(int $id): ?array {
        $result = $this->db->query("SELECT * FROM transport WHERE transport_id = ?", [$id]);
        return $result[0] ?? null;
    }

    public function update(
        int $transport_id,
        int $firmId,
        string $firmDate,
        string $firmAccountType
    ): int {
        $sql = "UPDATE transport SET firm_id = ?, firm_date = ?, firm_account_type = ? WHERE transport_id = ?";
        return $this->db->execute($sql, [$firmId, $firmDate, $firmAccountType, $transport_id]);
    }

    public function delete(int $transport_id): int {
        $sql = "DELETE FROM transport WHERE transport_id = ?";
        return $this->db->execute($sql, [$transport_id]);
    }
}
