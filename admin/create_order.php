<?php
session_start();
include("../includes/admin_db.php");

// Lấy dữ liệu từ form
$customer_name = $_POST['customer_name'];
$phone         = $_POST['phone'];
$address       = $_POST['address'];
$total         = $_POST['total'];
$status        = "Chờ xử lý";

// 1. Thêm vào bảng orders
$stmt = $conn->prepare("INSERT INTO orders (customer_name, phone, address, total, status, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("sssds", $customer_name, $phone, $address, $total, $status);
$stmt->execute();
$order_id = $stmt->insert_id;

// 2. Thêm chi tiết sản phẩm vào order_items
foreach ($_POST['items'] as $item) {
    $product_id = $item['product_id'];
    $qty        = $item['quantity'];
    $price      = $item['price'];

    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt_item->bind_param("iiid", $order_id, $product_id, $qty, $price);
    $stmt_item->execute();
}

echo "✅ Đơn hàng đã được tạo thành công!";
?>
