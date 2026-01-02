<?php
require_once 'BaseRepository.php';

class PatientRepository extends BaseRepository {

    public function findAll() {
        $stmt = $this->db->query(
            "SELECT users.*, patients.gender, patients.date_of_birth, patients.address
             FROM users
             JOIN patients ON users.id = patients.id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare(
            "SELECT * FROM patients WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
