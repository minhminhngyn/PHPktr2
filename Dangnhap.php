<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #fff;
            color: #000;
        }
        h2 {
            color: #000;
        }
        input[type="text"], input[type="password"], button {
            width: 90%;
            max-width: 500px;
            padding: 13px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #000;
            font-size: 16px;
        }
        button {
            width: 80%;
            max-width: 250px;
            padding: 13px;
            background-color: #000;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<h2>Đăng Nhập</h2>
<form action="login_process.php" method="post">
        <input type="text" name="username" placeholder="Tên đăng nhập" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
        <input type="password" name="password" placeholder="Mật khẩu" value="<?php echo isset($_GET['password']) ? htmlspecialchars($_GET['password']) : ''; ?>">
        <button type="submit">Đăng Nhập</button>
    </form>

</body>
</html>
