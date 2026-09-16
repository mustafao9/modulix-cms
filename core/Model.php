<?php
class Model {
    protected $db;
    public function __construct() {
        if (file_exists(__DIR__ . '/../core/installed.lock')) {
            $this->db = Database::baglan();
        }
    }
}
