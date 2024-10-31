<?php
include 'connect.php';

// Giả sử token được truyền qua URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Tính toán thời gian hết hạn của token (1 giờ kể từ bây giờ)
    $expiry_time = date("Y-m-d H:i:s", strtotime("+1 hour"));
    
    // Query để lấy thông tin token và cập nhật thời gian hết hạn
    $stmt = $conn->prepare("SELECT MaTK, TgHethan FROM datlaimk WHERE Token = ? AND TrangThai = 'Active'");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_time = date("Y-m-d H:i:s");

        // Kiểm tra nếu token vẫn hợp lệ
        if ($current_time <= $row['TgHethan']) {
            // Cập nhật thời gian hết hạn trong bảng 'datlaimk'
            $update_stmt = $conn->prepare("UPDATE datlaimk SET TgHethan = ? WHERE Token = ?");
            $update_stmt->bind_param("ss", $expiry_time, $token);
            
            if ($update_stmt->execute()) {
                echo "<p>Token is valid. You can reset your password.</p>";
            } else {
                echo "<p>Lỗi khi cập nhật thời gian hết hạn.</p>";
            }
        } else {
            echo "<p>Token has expired. Please request a new password reset.</p>";
        }
    }
}
?>
