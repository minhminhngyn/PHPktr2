<?php
session_start();
include 'connect.inp'; // Kết nối đến cơ sở dữ liệu

if (isset($_GET['user']) && isset($_GET['token'])) {
    $username = $_GET['user'];
    $token = $_GET['token'];

    // Kiểm tra token xác nhận
    $stmt = $con->prepare("SELECT MaTK FROM thongtintaikhoan WHERE TenDangNhap = ? AND Tokenxacnhan = ? AND Ngayhethanxacnhan > NOW()");
    $stmt->bind_param("ss", $username, $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($maTK);
        $stmt->fetch();

        // Mở khóa tài khoản
        $stmt = $con->prepare("UPDATE thongtintaikhoan SET TrangThaiHoatDong = 1, Solansaidangnhap = 0, Lanchothulai = NULL, Tokenxacnhan = NULL, Ngayhethanxacnhan = NULL WHERE MaTK = ?");
        $stmt->bind_param("i", $maTK);
        $stmt->execute();
        echo "Tài khoản của bạn đã được mở khóa. Bạn có thể đăng nhập lại.";
        header("Location: index.php"); 
        exit();
    } else {
        echo "Token không hợp lệ hoặc đã hết hạn.";
    }
    $stmt->close();
} else {
    echo "Yêu cầu không hợp lệ.";
}
?>
