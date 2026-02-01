<?php
session_start();
include("includes/header.php");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Thanh toán - Mỹ Phẩm Việt Hồng</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2 style="text-align:center;">Hóa đơn thanh toán</h2>

<?php if (!empty($_SESSION['cart'])): ?>
<table class="cart">
  <tr>
    <th>Sản phẩm</th>
    <th>Số lượng</th>
    <th>Giá (chưa thuế VAT)</th>
    <th>Tổng</th>
  </tr>
  <?php
  $total = 0;
  $total_tax = 0;

  foreach ($_SESSION['cart'] as $id => $item) {
      $subtotal = $item['price'] * $item['quantity'];
      $item_tax_rate = isset($item['tax_rate']) ? $item['tax_rate'] : 0;
      $item_tax = $subtotal * $item_tax_rate;

      $total += $subtotal;
      $total_tax += $item_tax;
  ?>
  <tr>
    <td><?php echo htmlspecialchars($item['name']); ?></td>
    <td><?php echo (int)$item['quantity']; ?></td>
    <td><?php echo number_format($item['price']); ?> VNĐ</td>
    <td><?php echo number_format($subtotal); ?> VNĐ</td>
  </tr>
  <?php } ?>
  <tr>
    <td colspan="3" align="right"><strong>Tổng cộng trước thuế:</strong></td>
    <td><strong><?php echo number_format($total); ?> VNĐ</strong></td>
  </tr>
  <tr>
    <td colspan="3" align="right"><strong>Tổng thuế:</strong></td>
    <td><strong><?php echo number_format($total_tax); ?> VNĐ</strong></td>
  </tr>
  <tr>
    <td colspan="3" align="right"><strong>Tổng cộng sau thuế:</strong></td>
    <td><strong><?php echo number_format($total + $total_tax); ?> VNĐ</strong></td>
  </tr>
</table>

<div style="text-align:center; margin-top:20px;">
 <form class="checkout-form" method="post" action="process_order.php">
  <input type="text" name="customer_name" placeholder="Họ tên" required><br><br>
  <input type="text" name="phone" placeholder="Số điện thoại" required><br><br>
  <textarea name="address" placeholder="Địa chỉ giao hàng" required></textarea><br><br>

  <input type="hidden" name="total" value="<?php echo $total + $total_tax; ?>">

  <button type="submit">Hoàn tất thanh toán</button>
</form>

  <a href="cart.php"><button>Quay lại giỏ hàng</button></a>
</div>


<?php else: ?>
<p style="text-align:center;">Chưa có sản phẩm nào để thanh toán.</p>
<div style="text-align:center; margin-top:20px;">
  <a href="products.php"><button>Quay lại mua hàng</button></a>
</div>
<?php endif; ?>

<?php include("includes/footer.php"); ?>
</body>
</html>
