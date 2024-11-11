<?php
    include('../helpers/others/connect.inp');
    echo "xử lý email<br>";
    if(isset($_GET['token']))
    {
        $token=$_GET['token'];
        $sql="SELECT * FROM thongtintaikhoan WHERE TokenEmail='{$token}' and TrangThaiXacThuc='0';";
        $result=$con->query($sql);

        if ($result->num_rows > 0) 
        {
            $sql_update_tk="UPDATE thongtintaikhoan SET TrangThaiXacThuc = '1' WHERE TokenEmail='{$token}'";
            $result=$con->query($sql_update_tk);
            $sql_update_xacthuc="UPDATE thongtintaikhoan SET TrangThaiHoatDong = '1' WHERE TokenEmail='{$token}'";
            $result=$con->query($sql_update_xacthuc);
            echo "Tài khoản của bạn đã được kích hoạt thành công! 
                Bạn có thể <a href='../dangnhap/dangnhap.php'>đăng nhập</a>.";
        }
    }
    else 
    {
        echo "Không tìm thấy token.";
    }
?>