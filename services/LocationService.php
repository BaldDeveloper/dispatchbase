<?php
require_once __DIR__ . '/../database/LocationsData.php';
require_once __DIR__ . '/../database/Database.php';
/**
 * LocationService
 *
 * Service class for location-related business logic.
 *
 * Methods in this class handle business rules, formatting, and validation
 * that are not strictly data access (which remains in LocationsData).
 *
 * Last reviewed: 2025-10-09
 */
class LocationService {
    private $repo;

    /**
     * LocationService constructor.
     * @param Database|null $db
     */
    public function __construct($db = null) {
        if ($db === null) {
            $db = new Database();
        }
        $this->repo = new LocationsData($db);
    }

    /**
     * Find a location by ID.
     * @param int|string $id
     * @return array|null
     */
    public function findById($id) {
        return $this->repo->findById($id);
    }

    /**
     * Delete a location by ID.
     * @param int|string $id
     * @return bool|int
     */
    public function delete($id) {
        return $this->repo->delete($id);
    }

    /**
     * Check if a location exists by name.
     * @param string $name
     * @return bool
     */
    public function existsByName($name) {
        return $this->repo->existsByName($name);
    }

    /**
     * Create a new location record.
     * @param string $name
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string $zip_code
     * @param string $phone_number
     * @param string $location_type
     * @return int|false New location ID or false on failure
     */
    public function create($name, $address, $city, $state, $zip_code, $phone_number, $location_type) {
        return $this->repo->create($name, $address, $city, $state, $zip_code, $phone_number, $location_type);
    }

    /**
     * Update an existing location record.
     * @param int|string $id
     * @param string $name
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string $zip_code
     * @param string $phone_number
     * @param string $location_type
     * @return bool|int
     */
    public function update($id, $name, $address, $city, $state, $zip_code, $phone_number, $location_type) {
        return $this->repo->update($id, $name, $address, $city, $state, $zip_code, $phone_number, $location_type);
    }

    /**
     * Get the total number of locations.
     * @return int
     */
    public function getCount() {
        return $this->repo->getCount();
    }

    /**
     * Get paginated list of locations.
     * @param int $pageSize
     * @param int $offset
     * @return array
     */
    public function getPaginated($pageSize, $offset) {
        return $this->repo->getPaginated($pageSize, $offset);
    }

    /**
     * Get all locations.
     * @return array
     */
    public function getAll() {
        return $this->repo->getAllLocations();
    }

    /**
     * Format a display name for a location (e.g., Name (City, State)).
     * @param array $location
     * @return string
     */
    public function formatDisplayName($location) {
        $name = isset($location['name']) ? $location['name'] : '';
        $city = isset($location['city']) ? $location['city'] : '';
        $state = isset($location['state']) ? $location['state'] : '';
        $parts = [];
        if ($city) $parts[] = $city;
        if ($state) $parts[] = $state;
        $suffix = $parts ? (' (' . implode(', ', $parts) . ')') : '';
        return trim($name . $suffix);
    }
}
