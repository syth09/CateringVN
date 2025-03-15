<?php
session_start(); // Bắt đầu session

// Xóa tất cả dữ liệu session
session_unset();
session_destroy();

// Chuyển hướng về trang chủ
header("Location: ../frontend/index.html");
exit();
