<?php
session_start();
include 'db_connect.php';

if (isset($_GET['MaSV'])) {
    $maSV = $_GET['MaSV'];
    $result = mysqli_query($conn, "SELECT * FROM SinhVien WHERE MaSV = '$maSV'");
    $sv = mysqli_fetch_assoc($result);
}

// Lấy danh sách ngành học từ database
$nganhQuery = mysqli_query($conn, "SELECT * FROM NganhHoc");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_sinhvien'])) {
    $maSV = $_POST['MaSV'];
    $hoTen = $_POST['HoTen'];
    $gioiTinh = $_POST['GioiTinh'];
    $ngaySinh = $_POST['NgaySinh'];
    $maNganh = $_POST['MaNganh'];
    
    // Xử lý upload ảnh
    $hinh = $sv['Hinh']; // Giữ ảnh cũ nếu không upload ảnh mới
    if (!empty($_FILES["Hinh"]["name"])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($_FILES["Hinh"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Kiểm tra loại file
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileType, $allowTypes)) {
            if (move_uploaded_file($_FILES["Hinh"]["tmp_name"], $targetFilePath)) {
                $hinh = $targetFilePath;
            } else {
                echo "Lỗi khi tải ảnh lên!";
                exit;
            }
        } else {
            echo "Chỉ chấp nhận file ảnh (JPG, JPEG, PNG, GIF).";
            exit;
        }
    }

    // Cập nhật sinh viên
    $sql = "UPDATE SinhVien SET HoTen='$hoTen', GioiTinh='$gioiTinh', NgaySinh='$ngaySinh', Hinh='$hinh', MaNganh='$maNganh' WHERE MaSV='$maSV'";
    
    if (mysqli_query($conn, $sql)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Lỗi khi cập nhật sinh viên!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sinh Viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2>Sửa Sinh Viên</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="MaSV" value="<?= $sv['MaSV'] ?>">

            <div class="mb-3">
                <label class="form-label">Họ Tên</label>
                <input type="text" class="form-control" name="HoTen" value="<?= $sv['HoTen'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Giới Tính</label>
                <select class="form-control" name="GioiTinh" required>
                    <option value="Nam" <?= $sv['GioiTinh'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                    <option value="Nữ" <?= $sv['GioiTinh'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Ngày Sinh</label>
                <input type="date" class="form-control" name="NgaySinh" value="<?= $sv['NgaySinh'] ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Chọn ảnh mới (nếu có)</label>
                <input type="file" class="form-control" name="Hinh">
                <p>Ảnh hiện tại:</p>
                <img src="<?= $sv['Hinh'] ?>" width="100">
            </div>

            <div class="mb-3">
                <label class="form-label">Ngành Học</label>
                <select class="form-control" name="MaNganh" required>
                    <?php while ($row = mysqli_fetch_assoc($nganhQuery)): ?>
                        <option value="<?= $row['MaNganh'] ?>" <?= $row['MaNganh'] == $sv['MaNganh'] ? 'selected' : '' ?>>
                            <?= $row['TenNganh'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <button type="submit" name="edit_sinhvien" class="btn btn-warning">Sửa Sinh Viên</button>
        </form>
    </div>
</body>
</html>
