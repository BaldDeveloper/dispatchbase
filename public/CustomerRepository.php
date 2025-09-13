<?php
require_once 'Database.php';

class CustomerRepository {
    private Database $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM customer ORDER BY customer_number DESC");
    }

    public function findByCustomerNumber(int $customer_number): ?array {
        $result = $this->db->query("SELECT * FROM customer WHERE customer_number = ?", [$customer_number]);
        return $result[0] ?? null;
    }

    public function create(string $name, string $email): int {
        return $this->db->insert(
            "INSERT INTO customer (name, email) VALUES (?, ?)",
            [$name, $email]
        );
    }

    public function delete(int $customer_number): int {
        return $this->db->execute("DELETE FROM customer WHERE customer_number = ?", [$customer_number]);
    }
}