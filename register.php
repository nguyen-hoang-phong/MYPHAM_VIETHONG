<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký | Mỹ Phẩm Việt Hồng</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include("includes/header.php"); ?>

<div class="login-page">

    <form method="post" action="register_process.php" class="login-form">
        <h2 class="login-title">Đăng ký tài khoản</h2>

        <input type="text" name="fullname" placeholder="Họ tên" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Mật khẩu" required>

        <button type="submit">Đăng ký</button>
    </form>

    <p class="login-register">
        Đã có tài khoản? <a href="login.php">Đăng nhập</a>
    </p>
</div>

<?php include("includes/footer.php"); ?>

</body>
</html>