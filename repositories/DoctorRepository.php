<?php
// Removed require_once 'BaseRepository.php'

class DoctorRepository {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $stmt = $this->db->query(
            "SELECT users.*, doctors.specialization, doctors.department_id
             FROM users
             JOIN doctors ON users.id = doctors.id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            $this->db->beginTransaction();

            // Insert into users
            $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, 'doctor')");
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['password'], // derived elsewhere or here
                $data['phone']
            ]);
            $userId = $this->db->lastInsertId();

            // Insert into doctors
            $stmt = $this->db->prepare("INSERT INTO doctors (id, specialization, department_id) VALUES (?, ?, ?)");
            $stmt->execute([
                $userId,
                $data['specialization'],
                $data['department_id']
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function delete($id) {
        // Delete from users (cascades to doctors)
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findById($id) {
        $stmt = $this->db->prepare(
            "SELECT users.*, doctors.specialization, doctors.department_id 
             FROM users 
             JOIN doctors ON users.id = doctors.id 
             WHERE doctors.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
