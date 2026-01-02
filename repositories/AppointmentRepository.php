<?php
require_once 'BaseRepository.php';

class AppointmentRepository extends BaseRepository {

    public function create($date, $time, $doctor_id, $patient_id, $reason) {
        $stmt = $this->db->prepare(
            "INSERT INTO appointments (date, time, doctor_id, patient_id, reason)
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$date, $time, $doctor_id, $patient_id, $reason]);
    }

    public function findByDoctor($doctorId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM appointments WHERE doctor_id = ?"
        );
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByPatient($patientId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM appointments WHERE patient_id = ?"
        );
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
