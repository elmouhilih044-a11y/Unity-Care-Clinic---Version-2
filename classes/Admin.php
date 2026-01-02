<?php
require_once 'User.php';

class Admin extends User {
    public function __construct($id, $first_name, $last_name, $email, $phone) {
        parent::__construct($id, $first_name, $last_name, $email, $phone, 'admin');
    }
}
