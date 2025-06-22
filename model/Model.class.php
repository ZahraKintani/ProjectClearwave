<?php

class Model {
    protected $db;
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'clearwave';

    public function __construct() {
        $this->db = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);

        if ($this->db->connect_error) {
            error_log("Koneksi database gagal: " . $this->db->connect_error);
            die('Terjadi masalah dengan koneksi database. Silakan coba lagi nanti.');
        }
        $this->db->set_charset("utf8mb4");
    }

    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}

?>
