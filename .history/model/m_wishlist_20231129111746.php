<?php

    include_once 'm_pdo.php';

    // Kiểm tra xem có danh sách yêu thích hay chwua
    function check_wishByProductAndUser($MaTK,$MaSP){
        return pdo_query_one("SELECT MaSP FROM yeuthich WHERE MaTK=? AND MaSP=?",$MaTK,$MaSP);
    }
?>