<?php

class DashboardRepository {
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getStats() {
        $stats = [];
        
        // Count Patients
        $stats['patients_count'] = $this->db->query("SELECT COUNT(*) FROM patients")->fetchColumn();
        
        // Count Doctors
        $stats['doctors_count'] = $this->db->query("SELECT COUNT(*) FROM doctors")->fetchColumn();
        
        // Count Scheduled Appointments
        $stats['appointments_count'] = $this->db->query("SELECT COUNT(*) FROM appointments WHERE status = 'scheduled'")->fetchColumn();
        
        // Count Medications in low stock (e.g., < 10)
        $stats['low_stock_medications'] = $this->db->query("SELECT COUNT(*) FROM medications WHERE stock_quantity < 10")->fetchColumn();
        
        return $stats;
    }

    public function getRecentAppointments($limit = 5) {
        $stmt = $this->db->prepare("
            SELECT a.*, 
                   u_p.first_name as patient_first_name, u_p.last_name as patient_last_name,
                   u_d.first_name as doctor_first_name, u_d.last_name as doctor_last_name
            FROM appointments a
            JOIN patients p ON a.patient_id = p.id
            JOIN users u_p ON p.id = u_p.id
            JOIN doctors d ON a.doctor_id = d.id
            JOIN users u_d ON d.id = u_d.id
            ORDER BY a.date DESC, a.time DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAppointmentsByStatus() {
        return $this->db->query("
            SELECT status, COUNT(*) as count 
            FROM appointments 
            GROUP BY status
        ")->fetchAll(PDO::FETCH_ASSOC);
    }
}
