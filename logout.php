<?php
session_start();
$_SESSION = array(); // Xóa tất cả các biến session
session_destroy(); // Hủy session
header("Location: login.php"); // Chuyển hướng về trang đăng nhập
exit();
?>