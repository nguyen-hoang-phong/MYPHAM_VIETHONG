<?php
session_start();
include("includes/db.php");

// Kiểm tra giỏ hàng
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die("❌ Giỏ hàng trống, không thể thanh toán.");
}

// Lấy thông tin khách hàng từ form POST (có thể thêm validate)
$customer_name = $_POST['customer_name'] ?? '';
$phone         = $_POST['phone'] ?? '';
$address       = $_POST['address'] ?? '';
$total         = isset($_POST['total']) ? (float)$_POST['total'] : 0.0;

// Kiểm tra dữ liệu cơ bản
if ($customer_name === '' || $phone === '' || $address === '') {
    die("❌ Thiếu thông tin khách hàng.");
}
if ($total <= 0) {
    die("❌ Tổng tiền không hợp lệ.");
}

// Bắt đầu transaction để đảm bảo an toàn dữ liệu
$conn->begin_transaction();

try {
    // 1) Tạo đơn hàng
    $stmt_order = $conn->prepare("INSERT INTO orders (customer_name, phone, address, total) VALUES (?, ?, ?, ?)");
    if (!$stmt_order) {
        throw new Exception("Lỗi chuẩn bị câu lệnh orders: " . $conn->error);
    }

    $stmt_order->bind_param("sssd", $customer_name, $phone, $address, $total);
    if (!$stmt_order->execute()) {
        throw new Exception("Lỗi thực thi orders: " . $stmt_order->error);
    }

    $order_id = $stmt_order->insert_id;
    $stmt_order->close();

    // 2) Chuẩn bị statement ghi chi tiết đơn hàng
    $stmt_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    if (!$stmt_item) {
        throw new Exception("Lỗi chuẩn bị câu lệnh order_items: " . $conn->error);
    }

    // 3) Chuẩn bị statement kiểm tra tồn kho (dùng prepared để an toàn)
    $stmt_check = $conn->prepare("SELECT stock FROM products WHERE id = ?");
    if (!$stmt_check) {
        throw new Exception("Lỗi chuẩn bị câu lệnh kiểm tra tồn kho: " . $conn->error);
    }

    // 4) Chuẩn bị statement trừ tồn kho
    $stmt_update = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
    if (!$stmt_update) {
        throw new Exception("Lỗi chuẩn bị câu lệnh cập nhật tồn kho: " . $conn->error);
    }

    // 5) Duyệt từng sản phẩm trong giỏ hàng
    foreach ($_SESSION['cart'] as $product_id => $item) {
        $product_id = (int)$product_id;
        $quantity   = (int)$item['quantity'];
        $price      = (float)$item['price'];

        if ($quantity <= 0 || $price < 0) {
            throw new Exception("❌ Dữ liệu sản phẩm không hợp lệ (ID $product_id).");
        }

        // Kiểm tra tồn kho
        $stmt_check->bind_param("i", $product_id);
        if (!$stmt_check->execute()) {
            throw new Exception("Lỗi thực thi kiểm tra tồn kho: " . $stmt_check->error);
        }
        $result = $stmt_check->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("❌ Sản phẩm ID $product_id không tồn tại trong kho!");
        }
        $product = $result->fetch_assoc();
        if ((int)$product['stock'] < $quantity) {
            throw new Exception("❌ Sản phẩm ID $product_id không đủ tồn kho!");
        }

        // Ghi chi tiết đơn hàng
        $stmt_item->bind_param("iiid", $order_id, $product_id, $quantity, $price);
        if (!$stmt_item->execute()) {
            throw new Exception("Lỗi thực thi order_items: " . $stmt_item->error);
        }

        // Trừ tồn kho
        $stmt_update->bind_param("ii", $quantity, $product_id);
        if (!$stmt_update->execute()) {
            throw new Exception("Lỗi cập nhật tồn kho: " . $stmt_update->error);
        }
    }

    // Đóng các statement
    $stmt_item->close();
    $stmt_check->close();
    $stmt_update->close();

    // 6) Commit transaction
    $conn->commit();

    // 7) Xóa giỏ hàng và chuyển hướng
    unset($_SESSION['cart']);
    header("Location: order_success.php");
    exit;

} catch (Exception $e) {
    // Rollback nếu có lỗi
    $conn->rollback();
    die("❌ Đặt hàng thất bại: " . $e->getMessage());
}
