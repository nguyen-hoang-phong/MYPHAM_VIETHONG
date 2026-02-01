<?php
session_start();
include("../includes/admin_db.php");
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];

    if (strlen($password) < 6) {
        $error = "Mật khẩu phải có ít nhất 6 ký tự!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $check = $conn->prepare("SELECT id FROM users WHERE email=?");
        $check->bind_param("s", $email);
        $check->execute();
        $checkResult = $check->get_result();

        if ($checkResult->num_rows > 0) {
            $error = "Email này đã tồn tại!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, 'admin')");
            $stmt->bind_param("sss", $fullname, $email, $hashedPassword);

            if ($stmt->execute()) {
                $success = "✅ Tạo admin thành công!";
            } else {
                $error = "❌ Lỗi khi tạo admin: " . $conn->error;
            }
        }
    }
}
?>

<form method="post">
  <h2>Đăng ký Admin mới</h2>
  <input type="text" name="fullname" placeholder="Họ tên" required autocomplete="off"><br>
  <input type="email" name="email" placeholder="Email" required autocomplete="off"><br>
  <input type="password" name="password" placeholder="Mật khẩu (≥ 6 ký tự)" required autocomplete="new-password"><br>
  <button type="submit">Tạo Admin</button>
</form>

<p><a href="login.php">⬅ Quay lại trang Đăng nhập</a></p>
<?php
if (!empty($error)) echo "<p style='color:red;'>$error</p>";
if (!empty($success)) echo "<p style='color:green;'>$success</p>";
?>
