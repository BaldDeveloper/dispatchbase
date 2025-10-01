// ...existing code...
    /**
     * Get total count of pouch records
     */
    public function getCount() {
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM pouches');
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Get paginated pouch records
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getPaginated($limit, $offset) {
        $stmt = $this->db->prepare('SELECT * FROM pouches ORDER BY pouch_id ASC LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
// ...existing code...
