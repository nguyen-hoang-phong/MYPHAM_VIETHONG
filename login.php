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

  <div class="container" style="max-width:400px; margin:auto;">
    <h2 style="text-align:center;">Đăng nhập</h2>
    <form id="loginForm" method="post" action="login.php" autocomplete="off">
      <input type="email" name="email" placeholder="Email" required autocomplete="off"><br><br>
      <input type="password" name="password" placeholder="Mật khẩu" required autocomplete="off"><br><br>
      <button type="submit" name="login">Đăng nhập</button>
    </form>

    <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>

    <p style="text-align:center;">Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
  </div>

  <?php include("includes/footer.php"); ?>
</body>
</html>
