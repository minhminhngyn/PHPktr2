<?php

    //require '../vendor/autoload.php';
    require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
    require '../vendor/phpmailer/phpmailer/src/Exception.php';
    require '../vendor/phpmailer/phpmailer/src/SMTP.php';
    $mail = new PHPMailer\PHPMailer\PHPMailer(true); // Bắt đầu sử dụng PHPMailer
    try {
        // Server settings
        $mail->SMTPDebug=2;
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
            $user='nguyenngoc51203@gmail.com';
            $pass='qnkx oika zgkm ycyq';
            //$username="Hoai Ngoc";
        $mail->Username = $user;
        $mail->Password = $pass;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Người gửi và người nhận
        $mail->setFrom($user);
        $to="hoaingoc51203@gmail.com";
        //$toname="Nguyen Ngoc";
        $mail->addAddress($to);

        // Nội dung email
        $mail->isHTML(true);
        $mail->Subject = 'Xác nhận tài khoản của bạn nhé';
        //$activationLink = "http://yourwebsite.com/activate.php?code=" . $activationCode;
        $noidung="Nhấp vào liên kết sau để kích hoạt tài khoản của bạn";
        $mail->Body = $noidung;

        $mail->send();
        echo 'Email kích hoạt đã được gửi';
    } catch (Exception $e) {
        echo "Không thể gửi email. Chi tiết lỗi: {$mail->ErrorInfo}";
    }
?>