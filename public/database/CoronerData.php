<?php
require_once __DIR__ . '/Database.php';

class CoronerData {
    private Database $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM coroner ORDER BY coroner_number DESC");
    }

    public function findByCoronerNumber(int $coroner_number): ?array {
        $result = $this->db->query("SELECT * FROM coroner WHERE coroner_number = ?", [$coroner_number]);
        return $result[0] ?? null;
    }

    public function create(
        string $coronerName,
        ?string $phoneNumber,
        ?string $emailAddress,
        ?string $address1,
        ?string $address2,
        ?string $city,
        ?string $state,
        ?string $zip
    ): int {
        return $this->db->insert(
            "INSERT INTO coroner (coroner_name, phone_number, email_address, address_1, address_2, city, state, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$coronerName, $phoneNumber, $emailAddress, $address1, $address2, $city, $state, $zip]
        );
    }

    public function update(
        int $coronerNumber,
        string $coronerName,
        ?string $phoneNumber,
        ?string $emailAddress,
        ?string $address1,
        ?string $address2,
        ?string $city,
        ?string $state,
        ?string $zip
    ): int {
        return $this->db->execute(
            "UPDATE coroner SET coroner_name = ?, phone_number = ?, email_address = ?, address_1 = ?, address_2 = ?, city = ?, state = ?, zip = ? WHERE coroner_number = ?",
            [$coronerName, $phoneNumber, $emailAddress, $address1, $address2, $city, $state, $zip, $coronerNumber]
        );
    }

    public function delete(int $coroner_number): int {
        return $this->db->execute("DELETE FROM coroner WHERE coroner_number = ?", [$coroner_number]);
    }
}
