<?php
session_start();

// Xóa toàn bộ dữ liệu phiên
$_SESSION = [];
session_unset();
session_destroy();

// Chuyển về trang đăng nhập
header("Location: login.php");
exit;
