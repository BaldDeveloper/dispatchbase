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
        return $this->db->query("SELECT * FROM transport ORDER BY transport_id DESC");
    }

    public function findById(int $id): ?array {
        $result = $this->db->query("SELECT * FROM transport WHERE transport_id = ?", [$id]);
        return $result[0] ?? null;
    }
}
