<?php
require_once __DIR__ . '/../database/PouchData.php';
require_once __DIR__ . '/../database/Database.php';
/**
 * PouchService
 *
 * Service class for pouch-related business logic.
 *
 * Methods in this class handle business rules, formatting, and validation
 * that are not strictly data access (which remains in PouchData).
 *
 * Last reviewed: 2025-10-09
 */
class PouchService {
    private $repo;

    /**
     * PouchService constructor.
     * @param Database|null $db
     */
    public function __construct($db = null) {
        if ($db === null) {
            $db = new Database();
        }
        $this->repo = new PouchData($db);
    }

    /**
     * Find a pouch by ID.
     * @param int|string $id
     * @return array|null
     */
    public function findById($id) {
        return $this->repo->findById($id);
    }

    /**
     * Find a pouch by type.
     * @param string $pouchType
     * @return array|null
     */
    public function findByType($pouchType) {
        return $this->repo->findByType($pouchType);
    }

    /**
     * Delete a pouch by ID.
     * @param int|string $id
     * @return bool|int
     */
    public function delete($id) {
        return $this->repo->delete($id);
    }

    /**
     * Create a new pouch record.
     * @param string $pouchType
     * @return int|false New pouch ID or false on failure
     */
    public function create($pouchType) {
        return $this->repo->create($pouchType);
    }

    /**
     * Update an existing pouch record.
     * @param int|string $id
     * @param string $pouchType
     * @return bool|int
     */
    public function update($id, $pouchType) {
        return $this->repo->update($id, $pouchType);
    }

    /**
     * Get the total number of pouches.
     * @return int
     */
    public function getCount() {
        return $this->repo->getCount();
    }

    /**
     * Get paginated list of pouches.
     * @param int $pageSize
     * @param int $offset
     * @return array
     */
    public function getPaginated($pageSize, $offset) {
        return $this->repo->getPaginated($pageSize, $offset);
    }

    /**
     * Get all pouches.
     * @return array
     */
    public function getAll() {
        return $this->repo->getAll();
    }

    /**
     * Get the total number of pouches matching a search term.
     * @param string $search
     * @return int
     */
    public function getCountBySearch($search) {
        return $this->repo->getCountBySearch($search);
    }

    /**
     * Get paginated list of pouches matching a search term.
     * @param string $search
     * @param int $pageSize
     * @param int $offset
     * @return array
     */
    public function searchPaginated($search, $pageSize, $offset) {
        return $this->repo->searchPaginated($search, $pageSize, $offset);
    }

    /**
     * Return a summary string for a pouch record.
     * @param array $pouch
     * @return string
     */
    public function formatSummary($pouch) {
        // Prefer pouch_type, fallback to name, else use pouch_number
        if (!empty($pouch['pouch_type'])) {
            return $pouch['pouch_type'];
        } elseif (!empty($pouch['name'])) {
            return $pouch['name'];
        } elseif (!empty($pouch['pouch_number'])) {
            return 'Pouch #' . $pouch['pouch_number'];
        }
        return 'Unknown Pouch';
    }
}
