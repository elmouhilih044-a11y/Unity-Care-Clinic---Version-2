<?php

class Medication {
    public $id;
    public $nom;

    public function __construct($nom) {
        $this->nom = $nom;
    }
}
