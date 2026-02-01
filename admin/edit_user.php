<?php
session_start();
include("../includes/admin_header.php");
include("../includes/admin_db.php");
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET fullname=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $fullname, $email, $role, $id);
    $stmt->execute();
    header("Location: users.php");
    exit;
}
?>
<link rel="stylesheet" href="../css/edit_user.css">
<div style="margin-bottom: 30px;"></div>
<div style="margin-bottom: 5px;"> <a href="users.php" class="btn-edit">✏️ SỬA THÔNG TIN</a> </div>

<form method="post" class="user-form">
  <label>Họ tên:</label>
  <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" required>

  <label>Email:</label>
  <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

  <label>Vai trò:</label>
  <select name="role">
    <option value="user" <?= $user['role']=='user'?'selected':'' ?>>User</option>
    <option value="admin" <?= $user['role']=='admin'?'selected':'' ?>>Admin</option>
  </select>

  <button type="submit">Cập nhật</button>
</form>

<?php include("../includes/admin_footer.php"); ?>
