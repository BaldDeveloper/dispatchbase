<?php
require_once __DIR__ . '/Database.php';

class CustomerData {
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

    public function create(
        string $companyName,
        string $phoneNumber,
        string $address1,
        string $address2,
        string $city,
        string $state,
        string $zip,
        string $email
    ): int {
        return $this->db->insert(
            "INSERT INTO customer (company_name, phone_number, address_1, address_2, city, state, zip, email_address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$companyName, $phoneNumber, $address1, $address2, $city, $state, $zip, $email]
        );
    }

    public function update(
        int $customerNumber,
        string $companyName,
        string $phoneNumber,
        string $address1,
        string $address2,
        string $city,
        string $state,
        string $zip,
        string $email
    ): int {
        return $this->db->execute(
            "UPDATE customer SET company_name = ?, phone_number = ?, address_1 = ?, address_2 = ?, city = ?, state = ?, zip = ?, email_address = ? WHERE customer_number = ?",
            [$companyName, $phoneNumber, $address1, $address2, $city, $state, $zip, $email, $customerNumber]
        );
    }

    public function delete(int $customer_number): int {
        return $this->db->execute("DELETE FROM customer WHERE customer_number = ?", [$customer_number]);
    }
}
