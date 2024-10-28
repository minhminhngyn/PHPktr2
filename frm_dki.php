<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h3 {
            text-align: center;
            color: #333;
            font-size: 24px; 
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        table {
            width: 100%;
            margin-bottom: 20px;
            table-layout: fixed; 
        }

        td {
            padding: 10px;
            vertical-align: middle;
            width: 50%; 
        }

        input[type='text'], 
        input[type='date'], 
        input[type='email'], 
        input[type='password'] {
            width: 100%; 
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            min-width: 200px; 
        }

        input[type='submit'], 
        #reset_value {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            margin: 0 10px; 
        }

        input[type='submit']:hover, 
        #reset_value:hover {
            background-color: #0056b3;
        }

        #strengthBar {
            display: flex;
            width: 50%; 
            height: 10px;
            border: 1px solid #ccc; 
            border-radius: 5px;
            margin: 5px auto 0; 
        }

        .strengthSegment {
            flex: 1; 
            height: 100%;
            transition: background-color 0.3s; 
        }

        .strengthText {
            display: flex;
            justify-content: space-between; 
            margin-top: 2px; 
            font-size: 12px; 
            padding: 0 100px;
        }

        .weak { color: red; }
        .medium { color: orange; }
        .strong { color: green; }

        .toggle-password {
            position: absolute;
            right: 10px; 
            top: 50%; 
            transform: translateY(-50%);
        }

        .toggle-password img {
            width: 25px; 
            height: 25px; 
        }
    </style>
</head>
<body>
    <div>
        <h3>Đăng ký tài khoản</h3>
    </div>
    <form action='xl_dki.php' method='POST'>
        <div class='cus_infor'>
            <h4>Thông tin người đăng ký</h4>
            <table>
                <tr>
                    <td>Họ tên:</td>
                    <td><input type='text' name='name' maxlength="300" required></td>
                </tr>
                <tr>
                    <td>Ngày sinh:</td>
                    <td><input type='date' name='birthdate' required></td>
                </tr>
                <tr>
                    <td>Địa chỉ:</td>
                    <td><input type='text' name='address' required></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><input type='email' name='email' required></td>
                </tr>
                <tr>
                    <td>Số điện thoại:</td>
                    <td><input type='text' name='phonenumber' maxlength='10' required></td>
                </tr>
            </table>
        </div>
        <div class='account_infor'>
            <h4>Thông tin tài khoản</h4>
            <table>
                <tr>
                    <td>Tên đăng nhập:</td>
                    <td><input type='text' name='username' maxlength="300" required></td>
                </tr>
                <tr>
                    <td>Mật khẩu:</td>
                    <td>
                        <div style="position: relative;">
                            <input type='password' name='password' class='password' required>
                            <span class="toggle-password" onclick="togglePasswordVisibility('password')">
                                <img src="eye-off-icon.png" alt="Show Password" id="eyeIconPassword" style="cursor: pointer;">
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="strengthBar">
                            <div class="strengthSegment" id="segment1"></div>
                            <div class="strengthSegment" id="segment2"></div>
                            <div class="strengthSegment" id="segment3"></div>
                            <div class="strengthSegment" id="segment4"></div>
                            <div class="strengthSegment" id="segment5"></div>
                        </div>
                        <div class="strengthText">
                            <span class="weak">Mật khẩu yếu</span>
                            <span class="strong">Mật khẩu mạnh</span>
                        </div>
                        <p id='strengthMessage'></p>
                    </td>
                </tr>
                <tr>
                    <td>Xác nhận mật khẩu:</td>
                    <td>
                        <div style="position: relative;">
                            <input type='password' name='password_confirm' class='password' required>
                            <span class="toggle-password" onclick="togglePasswordVisibility('password_confirm')">
                                <img src="eye-off-icon.png" alt="Show Password" id="eyeIconConfirmPassword" style="cursor: pointer;">
                            </span>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div style="text-align: center;"> 
            <button type='button' id='reset_value'>Làm lại</button>
            <input type='submit' value='Đăng ký'>
        </div>
    </form>
    <script src="frm_dki.js"></script>
</body>
</html>
