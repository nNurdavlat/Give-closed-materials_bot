<?php

class DB
{
    public $host;
    public $user;
    public $pass;
    public $db_name;
    public $conn;

    public function __construct()
    {
            $this->db_name = $_ENV['DB_DATABASE'];
            $this->host = $_ENV['DB_HOST'];
            $this->user = $_ENV['DB_USERNAME'];
            $this->pass = $_ENV['DB_PASSWORD'];

            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->user, $this->pass );
    }
}