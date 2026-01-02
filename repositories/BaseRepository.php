<?php
require_once __DIR__ . '/../config/Database.php';

abstract class BaseRepository {
    protected $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }
}
