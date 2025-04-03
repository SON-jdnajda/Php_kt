<?php
session_start();
include 'db_connect.php';

// Kiểm tra nếu người dùng chưa đăng nhập thì chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Kiểm tra nếu có mã học phần được truyền vào để xóa
if (isset($_GET['MaHP'])) {
    $maHP = $_GET['MaHP'];
    $maSV = $_SESSION['user'];  // Lấy mã sinh viên từ session

    // Xóa học phần khỏi bảng ChiTietDangKy
    $stmt = $conn->prepare("DELETE FROM ChiTietDangKy WHERE MaHP = ? AND MaDK IN (SELECT MaDK FROM DangKy WHERE MaSV = ?)");
    $stmt->bind_param("ss", $maHP, $maSV);
    $stmt->execute();
}

// Quay lại trang giỏ hàng
header('Location: cart.php');
exit;
