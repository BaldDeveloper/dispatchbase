<?php
require_once __DIR__ . '/Database.php';

class UserData {
    private Database $db;

    public function __construct(Database $db) {
        $this->db = $db;
    }

    // Get all users
    public function getAll(): array {
        return $this->db->query("SELECT * FROM users ORDER BY id DESC");
    }

    // Get user by ID
    public function findById(int $id): ?array {
        $result = $this->db->query("SELECT * FROM users WHERE id = ?", [$id]);
        return $result[0] ?? null;
    }

    // Create a new user
    public function create(string $name, string $email, string $role, ?string $phone = null, ?string $password = null): int {
        return $this->db->insert(
            "INSERT INTO users (name, email, role, phone, password) VALUES (?, ?, ?, ?, ?)",
            [$name, $email, $role, $phone, $password]
        );
    }

    // Update an existing user
    public function update(int $id, string $name, string $email, string $role, ?string $phone = null, ?string $password = null): int {
        return $this->db->execute(
            "UPDATE users SET name = ?, email = ?, role = ?, phone = ?, password = ? WHERE id = ?",
            [$name, $email, $role, $phone, $password, $id]
        );
    }

    // Delete a user
    public function delete(int $id): int {
        return $this->db->execute("DELETE FROM users WHERE id = ?", [$id]);
    }

    // Get all drivers (users with role = 'driver')
    public function getDrivers(): array {
        return $this->db->query("SELECT * FROM users WHERE role = 'driver' ORDER BY username ASC");
    }
}
