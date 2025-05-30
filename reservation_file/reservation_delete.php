<?php
require_once 'include/common/Database.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: reservation_list.php');
    exit;
}

$id = $_GET['id'];

// 데이터베이스 연결
$database = new Database();
$db = $database->getConnection();

try {
    // 예약 삭제
    $query = "DELETE FROM reservations WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    header('Location: reservation_list.php?message=deleted');
} catch(PDOException $e) {
    header('Location: reservation_list.php?error=delete_failed');
}
exit; 