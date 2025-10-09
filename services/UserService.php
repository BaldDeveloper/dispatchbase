<?php
require_once __DIR__ . '/../database/UserData.php';
require_once __DIR__ . '/../database/Database.php';
/**
 * UserService
 *
 * Service class for user-related business logic.
 *
 * Methods in this class handle business rules, formatting, and validation
 * that are not strictly data access (which remains in UserData).
 *
 * Last reviewed: 2025-10-09
 */
class UserService {
    private $repo;

    /**
     * UserService constructor.
     * @param Database|null $db
     */
    public function __construct($db = null) {
        if ($db === null) {
            $db = new Database();
        }
        $this->repo = new UserData($db);
    }

    /**
     * Find a user by ID.
     * @param int|string $id
     * @return array|null
     */
    public function findById($id) {
        return $this->repo->findById($id);
    }

    /**
     * Delete a user by ID.
     * @param int|string $id
     * @return bool|int
     */
    public function delete($id) {
        return $this->repo->delete($id);
    }

    /**
     * Check if a user exists by username.
     * @param string $username
     * @return bool
     */
    public function existsByName($username) {
        return $this->repo->existsByName($username);
    }

    /**
     * Create a new user record.
     * @param string $username
     * @param string $password_hash
     * @param string $full_name
     * @param string|null $address
     * @param string|null $city
     * @param string|null $state
     * @param string|null $zip_code
     * @param string|null $phone_number
     * @param string $role
     * @param int $is_active
     * @return int|false New user ID or false on failure
     */
    public function create($username, $password_hash, $full_name, $address = null, $city = null, $state = null, $zip_code = null, $phone_number = null, $role = 'other', $is_active = 1) {
        return $this->repo->create($username, $password_hash, $full_name, $address, $city, $state, $zip_code, $phone_number, $role, $is_active);
    }

    /**
     * Update an existing user record.
     * @param int|string $id
     * @param string $username
     * @param string|null $password_hash
     * @param string $full_name
     * @param string|null $address
     * @param string|null $city
     * @param string|null $state
     * @param string|null $zip_code
     * @param string|null $phone_number
     * @param string $role
     * @param int $is_active
     * @return bool|int
     */
    public function update($id, $username, $password_hash, $full_name, $address = null, $city = null, $state = null, $zip_code = null, $phone_number = null, $role = 'other', $is_active = 1) {
        return $this->repo->update($id, $username, $password_hash, $full_name, $address, $city, $state, $zip_code, $phone_number, $role, $is_active);
    }

    /**
     * Get the total number of users.
     * @return int
     */
    public function getCount() {
        return $this->repo->getCount();
    }

    /**
     * Get paginated list of users.
     * @param int $pageSize
     * @param int $offset
     * @return array
     */
    public function getPaginated($pageSize, $offset) {
        return $this->repo->getPaginated($pageSize, $offset);
    }

    /**
     * Get all drivers (users with role = 'driver').
     * @return array
     */
    public function getDrivers() {
        return $this->repo->getDrivers();
    }

    /**
     * Format a user's display name for UI output.
     * Uses full_name if available, otherwise falls back to username.
     *
     * @param array $user User data array
     * @return string Display name
     */
    public function formatDisplayName($user) {
        $fullName = isset($user['full_name']) ? trim($user['full_name']) : '';
        if ($fullName !== '') {
            return $fullName;
        }
        // Fallback to username if full_name is missing/empty
        return isset($user['username']) ? trim($user['username']) : '';
    }
} // end of class
