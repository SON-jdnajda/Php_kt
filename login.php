<?php
session_start();
include 'db_connect.php';  // Kết nối cơ sở dữ liệu

// Đăng nhập
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $maSV = $_POST['MaSV'];
    $password = $_POST['Password'];

    // Sử dụng prepared statement để tránh SQL Injection
    $stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ?");
    $stmt->bind_param("s", $maSV);  // 's' cho kiểu dữ liệu string
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Kiểm tra thông tin đăng nhập
    if ($user && $password === $user['Password']) {
        // Lưu thông tin người dùng vào session
        $_SESSION['user'] = $maSV;
        
        // Kiểm tra quyền của người dùng
        if ($user['Role'] === 'admin') {
            // Nếu là admin, chuyển hướng đến trang index
            header('Location: index.php');
        } else {
            // Nếu là user, chuyển hướng đến dashboard
            header('Location: dashboard.php');
        }
        exit;
    } else {
        $error_message = "Sai thông tin đăng nhập!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Test1</a>
    </nav>

    <div class="container mt-4">
        <h2>Đăng Nhập</h2>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="MaSV" class="form-label">Mã Sinh Viên</label>
                <input type="text" class="form-control" name="MaSV" required>
            </div>
            <div class="mb-3">
                <label for="Password" class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" name="Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Đăng Nhập</button>
        </form>
    </div>
</body>
</html>
