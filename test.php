<?php
// Import thư viện PHPMailer
require("vendor/phpmailer/phpmailer/src/PHPMailer.php");
require("vendor/phpmailer/phpmailer/src/SMTP.php");
require("vendor/phpmailer/phpmailer/src/Exception.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendTestEmail() {
    $mail = new PHPMailer(true);
    try {
        // Cấu hình SMTP
        $mail->isSMTP();
        $mail->CharSet = "utf-8";
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = "nguyenminh26721@gmail.com"; // Địa chỉ email của bạn
        $mail->Password = "pxkg xwep jpyc ifvd";       // Mật khẩu ứng dụng của email
        $mail->SMTPSecure = "ssl";
        $mail->Port = 465;

        // Cấu hình người gửi và người nhận
        $mail->setFrom("nguyenminh26721@gmail.com", "Minh Minh");
        $mail->addAddress("juec1222003hainguyen@gmail.com"); // Địa chỉ email bạn muốn gửi tới

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = "Thử nghiệm gửi email qua PHPMailer";
        $mail->Body = "Xin chào,<br><br>Đây là email thử nghiệm được gửi bằng PHPMailer.";

        // Tùy chọn bảo mật
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        // Gửi email
        $mail->send();
        echo "Email đã được gửi thành công đến juec1222003hainguyen@gmail.com";
    } catch (Exception $e) {
        echo "Không thể gửi email. Lỗi: " . $mail->ErrorInfo;
    }
}

// Gọi hàm gửi email thử nghiệm
sendTestEmail();
?>
