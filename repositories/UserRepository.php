<?php
// Removed require_once 'BaseRepository.php'

class UserRepository {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create(array $data) {
        $stmt = $this->db->prepare("INSERT INTO users (first_name, last_name, email, password, phone, role) VALUES (:first_name, :last_name, :email, :password, :phone, :role)");
        return $stmt->execute([
            ':first_name' => $data['first_name'],
            ':last_name' => $data['last_name'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':phone' => $data['phone'],
            ':role' => $data['role']
        ]);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare(
            "SELECT * FROM users WHERE email = ?"
        );
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        return $this->db->query("SELECT * FROM users")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
