<?php
require_once 'User.php';

class Patient extends User {
    private $gender;
    private $date_of_birth;
    private $address;

    public function __construct($id, $first_name, $last_name, $email, $phone, $gender, $date_of_birth, $address) {
        parent::__construct($id, $first_name, $last_name, $email, $phone, 'patient');
        $this->gender = $gender;
        $this->date_of_birth = $date_of_birth;
        $this->address = $address;
    }
}
