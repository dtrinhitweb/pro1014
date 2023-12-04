<?php 
    function product_search($keyword, $page=1){
        $batdau= ($page-1)*12;
        // 1 trang lay 8

        // trang 1 bat dau tu 0 1 2 3 4 5 6 7 

        // trang 2 bat dau tu 8

        // trang 3 bat dau tu 16

        // trang n bat dau tu (n-1)*8
        
        return pdo_query("SELECT * FROM sanpham sp INNER JOIN danhmuc dm ON sp.MaDM = dm.MaDM WHERE TenSP LIKE '%$keyword%' LIMIT $batdau,12");
    }
    function product_searchTotal($keyword){
        return pdo_query_value("SELECT COUNT(*) FROM sanpham WHERE TenSP LIKE '%$keyword%'");
    }
    function product_shop($start, $limit) {
        return pdo_query("SELECT * FROM sanpham sp INNER JOIN danhmuc dm ON sp.MaDM = dm.MaDM ORDER BY sp.SoLuong DESC LIMIT $start, $limit");
    }
    function count_product(){
        return pdo_query_value("SELECT count(*) AS soluong FROM sanpham");
    }
    function product_getNew($limit){
        return pdo_query("SELECT * FROM sanpham sp INNER JOIN danhmuc dm ON sp.MaDM=dm.MaDM ORDER BY MaSP DESC LIMIT $limit");
    }
    function product_getPin($limit){
        return pdo_query("SELECT * FROM sanpham sp INNER JOIN danhmuc dm ON sp.MaDM=dm.MaDM WHERE ghim = 1 LIMIT $limit");
    }

    // tìm kiếm sản phẩm
    function search_live_product($keyword) {
        return pdo_query("SELECT * FROM sanpham WHERE TenSP like '%$keyword%' LIMIT 3");
    }

    function product_detail($id){
        return pdo_query_one("SELECT * FROM sanpham s INNER JOIN danhmuc dm ON s.MaDM = dm.MaDM WHERE s.MaSP = $id");
    }

    // Mô tả sản phẩm
    function addComment($MaTK,$MaSP,$NoiDung,$SoSao){
        pdo_execute("INSERT INTO binhluan(`MaTK`,`MaSP`,`NoiDung`,`SoSao`) VALUES (?,?,?,?)",$MaTK,$MaSP,$NoiDung,$SoSao);
    }
    function comment_getByProduct($MaSP){
        return pdo_query("SELECT HoTen, HinhAnh, NgayBL, SoSao, NoiDung FROM taikhoan tk INNER JOIN binhluan bl ON tk.MaTK=bl.MaTK INNER JOIN sanpham sp ON bl.MaSP=sp.MaSP WHERE bl.MaSP=?",$MaSP);
    }
    function count_comment($MaSP){
        return pdo_query_value("SELECT count(*) AS SLBinhLuan FROM sanpham sp INNER JOIN binhluan bl ON sp.MaSP=bl.MaSP WHERE bl.MaSP=?",$MaSP);
    }
    function check_comment($MaTK,$MaSP){
        return pdo_query_value("SELECT COUNT(SoLuongSP) FROM taikhoan tk INNER JOIN hoadon hd ON tk.MaTK=hd.MaTK INNER JOIN chitiethoadon cthd ON hd.MaHD=cthd.MaHD WHERE hd.TrangThai='hoan-tat' and tk.MaTK=? and cthd.MaSP=?",$MaTK,$MaSP);
    }
    function show_comment(){
        return pdo_query ("SELECT
                                sp.`MaSP` AS MaSP,
                                sp.`TenSP` AS TenSP,
                                sp.`AnhSP` AS AnhSP,
                                MAX(bl.`NgayBL`) AS BLMoi,
                                MIN(bl.`NgayBL`) AS BLCu,
                                COUNT(bl.`MaBL`) AS SoLuongBinhLuan
                            FROM
                                `sanpham` sp
                            LEFT JOIN
                                `binhluan` bl ON sp.`MaSP` = bl.`MaSP`
                            GROUP BY
                                sp.`MaSP`, sp.`TenSP`, sp.`AnhSP`
                            HAVING
                                SoLuongBinhLuan > 0
                            ORDER BY
                                SoLuongBinhLuan DESC;
                    ");
    }
    function get_tenSP($MaSP){
        return pdo_query_value("SELECT TenSP FROM sanpham WHERE MaSP = ?",$MaSP);
    }
    function chitiet_comment($MaSP){
        return pdo_query("SELECT
                            bl.`MaBL`,
                            bl.`NoiDung`,
                            bl.`NgayBL`,
                            sp.`TenSP`,
                            sp.`MaSP`,
                            tk.`HoTen`
                        FROM
                            `binhluan` bl
                        JOIN
                            `sanpham` sp ON bl.`MaSP` = sp.`MaSP`
                        JOIN
                            `taikhoan` tk ON bl.`MaTK` = tk.`MaTK`
                        WHERE
                            sp.`MaSP` = ?", $MaSP);
    }
    function delete_comment($MaBL){
        pdo_execute("DELETE FROM binhluan WHERE MaBL = $MaBL");
    }
    // Sản phẩm tương tự
    function product_same($MaSP,$id){
        return pdo_query("SELECT * FROM sanpham WHERE MaSP!=? AND MaDM =? ORDER BY rand() LIMIT 5",$MaSP,$id );
    }
    function ratings_trungbinh($MaSP){
        return pdo_query_one("SELECT SUM(SoSao) as SoSao, COUNT(bl.MaSP) as SoBinhLuan FROM sanpham sp INNER JOIN binhluan bl ON sp.MaSP=bl.MaSP WHERE bl.MaSP=?;",$MaSP);
    }
    function product_tangView($MaSP){
        pdo_execute("UPDATE sanpham SET LuotXem=LuotXem+1 WHERE MaSP=? ",$MaSP);
    }
?>