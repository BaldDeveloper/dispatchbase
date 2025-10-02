<?php
require_once __DIR__ . '/Database.php';

class TransportChargesData {
    private Database $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM transport_charges ORDER BY id DESC");
    }

    public function findById(int $id): ?array {
        $result = $this->db->query("SELECT * FROM transport_charges WHERE id = ?", [$id]);
        return $result[0] ?? null;
    }

    public function findByTransportId(int $transport_id): ?array {
        $result = $this->db->query("SELECT * FROM transport_charges WHERE transport_id = ?", [$transport_id]);
        return $result[0] ?? null;
    }

    public function create(
        int $transport_id,
        float $removal_charge = 0.00,
        float $pouch_charge = 0.00,
        float $embalming_charge = 0.00,
        float $transport_fees = 0.00,
        float $cremation_charge = 0.00,
        float $wait_charge = 0.00,
        float $mileage_fees = 0.00,
        float $other_charge_1 = 0.00,
        ?string $other_charge_1_description = null,
        float $other_charge_2 = 0.00,
        ?string $other_charge_2_description = null,
        float $other_charge_3 = 0.00,
        ?string $other_charge_3_description = null,
        float $other_charge_4 = 0.00,
        ?string $other_charge_4_description = null,
        float $total_charge = 0.00
    ): int {
        return $this->db->insert(
            "INSERT INTO transport_charges (
                transport_id, removal_charge, pouch_charge, embalming_charge, transport_fees, cremation_charge, wait_charge, mileage_fees,
                other_charge_1, other_charge_1_description, other_charge_2, other_charge_2_description, other_charge_3, other_charge_3_description,
                other_charge_4, other_charge_4_description, total_charge
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $transport_id,
                $removal_charge,
                $pouch_charge,
                $embalming_charge,
                $transport_fees,
                $cremation_charge,
                $wait_charge,
                $mileage_fees,
                $other_charge_1,
                $other_charge_1_description,
                $other_charge_2,
                $other_charge_2_description,
                $other_charge_3,
                $other_charge_3_description,
                $other_charge_4,
                $other_charge_4_description,
                $total_charge
            ]
        );
    }

    public function update(
        int $id,
        int $transport_id,
        float $removal_charge = 0.00,
        float $pouch_charge = 0.00,
        float $embalming_charge = 0.00,
        float $transport_fees = 0.00,
        float $cremation_charge = 0.00,
        float $wait_charge = 0.00,
        float $mileage_fees = 0.00,
        float $other_charge_1 = 0.00,
        ?string $other_charge_1_description = null,
        float $other_charge_2 = 0.00,
        ?string $other_charge_2_description = null,
        float $other_charge_3 = 0.00,
        ?string $other_charge_3_description = null,
        float $other_charge_4 = 0.00,
        ?string $other_charge_4_description = null,
        float $total_charge = 0.00
    ): int {
        return $this->db->execute(
            "UPDATE transport_charges SET
                transport_id = ?,
                removal_charge = ?,
                pouch_charge = ?,
                embalming_charge = ?,
                transport_fees = ?,
                cremation_charge = ?,
                wait_charge = ?,
                mileage_fees = ?,
                other_charge_1 = ?,
                other_charge_1_description = ?,
                other_charge_2 = ?,
                other_charge_2_description = ?,
                other_charge_3 = ?,
                other_charge_3_description = ?,
                other_charge_4 = ?,
                other_charge_4_description = ?,
                total_charge = ?
            WHERE id = ?",
            [
                $transport_id,
                $removal_charge,
                $pouch_charge,
                $embalming_charge,
                $transport_fees,
                $cremation_charge,
                $wait_charge,
                $mileage_fees,
                $other_charge_1,
                $other_charge_1_description,
                $other_charge_2,
                $other_charge_2_description,
                $other_charge_3,
                $other_charge_3_description,
                $other_charge_4,
                $other_charge_4_description,
                $total_charge,
                $id
            ]
        );
    }

    public function delete(int $id): int {
        return $this->db->execute("DELETE FROM transport_charges WHERE id = ?", [$id]);
    }

    public function getCount(): int {
        $result = $this->db->query("SELECT COUNT(*) AS cnt FROM transport_charges");
        return isset($result[0]['cnt']) ? (int)$result[0]['cnt'] : 0;
    }

    public function getPaginated(int $limit, int $offset): array {
        $limit = max(1, (int)$limit);
        $offset = max(0, (int)$offset);
        return $this->db->query(
            "SELECT * FROM transport_charges ORDER BY id DESC LIMIT $limit OFFSET $offset"
        );
    }
}

