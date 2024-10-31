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
    <form action="xlQuenmatkhau.php" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;" onsubmit="return validateForm()">
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
</body>
</html>
