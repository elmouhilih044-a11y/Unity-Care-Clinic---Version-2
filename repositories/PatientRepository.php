<?php
// Removed require_once 'BaseRepository.php'

class PatientRepository {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function findAll() {
        $stmt = $this->db->query(
            "SELECT users.*, patients.gender, patients.date_of_birth, patients.address
             FROM users
             JOIN patients ON users.id = patients.id"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        try {
            $this->db->beginTransaction();

            // Insert into users
            $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES (?, ?, ?, ?, ?, 'patient')");
            $stmt->execute([
                $data['first_name'],
                $data['last_name'],
                $data['email'],
                $data['password'],
                $data['phone']
            ]);
            $userId = $this->db->lastInsertId();

            // Insert into patients
            $stmt = $this->db->prepare("INSERT INTO patients (id, gender, date_of_birth, address) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $userId,
                $data['gender'],
                $data['date_of_birth'],
                $data['address']
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function delete($id) {
         $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
         return $stmt->execute([$id]);
    }

    public function findById($id) {
        $stmt = $this->db->prepare(
            "SELECT users.*, patients.gender, patients.date_of_birth, patients.address
             FROM users 
             JOIN patients ON users.id = patients.id 
             WHERE patients.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
