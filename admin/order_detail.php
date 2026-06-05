<?php
session_start();
include("../includes/admin_db.php");
include("../includes/admin_header.php");

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Lấy thông tin đơn hàng
$order = $conn->query("SELECT * FROM orders WHERE id = $order_id")->fetch_assoc();

// Lấy chi tiết sản phẩm trong đơn hàng
$stmt = $conn->prepare("
  SELECT 
      p.name,
      oi.quantity,
      oi.price,
      p.tax_percent
  FROM order_items oi
  JOIN products p ON oi.product_id = p.id
  WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<div style="max-width:800px; margin:20px auto;">
  <h2>🧾 Chi tiết đơn hàng #<?= $order_id ?></h2>
  <p><strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
  <p><strong>SĐT:</strong> <?= htmlspecialchars($order['phone']) ?></p>
  <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
  <p><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status']) ?></p>
  <p><strong>Ngày tạo:</strong> <?= $order['created_at'] ?></p>

  <table border="1" width="100%" cellpadding="8" style="margin-top:20px;">
  <tr>
    <th>Sản phẩm</th>
    <th>Số lượng</th>
    <th>Đơn giá</th>
    <th>Thuế (%)</th>
    <th>Tiền VAT</th>
    <th>Tổng sau thuế</th>
  </tr>

<?php
$total = 0;
$total_tax = 0;

while ($row = $result->fetch_assoc()):

    $subtotal = $row['price'] * $row['quantity'];

    $tax_percent = $row['tax_percent'];

    $item_tax = $subtotal * $tax_percent / 100;

    $total_after_tax = $subtotal + $item_tax;

    $total += $subtotal;
    $total_tax += $item_tax;
?>

<tr>
  <td><?= htmlspecialchars($row['name']) ?></td>

  <td><?= $row['quantity'] ?></td>

  <td><?= number_format($row['price']) ?> VNĐ</td>

  <td><?= $tax_percent ?>%</td>

  <td><?= number_format($item_tax) ?> VNĐ</td>

  <td><?= number_format($total_after_tax) ?> VNĐ</td>
</tr>

<?php endwhile; ?>

<tr>
  <td colspan="5" align="right">
    <strong>Tổng trước thuế:</strong>
  </td>

  <td>
    <strong><?= number_format($total) ?> VNĐ</strong>
  </td>
</tr>

<tr>
  <td colspan="5" align="right">
    <strong>Tổng VAT:</strong>
  </td>

  <td>
    <strong><?= number_format($total_tax) ?> VNĐ</strong>
  </td>
</tr>

<tr>
  <td colspan="5" align="right">
    <strong>Tổng thanh toán:</strong>
  </td>

  <td>
    <strong>
      <?= number_format($total + $total_tax) ?> VNĐ
    </strong>
  </td>
</tr>

</table>
</div>

<?php include("../includes/admin_footer.php"); ?>
