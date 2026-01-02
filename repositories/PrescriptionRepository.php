<?php
require_once 'BaseRepository.php';

class PrescriptionRepository extends BaseRepository {

    public function create($date, $doctor_id, $patient_id, $medication_id, $instructions) {
        $stmt = $this->db->prepare(
            "INSERT INTO prescriptions 
            (date, doctor_id, patient_id, medication_id, dosage_instructions)
            VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([
            $date,
            $doctor_id,
            $patient_id,
            $medication_id,
            $instructions
        ]);
    }

    public function findByPatient($patientId) {
        $stmt = $this->db->prepare(
            "SELECT prescriptions.*, medications.nom 
             FROM prescriptions
             JOIN medications ON prescriptions.medication_id = medications.id
             WHERE patient_id = ?"
        );
        $stmt->execute([$patientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByDoctor($doctorId) {
        $stmt = $this->db->prepare(
            "SELECT * FROM prescriptions WHERE doctor_id = ?"
        );
        $stmt->execute([$doctorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
