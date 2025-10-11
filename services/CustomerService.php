<?php
require_once __DIR__ . '/../database/CustomerData.php';
require_once __DIR__ . '/../database/Database.php';
/**
 * CustomerService
 *
 * Service class for customer-related business logic.
 *
 * Methods in this class handle business rules, formatting, and validation
 * that are not strictly data access (which remains in CustomerData).
 *
 * Last reviewed: 2025-10-09
 */
class CustomerService {
    private $repo;

    /**
     * CustomerService constructor.
     * @param Database|null $db
     */
    public function __construct($db = null) {
        if ($db === null) {
            $db = new Database();
        }
        $this->repo = new CustomerData($db);
    }

    /**
     * Format a display name for a customer.
     * @param array $customer Customer data array
     * @return string
     */
    public function formatDisplayName($customer) {
        $company = $customer['company_name'] ?? '';
        return $company;
    }

    /**
     * Find a customer by customer_number (ID).
     * @param int|string $id
     * @return array|null
     */
    public function findByCustomerNumber($id) {
        return $this->repo->findByCustomerNumber($id);
    }

    /**
     * Delete a customer by customer_number (ID).
     * @param int|string $id
     * @return bool|int
     */
    public function delete($id) {
        return $this->repo->delete($id);
    }

    /**
     * Check if a customer exists by company name.
     * @param string $companyName
     * @return bool
     */
    public function existsByName($companyName) {
        return $this->repo->existsByName($companyName);
    }

    /**
     * Create a new customer record.
     * @param string $companyName
     * @param string $phoneNumber
     * @param string $address1
     * @param string $address2
     * @param string $city
     * @param string $state
     * @param string $zip
     * @param string $emailAddress
     * @return int|false New customer ID or false on failure
     */
    public function create($companyName, $phoneNumber, $address1, $address2, $city, $state, $zip, $emailAddress) {
        return $this->repo->create($companyName, $phoneNumber, $address1, $address2, $city, $state, $zip, $emailAddress);
    }

    /**
     * Update an existing customer record.
     * @param int|string $id
     * @param string $companyName
     * @param string $phoneNumber
     * @param string $address1
     * @param string $address2
     * @param string $city
     * @param string $state
     * @param string $zip
     * @param string $emailAddress
     * @return bool|int
     */
    public function update($id, $companyName, $phoneNumber, $address1, $address2, $city, $state, $zip, $emailAddress) {
        return $this->repo->update($id, $companyName, $phoneNumber, $address1, $address2, $city, $state, $zip, $emailAddress);
    }

    /**
     * Get the total number of customers.
     * @return int
     */
    public function getCount() {
        return $this->repo->getCount();
    }

    /**
     * Get paginated list of customers.
     * @param int $pageSize
     * @param int $offset
     * @return array
     */
    public function getPaginated($pageSize, $offset) {
        return $this->repo->getPaginated($pageSize, $offset);
    }

    /**
     * Get the total number of customers matching a search term.
     * @param string $search
     * @return int
     */
    public function getCountBySearch($search) {
        return $this->repo->getCountBySearch($search);
    }

    /**
     * Get paginated list of customers matching a search term.
     * @param string $search
     * @param int $pageSize
     * @param int $offset
     * @return array
     */
    public function searchPaginated($search, $pageSize, $offset) {
        return $this->repo->searchPaginated($search, $pageSize, $offset);
    }
}
