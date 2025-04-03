<?php
session_start();
include 'db_connect.php';

// Lấy danh sách ngành học từ database
$sqlNganh = "SELECT MaNganh, TenNganh FROM NganhHoc";
$resultNganh = mysqli_query($conn, $sqlNganh);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_sinhvien'])) {
    $maSV = $_POST['MaSV'];
    $hoTen = $_POST['HoTen'];
    $gioiTinh = $_POST['GioiTinh'];
    $ngaySinh = $_POST['NgaySinh'];
    $maNganh = $_POST['MaNganh'];
    $password = password_hash($_POST['Password'], PASSWORD_DEFAULT);

    // Xử lý upload ảnh
    $targetDir = "uploads/";
    $fileName = basename($_FILES["Hinh"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Chỉ chấp nhận file ảnh
    $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array($fileType, $allowTypes)) {
        if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $targetFilePath)) {
            // Lưu thông tin vào database
            $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh, Password) 
                    VALUES ('$maSV', '$hoTen', '$gioiTinh', '$ngaySinh', '$targetFilePath', '$maNganh', '$password')";
            
            if (mysqli_query($conn, $sql)) {
                header('Location: index.php');
                exit;
            } else {
                echo "Lỗi khi thêm sinh viên!";
            }
        } else {
            echo "Lỗi khi tải ảnh lên!";
        }
    } else {
        echo "Chỉ chấp nhận file ảnh (JPG, JPEG, PNG, GIF).";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh Viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Thêm Sinh Viên</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Mã Sinh Viên</label>
                <input type="text" class="form-control" name="MaSV" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Họ Tên</label>
                <input type="text" class="form-control" name="HoTen" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Giới Tính</label>
                <select class="form-control" name="GioiTinh" required>
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" name="NgaySinh" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Ngành Học</label>
                <select class="form-control" name="MaNganh" required>
                    <option value="">-- Chọn Ngành --</option>
                    <?php while ($row = mysqli_fetch_assoc($resultNganh)): ?>
                        <option value="<?= $row['MaNganh'] ?>"><?= $row['TenNganh'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" class="form-control" name="Password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Chọn ảnh</label>
                <input type="file" class="form-control" name="Hinh" required>
            </div>
            <button type="submit" name="add_sinhvien" class="btn btn-success">Thêm</button>
        </form>
    </div>
</body>
</html>
