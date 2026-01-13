<?php
// Removed require_once 'BaseRepository.php'

class AppointmentRepository {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function create($date, $time, $doctor_id, $patient_id, $reason) {
        $stmt = $this->db->prepare(
            "INSERT INTO appointments (date, time, doctor_id, patient_id, reason, status)
             VALUES (?, ?, ?, ?, ?, 'scheduled')"
        );
        return $stmt->execute([$date, $time, $doctor_id, $patient_id, $reason]);
    }

    public function findAll() {
        // Admin view: joins for names
        $sql = "SELECT a.*, 
                       pat.first_name as patient_first_name, pat.last_name as patient_last_name,
                       doc.first_name as doctor_first_name, doc.last_name as doctor_last_name
                FROM appointments a
                JOIN users pat ON a.patient_id = pat.id
                JOIN users doc ON a.doctor_id = doc.id
                ORDER BY a.date DESC, a.time ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByDoctor($doctorId) {
        $sql = "SELECT a.*, 
                       pat.first_name as patient_first_name, pat.last_name as patient_last_name
                FROM appointments a
                JOIN users pat ON a.patient_id = pat.id
                WHERE a.doctor_id = ?
                ORDER BY a.date DESC, a.time ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByPatient($patientId) {
        $sql = "SELECT a.*, 
                       doc.first_name as doctor_first_name, doc.last_name as doctor_last_name,
                       d_details.specialization
                FROM appointments a
                JOIN users doc ON a.doctor_id = doc.id
                JOIN doctors d_details ON doc.id = d_details.id
                WHERE a.patient_id = ?
                ORDER BY a.date DESC, a.time ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM appointments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE appointments SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
