<?php
include 'connect.php';

require("vendor/phpmailer/phpmailer/src/PHPMailer.php");
require("vendor/phpmailer/phpmailer/src/SMTP.php");
require("vendor/phpmailer/phpmailer/src/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['submit'])) {
    $userEmail = $_POST['email'];

    // Truy vấn thông tin tài khoản và tên khách hàng
    $stmt = $conn->prepare("SELECT thongtincanhan.MaKH, thongtincanhan.TenKH, thongtintaikhoan.MaTK 
                            FROM thongtincanhan 
                            JOIN thongtintaikhoan ON thongtincanhan.MaKH = thongtintaikhoan.MaTK 
                            WHERE thongtincanhan.Email = ?");
    if (!$stmt) {
        die("Lỗi trong câu truy vấn SELECT: " . $conn->error);
    }
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('Tài khoản không tồn tại');</script>";
    } else {
        $row = $result->fetch_assoc();
        $maKH = $row['MaKH'];
        $maTK = $row['MaTK'];
        $TenKH = $row['TenKH'];

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->CharSet = "utf-8";
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "nguyenminh26721@gmail.com";
            $mail->Password = "pxkg xwep jpyc ifvd";
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;

            $mail->setFrom("nguyenminh26721@gmail.com", "Minh Minh");
            $mail->addAddress($userEmail);

            // Tạo token mới và thời gian hết hạn
            $token = bin2hex(random_bytes(16));
            $expiry = date("Y-m-d H:i:s", strtotime('+1 hour'));

            // Cập nhật bảng datlaimk với token mới
            $insertToken = $conn->prepare("INSERT INTO datlaimk (MaToken, MaTK, Token, TgTao, TgHethan, TrangThai) VALUES (?, ?, ?, NOW(), ?, 'Active')");
            if (!$insertToken) {
                die("Lỗi trong câu truy vấn INSERT: " . $conn->error);
            }
            $maToken = bin2hex(random_bytes(8)); // Tạo mã token ngẫu nhiên
            $insertToken->bind_param("ssss", $maToken, $maTK, $token, $expiry);
            $insertToken->execute();

            // Cập nhật NguoiSua trong thongtintaikhoan
            $updateNguoiSua = $conn->prepare("UPDATE thongtintaikhoan SET NguoiSua = ? WHERE MaTK = ?");
            $updateNguoiSua->bind_param("ss", $TenKH, $maTK);
            $updateNguoiSua->execute();

            $resetLink = "http://localhost/Ktr2/Datlaimk.php?token=" . $token;
            $mail->isHTML(true);
            $mail->Subject = "Quên mật khẩu";
            $mail->Body = "
                <h3>Xin chào,</h3>
                <p>Bạn đã yêu cầu đặt lại mật khẩu. Nhấp vào nút bên dưới để tiếp tục:</p>
                <a href='$resetLink' style='display: inline-block; padding: 10px 20px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px; font-weight: bold;'>Đặt lại mật khẩu</a>
                <p>Nếu bạn không yêu cầu điều này, vui lòng bỏ qua email này.</p>
                <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
            ";
            $mail->send();

            echo "<script>alert('Email đã được gửi thành công đến $userEmail');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Không thể gửi email. Lỗi: " . $mail->ErrorInfo . "');</script>";
        }
    }
}
?>
