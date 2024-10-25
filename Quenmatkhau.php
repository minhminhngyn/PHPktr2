<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f3f3f3;
        }
        h2 {
            color: #333;
        }
        input[type="email"],
        button {
            width: 90%;
            max-width: 500px;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        p {
            max-width: 500px;
            width: 90%;
            text-align: center;
        }
    </style>
</head>
<body>

<h2>Quên Mật Khẩu</h2>
<form action="" method="POST" style="width: 100%; display: flex; flex-direction: column; align-items: center;">
    <input type="email" name="email" placeholder="Nhập email của bạn" required>
    <button type="submit" name="submit">Gửi Mã</button>
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    
    // Kiểm tra tính hợp lệ của email (có thể kết nối với cơ sở dữ liệu hoặc API ở đây)
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: green;'>Mã đã được gửi tới email của bạn!</p>";
    } else {
        echo "<p style='color: red;'>Email không hợp lệ!</p>";
    }
}
?>

</body>
</html>
