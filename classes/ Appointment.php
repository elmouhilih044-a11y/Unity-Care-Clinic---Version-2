<?php

class Appointment {
    public $id;
    public $date;
    public $time;
    public $doctor_id;
    public $patient_id;
    public $reason;

    public function __construct($date, $time, $doctor_id, $patient_id, $reason) {
        $this->date = $date;
        $this->time = $time;
        $this->doctor_id = $doctor_id;
        $this->patient_id = $patient_id;
        $this->reason = $reason;
    }
}
