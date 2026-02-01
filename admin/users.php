<?php
session_start();
include("../includes/admin_header.php");
include("../includes/admin_db.php");


$where = [];
$params = [];
$types = '';

if (isset($_GET['search_name']) && $_GET['search_name'] !== '') {
    $where[] = "fullname LIKE ?";
    $params[] = '%' . $_GET['search_name'] . '%';
    $types .= 's';
}

if (isset($_GET['search_role']) && $_GET['search_role'] !== '') {
    $where[] = "role = ?";
    $params[] = $_GET['search_role'];
    $types .= 's';
}

$sql = "SELECT id, fullname, email, role FROM users";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

?>
<div class="container">

  <!-- 🔍 Form tìm kiếm người dùng -->
  <form method="get" style="text-align:center; margin-bottom:20px;">
    <input type="text" name="search_name" placeholder="Tìm theo tên..." 
           value="<?= isset($_GET['search_name']) ? htmlspecialchars($_GET['search_name']) : '' ?>">
    <select name="search_role">
      <option value="">-- Vai trò --</option>
      <option value="admin" <?= isset($_GET['search_role']) && $_GET['search_role']=='admin' ? 'selected' : '' ?>>Admin</option>
      <option value="user" <?= isset($_GET['search_role']) && $_GET['search_role']=='user' ? 'selected' : '' ?>>User</option>
    </select>
    <button type="submit" style="padding: 5px 15px; width: auto;">🔍 Tìm kiếm</button>

  </form>
  <h2 style="text-align:center;">👥 Danh sách người dùng</h2>
  <table border="1" width="100%">
    <tr><th>ID</th><th>Họ tên</th><th>Email</th><th>Vai trò</th><th>Hành động</th></tr>
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['fullname']) ?></td>
      <td><?= htmlspecialchars($row['email']) ?></td>
      <td style="color:<?= $row['role']==='admin'?'red':'green' ?>"><?= $row['role'] ?></td>
      <td>
        <a href="edit_user.php?id=<?= $row['id'] ?>">✏️ Sửa</a> |
        <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Xóa người dùng này?');">🗑️ Xóa</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>
</div>
<?php include("../includes/admin_footer.php"); ?>

