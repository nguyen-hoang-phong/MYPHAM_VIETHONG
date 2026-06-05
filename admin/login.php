<?php
session_start();
include("../includes/admin_db.php");

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {// Dòng này kiểm tra request gửi lên có phải là POST không //$_SERVER là một biến toàn cục,chứa thông tin về:Server (máy chủ),Request (yêu cầu từ trình duyệt),Đường dẫn, header, phương thức gửi dữ liệu...
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?"); //   "?"
    $stmt->bind_param("s", $email); // Gán giá trị vào dấu "?"
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin) {
        if ($admin['role'] !== 'admin') {
            $error = "Tài khoản không có quyền admin!";  //Chặn user thường
        } elseif (password_verify($password, $admin['password'])) {//Chỉ admin + đúng mật khẩu mới login
            $_SESSION['admin'] = $admin['id'];
            header("Location: dashboard.php");// Điều hướng vào trang admin
            exit;
        } else {
            $error = "Sai mật khẩu!";
        }
    } else {
        $error = "Email không tồn tại!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập Admin</title>
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <div class="login-wrapper">
    <form method="post" class="login-form">
      <h2>Đăng nhập Admin</h2>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Mật khẩu" required>
      <button type="submit">Đăng nhập</button>
      <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
      <p>Chưa có tài khoản? <a href="add_admin.php">Đăng ký Admin mới</a></p>
    </form>
  </div>
</body>
</html>
