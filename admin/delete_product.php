<?php
session_start();
include("../includes/admin_header.php");
include("../includes/admin_db.php");

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Xóa ảnh nếu có
    $result = $conn->query("SELECT image FROM products WHERE id=$id");
    if ($row = $result->fetch_assoc()) {
        $file = "../uploads/images/" . $row['image'];
        if (!empty($row['image']) && file_exists($file)) {
            unlink($file); // xóa file ảnh
        }
    }

    // Nếu sản phẩm có liên kết trong order_items thì xóa trước
    $conn->query("DELETE FROM order_items WHERE product_id = $id");

    // Xóa sản phẩm
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: manage_products.php"); // quay lại danh sách sản phẩm
        exit;
    } else {
        echo "❌ Lỗi khi xóa sản phẩm: " . $conn->error;
    }
} else {
    echo "❌ ID sản phẩm không hợp lệ!";
}
?>

