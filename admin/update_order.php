<?php
session_start();
include("../includes/admin_db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = (int)$_POST['id'];
    $status = $_POST['status'];

    // Cập nhật trạng thái đơn hàng
    $stmt = $conn->prepare("UPDATE orders SET status=? WHERE id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();

    // Nếu trạng thái là Hoàn tất thì trừ kho
    if ($status === 'Hoàn tất') {
        // Kiểm tra trạng thái hiện tại để tránh trừ kho nhiều lần
        $check = $conn->prepare("SELECT status FROM orders WHERE id=?");
        $check->bind_param("i", $id);
        $check->execute();
        $current_status = $check->get_result()->fetch_assoc()['status'];

        if ($current_status === 'Hoàn tất') {
            // Truy vấn chi tiết sản phẩm trong đơn hàng
            $stmt = $conn->prepare("SELECT product_id, quantity FROM order_items WHERE order_id=?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();

            // Trừ tồn kho từng sản phẩm
            while ($item = $result->fetch_assoc()) {
                $product_id = $item['product_id'];
                $qty        = $item['quantity'];

                $update = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id=?");
                $update->bind_param("ii", $qty, $product_id);
                $update->execute();
            }
        }
    }

    header("Location: orders.php");
    exit;
}
?>

