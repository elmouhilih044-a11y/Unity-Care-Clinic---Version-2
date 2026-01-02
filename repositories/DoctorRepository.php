<?php
require_once 'BaseRepository.php';

class DoctorRepository extends BaseRepository {

    public function findAll() {
        $stmt = $this->db->query(
            "SELECT users.*, doctors.specialization, doctors.department_id
             FROM users
             JOIN doctors ON users.id = doctors.id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare(
            "SELECT * FROM doctors WHERE id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
