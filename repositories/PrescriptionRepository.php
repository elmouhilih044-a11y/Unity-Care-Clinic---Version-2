<?php
// Removed require_once 'BaseRepository.php'

class PrescriptionRepository {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($patient_id, $doctor_id, $medication_id, $dosage, $instructions) {
        $stmt = $this->db->prepare(
            "INSERT INTO prescriptions 
            (patient_id, doctor_id, medication_id, dosage, instructions)
            VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $patient_id,
            $doctor_id,
            $medication_id,
            $dosage,
            $instructions
        ]);
    }

    public function findByPatient($patientId) {
        $stmt = $this->db->prepare(
            "SELECT p.*, m.name as medication_name, m.description,
                    doc.first_name as doctor_first_name, doc.last_name as doctor_last_name
             FROM prescriptions p
             JOIN medications m ON p.medication_id = m.id
             JOIN users doc ON p.doctor_id = doc.id
             WHERE p.patient_id = ?"
        );
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByDoctor($doctorId) {
        $stmt = $this->db->prepare(
            "SELECT p.*, m.name as medication_name,
                    pat.first_name as patient_first_name, pat.last_name as patient_last_name
             FROM prescriptions p
             JOIN medications m ON p.medication_id = m.id
             JOIN users pat ON p.patient_id = pat.id
             WHERE p.doctor_id = ?"
        );
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll() {
        $stmt = $this->db->query("SELECT * FROM prescriptions");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM prescriptions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM prescriptions WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
