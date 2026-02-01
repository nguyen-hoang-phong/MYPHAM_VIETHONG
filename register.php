<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng ký | Mỹ Phẩm Việt Hồng</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <?php include("includes/header.php"); ?>

  <div class="container" style="max-width:400px; margin:auto;">
    <h2 style="text-align:center;">Đăng ký tài khoản</h2>
    <form method="post" action="register_process.php">
      <label>Họ tên:</label>
      <input type="text" name="fullname" required><br><br>

      <label>Email:</label>
      <input type="email" name="email" required><br><br>

      <label>Mật khẩu:</label>
      <input type="password" name="password" required><br><br>

      <button type="submit">Đăng ký</button>
    </form>
    <p style="text-align:center;">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
  </div>

  <?php include("includes/footer.php"); ?>
</body>
</html>
