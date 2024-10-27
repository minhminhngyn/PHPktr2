<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffffff;
            color: #000000;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        h2 {
            color: #333;
        }
        input[type="email"],
        button {
            width: 90%;
            max-width: 400px;
            padding: 13px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #000;
        }
        button {
            background-color: #000;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #333;
        }
        p {
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
    </style>
    <script>
        function validateForm() {
            const email = document.getElementById("email").value;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email === "") {
                alert("Vui lòng nhập email!");
                return false;
            }
            if (!emailPattern.test(email)) {
                alert("Email không hợp lệ!");
                return false;
            }
            alert("Đã gửi mã, check email");
            return true;
        }
    </script>
</head>
<body>
    <h2>Quên Mật Khẩu</h2>
    <form action="forgot_password.php" method="POST" onsubmit="return validateForm()" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
        <input type="email" id="email" name="email" placeholder="Nhập email của bạn">
        <button type="submit" name="submit">Gửi Mã</button>
        <p>Đã có tài khoản? <a href="login.html" style="color: #000; text-decoration: none;">Đăng nhập</a></p>
    </form>

    <?php
include "connect.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\Ktr2\vendor\phpmailer\phpmailer\src\Exception.php';
require 'C:\xampp\htdocs\Ktr2\vendor\phpmailer\phpmailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\Ktr2\vendor\phpmailer\phpmailer\src\SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) { // Sửa lại "email" chữ thường
    $email = $_POST["email"];
    
    // Kiểm tra email có tồn tại trong hệ thống không
    $stmt = $conn->prepare("SELECT tk.MaTK, cn.MaKH FROM thongtintaikhoan tk JOIN thongtincanhan cn ON tk.MaTK = cn.MaKH WHERE cn.Email = ?");
    
    if ($stmt === false) {
        die("Lỗi khi chuẩn bị truy vấn SQL: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $MaTK = $row['MaTK'];

        // Tạo mã xác nhận và liên kết đặt lại mật khẩu
        $token = bin2hex(random_bytes(16));
        $resetLink = "http://yourwebsite.com/datlaimk.php?token=" . $token;

        // Lưu token vào bảng datlaimk
        $stmt = $conn->prepare("INSERT INTO datlaimk (MaTK, Token, TgTao, TgHetHan, Trangthai) VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 1 HOUR), 0)");
        
        if ($stmt === false) {
            die("Lỗi khi chuẩn bị truy vấn SQL: " . $conn->error);
        }

        $stmt->bind_param("is", $MaTK, $token);

        if ($stmt->execute()) {
            // Gửi email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'nguyenminh26721@gmail.com';
                $mail->Password = 'app-specific-password';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->SMTPDebug = 0;
            
                $mail->setFrom('nguyenminh26721@gmail.com');
                $mail->addAddress($email);
            
                $mail->isHTML(true);
                $mail->Subject = 'Đặt lại mật khẩu của bạn';
                $mail->Body    = "Click vào link sau để đặt lại mật khẩu của bạn: <a href='$resetLink'>$resetLink</a>";
                
                $mail->send();
                echo "<p style='color: green;'>Mã đã được gửi tới email của bạn!</p>";
            } catch (Exception $e) {
                echo "<p style='color: red;'>Không thể gửi email. Vui lòng thử lại sau. Lỗi: {$mail->ErrorInfo}</p>";
            }
            
        } else {
            die("Lỗi khi thực hiện truy vấn SQL: " . $stmt->error);
        }
    } else {
        echo "<p style='color: red;'>Email không tồn tại trong hệ thống!</p>";
    }
}
?>

</body>
</html>
