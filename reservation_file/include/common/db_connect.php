<?php
class Database {
    private $conn;

    public function __construct() {
        try {
            $host = '175.196.104.244';
            $dbname = 'reservation_system';
            $username = 'root';
            $password = 'root';

            $this->conn = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            throw new Exception("데이터베이스 연결에 실패했습니다.");
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>