<?php

class Medication {
    public $id;
    public $name;
    public $description;
    public $stock_quantity;

    public function __construct($name, $description, $stock_quantity) {
        $this->name = $name;
        $this->description = $description;
        $this->stock_quantity = $stock_quantity;
    }
}
