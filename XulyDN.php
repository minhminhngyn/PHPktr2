<?php
include "connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT MatKhau FROM thongtintaikhoan WHERE TenDangNhap = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['MatKhau'])) {
            echo "<p style='color: green;'>Đăng nhập thành công!</p>";
            // Điều hướng tới trang chính (sau khi đăng nhập thành công)
        } else {
            echo "<p style='color: red;'>Sai mật khẩu!</p>";
        }
    } else {
        echo "<p style='color: red;'>Tên đăng nhập không tồn tại!</p>";
    }
}
?>
