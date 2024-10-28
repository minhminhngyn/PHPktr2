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
</head>
<body>
    <h2>Quên Mật Khẩu</h2>
    <form action="Quenmatkhau.php" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;" onsubmit="return validateForm()">
        <input type="email" id="email" name="email" placeholder="Nhập email của bạn" required>
        <button type="submit" name="submit">Gửi Mã</button>
        <p>Đã có tài khoản? <a href="login.html" style="color: #000; text-decoration: none;">Đăng nhập</a></p>
    </form>

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
            return true;
        }
    </script>

    <?php
    include 'connect.php';

    // Import thư viện PHPMailer
    require("vendor/phpmailer/phpmailer/src/PHPMailer.php");
    require("vendor/phpmailer/phpmailer/src/SMTP.php");
    require("vendor/phpmailer/phpmailer/src/Exception.php");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['submit'])) {
        $userEmail = $_POST['email'];

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
            $mail->addAddress($userEmail); // Sử dụng email người dùng nhập từ form

            // Nội dung email
            $mail->isHTML(true);
            $mail->Subject = "Quên mật khẩu";
            $mail->Body = "
                <h3>Xin chào,</h3>
                <p>Bạn đã yêu cầu đặt lại mật khẩu. Nhấp vào nút bên dưới để tiếp tục:</p>
                <a href='http://localhost/Ktr2/Datlaimk.php' style='display: inline-block; padding: 10px 20px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px; font-weight: bold;'>Đặt lại mật khẩu</a>
                <p>Nếu bạn không yêu cầu điều này, vui lòng bỏ qua email này.</p>
                <p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
            ";

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
            echo "<script>alert('Email đã được gửi thành công đến $userEmail');</script>";
        } catch (Exception $e) {
            echo "<script>alert('Không thể gửi email. Lỗi: " . $mail->ErrorInfo . "');</script>";
        }
    }
    ?>

</body>
</html>
