<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include("../includes/admin_db.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Nếu admin tự xóa chính mình thì hủy session để thoát ra ngoài
    if ($id == $_SESSION['admin']) {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}

header("Location: users.php");
exit;
