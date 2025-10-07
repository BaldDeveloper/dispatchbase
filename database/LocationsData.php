<?php
// database/LocationsData.php
// Handles CRUD operations for the location table
require_once __DIR__ . '/Database.php';

class LocationsData {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all locations
    public function getAllLocations() {
        return $this->db->query('SELECT * FROM location ORDER BY name ASC');
    }

    // Get a single location by ID
    public function getLocationById($id) {
        $results = $this->db->query('SELECT * FROM location WHERE id = ?', [$id]);
        return $results[0] ?? null;
    }

    // Add a new location
    public function addLocation($name, $address = null) {
        return $this->db->insert('INSERT INTO location (name, address) VALUES (?, ?)', [$name, $address]);
    }

    // Create a new location with all fields
    public function create($name, $address, $city, $state, $zip_code, $phone_number, $location_type) {
        return $this->db->insert(
            'INSERT INTO location (name, address, city, state, zip_code, phone_number, location_type) VALUES (?, ?, ?, ?, ?, ?, ?)',
            [$name, $address, $city, $state, $zip_code, $phone_number, $location_type]
        );
    }

    // Update an existing location
    public function updateLocation($id, $name, $address, $city, $state, $zip_code, $phone_number, $location_type) {
        return $this->db->execute(
            'UPDATE location SET name = ?, address = ?, city = ?, state = ?, zip_code = ?, phone_number = ?, location_type = ? WHERE id = ?',
            [$name, $address, $city, $state, $zip_code, $phone_number, $location_type, $id]
        );
    }

    // Delete a location
    public function deleteLocation($id) {
        return $this->db->execute('DELETE FROM location WHERE id = ?', [$id]);
    }

    public function getCount() {
        $result = $this->db->query('SELECT COUNT(*) AS cnt FROM location');
        return isset($result[0]['cnt']) ? (int)$result[0]['cnt'] : 0;
    }

    public function getPaginated($limit, $offset) {
        $limit = max(1, (int)$limit);
        $offset = max(0, (int)$offset);
        return $this->db->query("SELECT * FROM location ORDER BY id DESC LIMIT $limit OFFSET $offset");
    }

    public function findById($id) {
        return $this->getLocationById($id);
    }

    public function update($id, $name, $address, $city, $state, $zip_code, $phone_number, $location_type) {
        return $this->updateLocation($id, $name, $address, $city, $state, $zip_code, $phone_number, $location_type);
    }

    public function delete($id) {
        return $this->deleteLocation($id);
    }

    // Check if a location with the given name exists
    public function existsByName($name) {
        $result = $this->db->query('SELECT id FROM location WHERE name = ?', [$name]);
        return !empty($result);
    }
}
