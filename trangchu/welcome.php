<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../dangnhap/dangnhap.php"); // Chuyển hướng về trang đăng nhập nếu không có session
    exit;
}

echo "Chào mừng bạn đến với trang chính!";
?>

<!-- Nút Đăng xuất -->
<form action="../dangnhap/xldangnhap.php" method="get" style="margin-top: 20px;">
    <input type="hidden" name="action" value="logout">
    <button type="submit">Đăng xuất</button>
</form>
