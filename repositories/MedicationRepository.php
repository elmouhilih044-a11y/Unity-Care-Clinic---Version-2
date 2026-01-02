<?php
require_once 'BaseRepository.php';

class MedicationRepository extends BaseRepository {

    public function create($name) {
        $stmt = $this->db->prepare(
            "INSERT INTO medications (nom) VALUES (?)"
        );
        return $stmt->execute([$name]);
    }

    public function findAll() {
        $stmt = $this->db->query(
            "SELECT * FROM medications"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->db->prepare(
            "DELETE FROM medications WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}
