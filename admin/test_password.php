<?php
include("../includes/admin_db.php");
$email = "phongds1209@gmail.com.vn";
$input_password = "728288"; // mật khẩu bạn muốn kiểm tra

$stmt = $conn->prepare("SELECT password FROM users WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row && isset($row['password'])) {
    $hash = $row['password'];
    if (password_verify($input_password, $hash)) {
        echo "✅ Mật khẩu nhập vào KHỚP với mật khẩu trong DB.";
    } else {
        echo "❌ Mật khẩu nhập vào KHÔNG khớp.";
    }
} else {
    echo "Không tìm thấy tài khoản với email: " . $email;
}
?>
