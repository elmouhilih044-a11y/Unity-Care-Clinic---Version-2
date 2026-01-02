<?php

abstract class User {
    protected $id;
    protected $first_name;
    protected $last_name;
    protected $email;
    protected $phone;
    protected $role;

    public function __construct($id, $first_name, $last_name, $email, $phone, $role) {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->phone = $phone;
        $this->role = $role;
    }

    public function getId() {
        return $this->id;
    }

    public function getRole() {
        return $this->role;
    }
}
