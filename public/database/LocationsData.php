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

    // Update an existing location
    public function updateLocation($id, $name, $address = null) {
        return $this->db->execute('UPDATE location SET name = ?, address = ? WHERE id = ?', [$name, $address, $id]);
    }

    // Delete a location
    public function deleteLocation($id) {
        return $this->db->execute('DELETE FROM location WHERE id = ?', [$id]);
    }
}
