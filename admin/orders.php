<?php
session_start();
include("../includes/admin_header.php");
include("../includes/admin_db.php");

// ✅ Xử lý khi nhấn "Hoàn tất" đơn hàng
if (isset($_GET['complete_id'])) {
    $order_id = (int)$_GET['complete_id'];

    // Cập nhật trạng thái đơn hàng
    $stmt = $conn->prepare("UPDATE orders SET status='Hoàn tất' WHERE id=?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    // Trừ tồn kho cho từng sản phẩm trong đơn hàng
    $items = $conn->query("SELECT product_id, quantity FROM order_items WHERE order_id=$order_id");
    while ($item = $items->fetch_assoc()) {
        $product_id = $item['product_id'];
        $qty = $item['quantity'];

        $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $update_stock->bind_param("ii", $qty, $product_id);
        $update_stock->execute();
    }

    echo "<p style='color:green; text-align:center;'>✅ Đơn hàng #$order_id đã được đánh dấu Hoàn tất và tồn kho đã được cập nhật!</p>";
}

// Thống kê đơn hàng 
$total_orders = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count'];
$total_revenue = $conn->query("SELECT SUM(total) AS sum FROM orders")->fetch_assoc()['sum'];
$completed_revenue = $conn->query("SELECT SUM(total) AS sum FROM orders WHERE status='Hoàn tất'")->fetch_assoc()['sum'];
$status_counts = $conn->query("SELECT status, COUNT(*) AS count FROM orders GROUP BY status");

//tìm kiếm đơn hàng theo tên khách hàng


 // Lấy danh sách đơn hàng
if (isset($_GET['search_name']) && $_GET['search_name'] !== '') {
    $search_name = '%' . $conn->real_escape_string($_GET['search_name']) . '%';
    $stmt = $conn->prepare("SELECT * FROM orders WHERE customer_name LIKE ? ORDER BY id DESC");
    $stmt->bind_param("s", $search_name);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM orders ORDER BY id DESC");
}

?>

<link rel="stylesheet" href="../css/orders.css">
<div class="container">
 <div style="margin-bottom:20px; text-align:center;">
  <h3>📊 Thống kê đơn hàng</h3>
  <p><strong>Tổng số đơn hàng:</strong> <?= $total_orders ?></p>
  <p><strong>Tổng doanh thu:</strong> <?= number_format($total_revenue, 0, ',', '.') ?> VNĐ</p>
  <p><strong>Doanh thu từ đơn đã hoàn tất:</strong> <?= number_format($completed_revenue, 0, ',', '.') ?> VNĐ</p>

  <h4>Chi tiết theo trạng thái:</h4>
  <ul style="list-style:none; padding:0;">
    <?php while ($row = $status_counts->fetch_assoc()): ?>
      <li><?= htmlspecialchars($row['status']) ?>: <?= $row['count'] ?> đơn</li>
    <?php endwhile; ?>
  </ul>
</div>

<!-- Form tìm kiếm đơn hàng theo tên khách hàng --> <form method="get" style="text-align:center; margin-bottom:20px;"> <input type="text" name="search_name" placeholder="Nhập tên khách hàng..." value="<?= isset($_GET['search_name']) ? htmlspecialchars($_GET['search_name']) : '' ?>"> 
<button type="submit">🔍 Tìm kiếm</button> </form>

  <h2 style="text-align:center; margin-top:10px;margin-bottom:5px">📦 Danh sách đơn hàng</h2>
  <table border="1" width="100%">
   <tr>
      <th>ID</th>
      <th>Khách hàng</th>
      <th>SĐT</th>
      <th>Địa chỉ</th>
      <th>Tổng tiền</th>
      <th>Trạng thái</th>
      <th>Ngày tạo</th>
      <th>Hành động</th>
    </tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
  <td><?= $row['id'] ?></td>
  <td><?= htmlspecialchars($row['customer_name']) ?></td>
  <td><?= htmlspecialchars($row['phone']) ?></td>
  <td><?= htmlspecialchars($row['address']) ?></td>
  <td><?= number_format($row['total']) ?> VNĐ</td>
  <td><?= htmlspecialchars($row['status']) ?></td>
  <td><?= $row['created_at'] ?></td>
  <td>
  <div class="action-buttons">
    <form method="post" action="update_order.php" style="display:inline;">
      <input type="hidden" name="id" value="<?= $row['id'] ?>">
      <select name="status">
        <option value="Chờ xử lý" <?= $row['status']=='Chờ xử lý'?'selected':'' ?>>Chờ xử lý</option>
        <option value="Đang giao" <?= $row['status']=='Đang giao'?'selected':'' ?>>Đang giao</option>
        <option value="Hoàn tất" <?= $row['status']=='Hoàn tất'?'selected':'' ?>>Hoàn tất</option>
       
      </select>
      <button type="submit">Cập nhật</button>
    </form>

    <a href="orders.php?complete_id=<?= $row['id'] ?>" 
       onclick="return confirm('Xác nhận hoàn tất đơn hàng này?')" 
       class="btn-complete">Đánh dấu Hoàn tất</a>
     <!-- Nút chi tiết đơn hàng -->
    <a href="order_detail.php?id=<?= $row['id'] ?>" 
       class="btn-detail">Chi tiết</a>
    <a href="delete_order.php?id=<?= $row['id'] ?>" 
       onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?')" 
       class="btn-delete">Xóa</a>
       
  </div>
</td>

</tr>
<?php endwhile; ?>

  </table>
</div>
<?php include("../includes/admin_footer.php"); ?>
