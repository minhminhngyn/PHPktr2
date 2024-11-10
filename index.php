<?php
session_start(); // Khởi động session
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="login.css"> 
</head>
<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Tên Đăng Nhập:</label>
                <input type="text" id="username" name="username" required 
                        value="<?php echo isset($_SESSION['remembered_username']) ? htmlspecialchars($_SESSION['remembered_username']) : ''; ?>">

            </div>
            <div class="form-group">
                <label for="password">Mật Khẩu:</label>
                <input type="password" id="password" name="password" required>
                <button type="button" onclick="togglePassword()">Hiện</button>
            </div>
            <div class="form-group">
                <label for="captcha">Mã Captcha:</label>
                <input type="text" id="captcha" name="captcha" required>
                <img src="generate_captcha.php" alt="Captcha" class="captcha-image">
                <button type="button" onclick="reloadCaptcha()">Tạo lại Captcha</button>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="remember">
                    Lưu Mật Khẩu
                </label>
            </div>
            <button type="submit">Đăng Nhập</button>
            <div class="extra-links">
                <a href="frm_dki.php">Bạn chưa có tài khoản?</a> 
                <a href="Quenmatkhau.php">Quên mật khẩu</a>
            </div>
        </form>
    </div>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
        }
        
        function reloadCaptcha() {
            document.querySelector('.captcha-image').src = 'generate_captcha.php?' + Date.now();
        }
    </script>
</body>
</html>
