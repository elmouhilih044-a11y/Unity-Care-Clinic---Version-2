<?php
require_once 'config/Database.php';
require_once 'repositories/UserRepository.php';
require_once 'repositories/MedicationRepository.php';
require_once 'repositories/PrescriptionRepository.php';
require_once 'repositories/DoctorRepository.php';
require_once 'repositories/PatientRepository.php';
require_once 'repositories/AppointmentRepository.php';

echo "Testing Repositories...\n";

try {
    $db = new PDO("mysql:host=localhost;dbname=unity_care_v2;charset=utf8", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $userRepo = new UserRepository($db);
    $users = $userRepo->findAll();
    echo "Users found: " . count($users) . "\n";

    $doctorRepo = new DoctorRepository($db);
    $doctors = $doctorRepo->findAll();
    echo "Doctors found: " . count($doctors) . "\n";

    $patientRepo = new PatientRepository($db);
    $patients = $patientRepo->findAll();
    echo "Patients found: " . count($patients) . "\n";

    $medRepo = new MedicationRepository($db);
    $meds = $medRepo->findAll();
    echo "Medications found: " . count($meds) . "\n";

    $prescRepo = new PrescriptionRepository($db);
    $prescs = $prescRepo->findAll();
    echo "Prescriptions found: " . count($prescs) . "\n";

    $apptRepo = new AppointmentRepository($db);
    $appts = $apptRepo->findAll();
    echo "Appointments found: " . count($appts) . "\n";

    echo "All repositories instantiated and queried successfully.\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
