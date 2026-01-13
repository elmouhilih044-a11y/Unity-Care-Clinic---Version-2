<?php
require_once 'User.php';

class Doctor extends User {
    protected $role = 'doctor'; 
    private $specialization;
    private $departement_id;

    public function __construct($id, $first_name, $last_name, $email, $phone, $specialization, $departement_id) {
        parent::__construct($id, $first_name, $last_name, $email, $phone, 'doctor');
        $this->specialization = $specialization;
        $this->departement_id = $departement_id;
    }

    // Getters
    public function getSpecialization() {
        return $this->specialization;
    }

    public function getDepartementId() {
        return $this->departement_id;
    }

    // Setters
    public function setSpecialization($specialization) {
        $this->specialization = $specialization;
    }

    public function setDepartementId($departement_id) {
        $this->departement_id = $departement_id;
    }
}
