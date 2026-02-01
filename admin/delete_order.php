<?php
session_start();
include("../includes/admin_db.php");

if (isset($_GET['id'])) {
    $order_id = (int)$_GET['id'];

    // 🔁 Xóa chi tiết đơn hàng trước
    $stmt = $conn->prepare("DELETE FROM order_items WHERE order_id=?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    // ✅ Sau đó xóa đơn hàng
    $stmt = $conn->prepare("DELETE FROM orders WHERE id=?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        // Quay lại danh sách đơn hàng
        header("Location: orders.php");
        exit;
    } else {
        echo "❌ Lỗi khi xóa đơn hàng: " . $conn->error;
    }
} else {
    echo "❌ Không tìm thấy ID đơn hàng.";
}
?>

