<?php
class Database
{
    private static $instace = null;
    private $conn;
    private $host = 'localhost';
    private $db = 'contatos';
    private $user = 'root';
    private $pass = '';

    private function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db", $this->user, $this->pass);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public static function getInstance()
    {
        if(!self::$instace)
        {
            self::$instace = new Database();
        }

        return self::$instace;
    }

    public function getConnection() 
    {
        return $this->conn;    
    }
}
?>