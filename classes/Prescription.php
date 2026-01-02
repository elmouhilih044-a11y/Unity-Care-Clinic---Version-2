<?php

class Prescription {
    public $id;
    public $date;
    public $doctor_id;
    public $patient_id;
    public $medication_id;
    public $dosage_instructions;

    public function __construct($date, $doctor_id, $patient_id, $medication_id, $dosage_instructions) {
        $this->date = $date;
        $this->doctor_id = $doctor_id;
        $this->patient_id = $patient_id;
        $this->medication_id = $medication_id;
        $this->dosage_instructions = $dosage_instructions;
    }
}
