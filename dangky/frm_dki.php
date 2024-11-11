<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <style>
        .weak { color: red; }
        .medium { color: orange; }
        .strong { color: green; }
    </style>
</head>
<body>
    
    <div>
        <h3> Đăng ký tài khoản </h3>
    </div>
    <form action='xl_dki.php'  method='POST'>
        <div class='cus_infor'>
            <h4> Thông tin người đăng ký</h4>
            <table>
                <tr>
                    <td> Họ tên: </td>
                    <td> <input type='text' name='name' value='' maxlength="300"></td>
                </tr>
                <tr>
                    <td> Ngày sinh: </td>
                    <td> <input type='date' name='birthdate' value='' ></td>
                </tr>
                <tr>
                    <td> Địa chỉ: </td>
                    <td> <input type='text' name='address' value='' ></td>
                </tr>
                <tr>
                    <td> Email: </td>
                    <td> <input type='email' name='email' value='' ></td>
                </tr>
                <tr>
                    <td> Số điện thoại: </td>
                    <td> <input type='text' name='phonenumber' value='' maxlength='10'></td>
                </tr>
            </table>
        </div>
        <div class='account_infor'>
            <h4> Thông tin tài khoản</h4>
            <table>
                <tr>
                    <td> Tên đăng nhập: </td>
                    <td> <input type='text' name='username' value='' maxlength="300" ></td>
                </tr>
                <tr>
                    <td> Mật khẩu: </td>
                    <td> <input type='password' name='password' class='password' value=''></td>
                    <td> <button type='button' name='view_password' class='view_password'>Hiển thị</button></td>
                </tr>
                <tr>
                    <td><p id='strengthMessage'></p></td>
                </tr>
                <tr>
                    <td> Xác nhận mật khẩu: </td>
                    <td> <input type='password' name='password_confirm' class='password' value='' ></td>
                    <td> <button type='button' name='view_password' class='view_password'>Hiển thị</button></td>
                </tr>
            </table>
        </div>
        <button type='button' id='reset_value'>Làm lại</button>
        <input type='submit' value='Đăng ký'>
    </form>
    <script src="../helpers/js/frm_dki.js"></script> 

    
</body>
</html>