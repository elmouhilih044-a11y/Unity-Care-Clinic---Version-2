<?php
// Removed require_once 'BaseRepository.php'

class MedicationRepository {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($name, $description, $stock) {
        $stmt = $this->db->prepare(
            "INSERT INTO medications (name, description, stock_quantity) VALUES (?, ?, ?)"
        );
        return $stmt->execute([$name, $description, $stock]);
    }

    public function update($id, $name, $description, $stock) {
        $stmt = $this->db->prepare(
            "UPDATE medications SET name = ?, description = ?, stock_quantity = ? WHERE id = ?"
        );
        return $stmt->execute([$name, $description, $stock, $id]);
    }

    public function findAll() {
        $stmt = $this->db->query(
            "SELECT * FROM medications"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM medications WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->db->prepare(
            "DELETE FROM medications WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}
