<?php
session_start();
include 'connect.inp'; 
date_default_timezone_set('Asia/Bangkok'); //Do múi giờ trong PHP đang lệch so với Hà Nội

// Hàm gửi email cảnh báo khi có nhiều lần đăng nhập sai
function sendVerificationEmail($email, $username, $token) {
    require("vendor/phpmailer/phpmailer/src/PHPMailer.php");
    require("vendor/phpmailer/phpmailer/src/SMTP.php");
    require("vendor/phpmailer/phpmailer/src/Exception.php");
    $mail = new phpmailer\phpmailer\phpmailer(true);
    try {
        $mail->SMTPDebug = 0; 
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nguyenngoc51203@gmail.com';
        $mail->Password = 'qnkx oika zgkm ycyq';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom($mail->Username);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = "Xác nhận đăng nhập từ hệ thống";
        $verificationLink = "http://localhost/dangnhapmoi/confirm_login.php?user=" . urlencode($username) . "&token=" . $token;
        $mail->Body = "Chúng tôi đã phát hiện nhiều lần đăng nhập sai vào tài khoản của bạn. Vui lòng xác nhận qua link này để tiếp tục đăng nhập: <a href='$verificationLink'>$verificationLink</a>";

        if (!$mail->send()) {
            die("Gửi email thất bại. Lỗi: {$mail->ErrorInfo}");
        }
    } catch (Exception $e) {
        die("Gửi email thất bại. Lỗi ngoại lệ: " . $e->getMessage());
    }    
}

// Xử lý đăng xuất và xóa Tokenluudangnhap
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    if (isset($_COOKIE['remember_me'])) {
        $token = $_COOKIE['remember_me'];
        $stmt = $con->prepare("UPDATE thongtintaikhoan SET Tokenluudangnhap = NULL, Ngayhethantoken = NULL WHERE Tokenluudangnhap = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->close();

        // Xóa cookie "remember_me" trên trình duyệt
        setcookie('remember_me', '', time() - 3600, '/', '', true, true);
    }

    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Kiểm tra cookie "remember_me" và tự động đăng nhập nếu có
if (isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];
    $stmt = $con->prepare("SELECT MaTK, PhanQuyen FROM thongtintaikhoan WHERE Tokenluudangnhap = ? AND Ngayhethantoken > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($maTK, $vaitro);
        $stmt->fetch();
        
        $_SESSION['user_id'] = $maTK; 

        // Điều hướng dựa trên vai trò
        if ($vaitro === 'admin') {
            header("Location: qluserview.php");  
        } else {
            header("Location: welcome.php");  
        }
        exit;
    }
    $stmt->close();
}

// Xử lý đăng nhập khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha = $_POST['captcha'];
    $remember = isset($_POST['remember']);

    // Kiểm tra mã captcha
    if ($captcha !== $_SESSION['captcha_code']) {
        echo '<script>
                alert("Mã captcha không đúng. Vui lòng thử lại.");
                window.location.href = "index.php";
            </script>';
                exit();
    }

    // Truy vấn thông tin tài khoản
    $stmt = $con->prepare("SELECT thongtintaikhoan.MaTK, thongtintaikhoan.MatKhau, thongtintaikhoan.TrangThaiHoatDong, 
                                  thongtintaikhoan.Solansaidangnhap, thongtintaikhoan.Lanchothulai, 
                                  thongtincanhan.Email, thongtintaikhoan.PhanQuyen
                           FROM thongtintaikhoan 
                           JOIN thongtincanhan ON thongtintaikhoan.MaTK = thongtincanhan.MaKH 
                           WHERE thongtintaikhoan.TenDangNhap = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($maTK, $hashedPassword, $trangthaiTK, $solansaidangnhap, $lanchothulai, $email, $vaitro);
        $stmt->fetch();

        // Kiểm tra trạng thái tài khoản
        if ($trangthaiTK == 0) {
            $timeLocked = strtotime($lanchothulai);
            if (time() <= $timeLocked) { 
                echo '<script>
                alert("Tài khoản của bạn đang bị khóa. Vui lòng thử lại sau.");
                window.location.href = "index.php";
            </script>';
                exit();
            } else {
                // Mở khóa tài khoản
                $stmt = $con->prepare("UPDATE thongtintaikhoan SET TrangThaiHoatDong = 1, Solansaidangnhap = 0, Lanchothulai = NULL WHERE MaTK = ?");
                $stmt->bind_param("i", $maTK);
                $stmt->execute();
            }
        }

        // Kiểm tra mật khẩu
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $maTK;
            $_SESSION['vaitro'] = $vaitro;
        
            // Xóa các thông tin liên quan đến tài khoản khi đăng nhập thành công
            $stmt = $con->prepare("
                UPDATE thongtintaikhoan 
                SET 
                    Lanchothulai = NULL,
                    Tokenxacnhan = NULL,
                    Ngayhethanxacnhan = NULL,
                    Solansaidangnhap = 0
                WHERE MaTK = ?
            ");
            $stmt->bind_param("i", $maTK);
            $stmt->execute();
        
            // Lưu cookie nếu người dùng chọn "remember me"
            if ($remember) {
                $token = bin2hex(random_bytes(16));
                $expire = time() + (30 * 24 * 60 * 60);
                setcookie('remember_me', $token, $expire, '/', '', true, true);
        
                // Cập nhật token vào cơ sở dữ liệu
                $stmt = $con->prepare("UPDATE thongtintaikhoan SET Tokenluudangnhap = ?, Ngayhethantoken = ? WHERE MaTK = ?");
                $stmt->bind_param("ssi", $token, date('Y-m-d H:i:s', $expire), $maTK);
                $stmt->execute();
            }
        
            if ($vaitro === 'admin') {
                header("Location: qluserview.php");  
            } else {
                header("Location: welcome.php");  
            }
            exit;
        }
         else {
            $solansaidangnhap++;

            // Cập nhật số lần đăng nhập sai vào CSDL
            $stmt = $con->prepare("UPDATE thongtintaikhoan SET Solansaidangnhap = ? WHERE MaTK = ?");
            $stmt->bind_param("ii", $solansaidangnhap, $maTK);
            $stmt->execute();

            if ($solansaidangnhap == 1 || $solansaidangnhap == 2) {
                echo '<script>
                alert("Sai tên đăng nhập hoặc mật khẩu.");
                window.location.href = "index.php";
            </script>';
                exit();
            } elseif ($solansaidangnhap == 3) {
                echo "Sai 3 lần. Vui lòng chờ 30 giây trước khi thử lại.";
                
                // Thêm bộ đếm thời gian
                echo '<div id="timer">Thời gian còn lại: <span id="countdown">30</span> giây</div>';

                // Gọi JavaScript cho đếm ngược
                echo '<script>
                function startCountdown(seconds) {
                    let countdownElement = document.getElementById("countdown");
                    let countdownContainer = document.getElementById("timer");
                    let remainingTime = seconds;

                    countdownContainer.style.display = "block"; 
                    countdownElement.textContent = remainingTime;

                    const countdownInterval = setInterval(() => {
                        remainingTime--;
                        countdownElement.textContent = remainingTime;

                        if (remainingTime <= 0) {
                            clearInterval(countdownInterval);
                            // Tự động chuyển hướng về index.php khi đếm ngược kết thúc
                            window.location.href = "index.php";
                        }
                    }, 1000);
                }

                window.onload = function() {
                    startCountdown(30); 
                };
            </script>';
            
                // Lưu thời gian khóa
                $currentTime = date('Y-m-d H:i:s'); 
                $lanchothulai = date('Y-m-d H:i:s', strtotime($currentTime . ' +30 seconds'));
                $stmt = $con->prepare("UPDATE thongtintaikhoan SET Lanchothulai = ? WHERE MaTK = ?");
                $stmt->bind_param("si", $lanchothulai, $maTK);
                $stmt->execute();

            } elseif ($solansaidangnhap == 4) {
                echo "Sai 4 lần. Vui lòng chờ 60 giây trước khi thử lại.";
                
                // Thêm bộ đếm thời gian
                echo '<div id="timer">Thời gian còn lại: <span id="countdown">60</span> giây</div>';

                // Gọi JavaScript cho đếm ngược
                echo '<script>
                function startCountdown(seconds) {
                    let countdownElement = document.getElementById("countdown");
                    let countdownContainer = document.getElementById("timer");
                    let remainingTime = seconds;

                    countdownContainer.style.display = "block"; 
                    countdownElement.textContent = remainingTime;

                    const countdownInterval = setInterval(() => {
                        remainingTime--;
                        countdownElement.textContent = remainingTime;

                        if (remainingTime <= 0) {
                            clearInterval(countdownInterval);
                            // Tự động chuyển hướng về index.php khi đếm ngược kết thúc
                            window.location.href = "index.php";
                        }
                    }, 1000);
                }

                window.onload = function() {
                    startCountdown(60); 
                };
            </script>';
            
                // Lưu thời gian khóa
                $currentTime = date('Y-m-d H:i:s'); 
                $lanchothulai = date('Y-m-d H:i:s', strtotime($currentTime . ' +60 seconds'));
                $stmt = $con->prepare("UPDATE thongtintaikhoan SET Lanchothulai = ? WHERE MaTK = ?");
                $stmt->bind_param("si", $lanchothulai, $maTK);
                $stmt->execute();
            
            } elseif ($solansaidangnhap >= 5) {
                // Khóa tài khoản nếu nhập sai 5 lần
                $stmt = $con->prepare("UPDATE thongtintaikhoan SET TrangThaiHoatDong = 0, Lanchothulai = ? WHERE MaTK = ?");
                $lanchothulai = date('Y-m-d H:i:s', strtotime('+60 minutes')); // Thời gian khóa tài khoản 60 phút
                $stmt->bind_param("si", $lanchothulai, $maTK);
                $stmt->execute();

                // Gửi email thông báo cho người dùng
                $token = bin2hex(random_bytes(16));
                $stmt = $con->prepare("UPDATE thongtintaikhoan SET Tokenxacnhan = ?, Ngayhethanxacnhan = DATE_ADD(NOW(), INTERVAL 60 MINUTE) WHERE MaTK = ?");
                $stmt->bind_param("si", $token, $maTK);
                $stmt->execute();
                sendVerificationEmail($email, $username, $token);

                echo '<script>
                alert("Tài khoản bị tạm khóa trong 60 phút do phát hiện nhiều lần đăng nhập sai. Vui lòng kiểm tra email để biết thêm chi tiết.");
                window.location.href = "index.php";
            </script>';
                exit();
            }
             else {
                echo '<script>
                alert("Sai tên đăng nhập hoặc mật khẩu.");
                window.location.href = "index.php";
            </script>';
                exit();
            }
        }
    } else {
        echo '<script>
                alert("Sai tên đăng nhập hoặc mật khẩu.");
                window.location.href = "index.php";
            </script>';
                exit();
    }
    $stmt->close();
}
?>
