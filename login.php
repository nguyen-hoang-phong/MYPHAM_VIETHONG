<?php
session_start();
include("includes/db.php");

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Chuẩn bị truy vấn
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
   
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Lưu session đầy đủ thông tin 
            $_SESSION['user'] = [
                'id'    => $user['id'],
                'email' => $user['email'],
                'role'  => $user['role'] // 'admin' hoặc 'user'
            ];

            // Điều hướng theo role
           header("Location: index.php");
exit;
           
        } else {
            $error = "Sai mật khẩu!";
        }
    } else {
        $error = "Email không tồn tại!";
    }
}
 /*   
    Đoạn code sử dụng prepared statement để truy vấn dữ liệu từ bảng users theo email.
Hàm prepare() tạo câu lệnh SQL có tham số ?,
bind_param() gán giá trị email vào tham số đó,
execute() thực thi truy vấn,
và get_result() lấy kết quả trả về từ database.
Cách này giúp chống SQL Injection và tăng tính bảo mật
Đoạn code kiểm tra kết quả truy vấn, nếu tồn tại user thì lấy thông tin bằng fetch_assoc().
Sau đó dùng password_verify() để so sánh mật khẩu người dùng nhập với mật khẩu đã mã hóa trong database.
Nếu đúng, lưu thông tin user vào session và chuyển hướng trang.
Nếu sai, thông báo lỗi mật khẩu.
Nếu email không tồn tại, thông báo lỗi tương ứng

    */
?>


<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập | Mỹ Phẩm Việt Hồng</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include("includes/header.php"); ?>

<div class="login-page">
   

    <form id="loginForm" method="post" action="login.php" class="login-form" >
         <h2 class="login-title">Đăng nhập</h2>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>
        

        <button type="submit" name="login">Đăng nhập</button>
    </form>

    <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>

    <p class="login-register">
        Chưa có tài khoản? <a href="register.php">Đăng ký</a>
    </p>
</div>

  <?php include("includes/footer.php"); ?>
</body>
</html>
