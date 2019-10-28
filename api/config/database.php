<?php
define( 'MYSQL_HOST', 'localhost' );
define( 'MYSQL_USER', 'root' );
define( 'MYSQL_PASSWORD', 'debian' );
define( 'MYSQL_DB_NAME', 'batalha' );

class Database{
 
    // specify your own database credentials
    private $host = MYSQL_HOST;
    private $db_name = MYSQL_DB_NAME;
    private $username = MYSQL_USER;
    private $password = MYSQL_PASSWORD;
    public $conn;
 
    // get the database connection
    public function getConnection(){
 
        $this->conn = null;
 
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
 
        return $this->conn;
    }
}
?>