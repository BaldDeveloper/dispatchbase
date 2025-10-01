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
        ?string $zip,
        string $county
    ): int {
        return $this->db->insert(
            "INSERT INTO coroner (coroner_name, phone_number, email_address, address_1, address_2, city, state, zip, county) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [$coronerName, $phoneNumber, $emailAddress, $address1, $address2, $city, $state, $zip, $county]
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
        ?string $zip,
        string $county
    ): int {
        return $this->db->execute(
            "UPDATE coroner SET coroner_name = ?, phone_number = ?, email_address = ?, address_1 = ?, address_2 = ?, city = ?, state = ?, zip = ?, county = ? WHERE coroner_number = ?",
            [$coronerName, $phoneNumber, $emailAddress, $address1, $address2, $city, $state, $zip, $county, $coronerNumber]
        );
    }

    public function delete(int $coroner_number): int {
        return $this->db->execute("DELETE FROM coroner WHERE coroner_number = ?", [$coroner_number]);
    }

    public function getPaginated(int $limit, int $offset): array {
        $limit = max(1, (int)$limit);
        $offset = max(0, (int)$offset);
        $sql = "SELECT * FROM coroner ORDER BY coroner_number DESC LIMIT $limit OFFSET $offset";
        return $this->db->query($sql);
    }

    public function getCount(): int {
        $result = $this->db->query("SELECT COUNT(*) as cnt FROM coroner");
        return isset($result[0]['cnt']) ? (int)$result[0]['cnt'] : 0;
    }

    public function existsByName(string $coronerName): bool {
        $result = $this->db->query("SELECT coroner_number FROM coroner WHERE coroner_name = ? LIMIT 1", [$coronerName]);
        return !empty($result);
    }
}
