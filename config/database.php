<?php
class Database
{
    private $url = "localhost";
    private $dbname = "cellphones";
    private $username = "mgs_user";
    private $password = "pa55word";
    private $conn;

    public function getConnection()
    {
        if (!isset($this->conn)) {
            try {
                $this->conn = new PDO("mysql:host=" . $this->url . ";dbname=" . $this->dbname . ";charset=utf8", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Database could not be connected : " . $e->getMessage();
                exit();
            }
        }

        return $this->conn;
    }
}
