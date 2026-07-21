<?php
session_start();

// Hủy toàn bộ biến session
$_SESSION = array();

// Hủy Session trên server
session_destroy();

// Điều hướng về trang login
header('Location: login.php');
exit();
?>