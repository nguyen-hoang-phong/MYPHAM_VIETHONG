<?php
session_start();
include("includes/header.php");

// Nếu giỏ hàng chưa có thì khởi tạo
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý thêm sản phẩm vào giỏ
if (isset($_GET['action']) && $_GET['action'] == 'add') {
$id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] += $quantity;
    } else {
        // Ví dụ dữ liệu sản phẩm (bạn có thể lấy từ DB hoặc mảng)
include("includes/db.php"); // nếu chưa có

$stmt = $conn->prepare("SELECT name, price FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
    $_SESSION['cart'][$id] = [
        "name" => $product['name'],
        "price" => $product['price'],
        "quantity" => $quantity
    ];
}
$stmt->close();


        if (isset($products[$id])) {
            $_SESSION['cart'][$id] = [
                "name" => $products[$id]['name'],
                "price" => $products[$id]['price'],
                "quantity" => $quantity
            ];
        }
    }
}

// Xử lý tăng/giảm số lượng
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    $id = $_GET['id'];
    if (isset($_SESSION['cart'][$id])) {
        if ($_GET['type'] == 'increase') {
            $_SESSION['cart'][$id]['quantity']++;
        } elseif ($_GET['type'] == 'decrease' && $_SESSION['cart'][$id]['quantity'] > 1) {
            $_SESSION['cart'][$id]['quantity']--;
        }
    }
}
if (isset($_GET['action']) && $_GET['action'] == 'set_tax') {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $tax_rate = isset($_POST['tax_rate']) ? (float)$_POST['tax_rate'] : 0;

    if (isset($_SESSION['cart'][$id])) {
        if ($tax_rate >= 0 && $tax_rate <= 100) {
            $_SESSION['cart'][$id]['tax_rate'] = $tax_rate / 100;
        }
    }
}




// Xử lý xóa sản phẩm
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    unset($_SESSION['cart'][$id]);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Giỏ hàng - Mỹ Phẩm Việt Hồng</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h2>Giỏ hàng của bạn</h2>

<?php if (!empty($_SESSION['cart'])): ?>
<table class="cart">
  <tr>
    <th>Sản phẩm</th>
    <th>Số lượng</th>
    <th>Giá (chưa thuế VAT)</th>
    <th>Tổng</th>
    <th>Thuế (%)</th>
    <th>Hành động</th>
  </tr>
<?php
$total = 0;
$total_tax = 0;

foreach ($_SESSION['cart'] as $id => $item) {
    // Tính tổng tiền cho sản phẩm
    $subtotal = $item['price'] * $item['quantity'];

    // Lấy thuế suất riêng từng sản phẩm (nếu có)
    $item_tax_rate = isset($item['tax_rate']) ? $item['tax_rate'] : 0;
    $item_tax = $subtotal * $item_tax_rate;

    // Cộng dồn tổng tiền và tổng thuế
    $total += $subtotal;
    $total_tax += $item_tax;
?>
    <tr>
        <td><?php echo $item['name']; ?></td>
        <td>
            <a href="cart.php?action=update&type=decrease&id=<?php echo $id; ?>">-</a>
            <?php echo $item['quantity']; ?>
            <a href="cart.php?action=update&type=increase&id=<?php echo $id; ?>">+</a>
        </td>
        <td><?php echo number_format($item['price']); ?> VNĐ</td>
        <td><?php echo number_format($subtotal); ?> VNĐ</td>
        <td>
            <form method="post" action="cart.php?action=set_tax&id=<?php echo $id; ?>">
                <input type="number" name="tax_rate" 
                       value="<?php echo $item_tax_rate * 100; ?>" 
                       min="0" max="100" style="width:60px;">
                <button type="submit">OK</button>
            </form>
        </td>
        <td><a href="cart.php?action=delete&id=<?php echo $id; ?>">Xóa</a></td>
    </tr>
<?php
}
?>

<!-- Tổng kết cuối bảng -->
<tr>
    <td colspan="4" align="right"><strong>Tổng cộng trước thuế:</strong></td>
    <td colspan="2"><strong><?php echo number_format($total); ?> VNĐ</strong></td>
</tr>
<tr>
    <td colspan="4" align="right"><strong>Tổng thuế:</strong></td>
    <td colspan="2"><strong><?php echo number_format($total_tax); ?> VNĐ</strong></td>
</tr>
<tr>
    <td colspan="4" align="right"><strong>Tổng cộng sau thuế:</strong></td>
    <td colspan="2"><strong><?php echo number_format($total + $total_tax); ?> VNĐ</strong></td>
</tr>
</table> <!-- Đóng bảng tại đây -->

<!-- Nút nằm bên dưới bảng -->
<div style="text-align:center; margin-top:20px;">
  <a href="checkout.php"><button>Thanh toán</button></a>
  <a href="products.php"><button>Quay lại mua hàng</button></a>
</div>


<?php else: ?>
<p style="text-align:center;">Giỏ hàng của bạn hiện chưa có sản phẩm nào.</p>
<div style="text-align:center; margin-top:20px;">
  <a href="products.php"><button>Quay lại mua hàng</button></a>
</div>
<?php endif; ?>

<?php include("includes/footer.php"); ?>
</body>
</html>
