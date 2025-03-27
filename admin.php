<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT nhanvien.Ma_NV, nhanvien.Ten_NV, nhanvien.Phai, nhanvien.Noi_Sinh, phongban.Ten_Phong, nhanvien.Luong 
                        FROM nhanvien 
                        JOIN phongban ON nhanvien.Ma_Phong = phongban.Ma_Phong");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Nhân Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        .logout {
            display: inline-block;
            padding: 10px 15px;
            color: white;
            background: red;
            text-decoration: none;
            border-radius: 5px;
        }
        .logout:hover {
            background: darkred;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background: #007BFF;
            color: white;
        }
        tr:nth-child(even) {
            background: #f9f9f9;
        }
        tr:hover {
            background: #f1f1f1;
        }
        img {
            width: 30px;
            vertical-align: middle;
        }
        .action-links a {
            margin: 0 5px;
            text-decoration: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .edit {
            background: #28a745;
        }
        .delete {
            background: #dc3545;
        }
        .edit:hover {
            background: #218838;
        }
        .delete:hover {
            background: #c82333;
        }
        .add-button {
    display: inline-block;
    padding: 10px 15px;
    margin-bottom: 15px;
    background: #28a745;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: bold;
}
.add-button:hover {
    background: #218838;
}

    </style>
</head>
<body>

<div class="container">
    <h2>Danh sách Nhân Viên</h2>
    
    <p>Xin chào, <b><?= $_SESSION['user']['username'] ?></b> |
       <a href="logout.php" class="logout">Đăng xuất</a>
    </p>

    <?php if ($_SESSION['user']['role'] == 'admin') : ?>
        <a href="add.php" class="add-button">+ Thêm Nhân Viên</a>
    <?php endif; ?>

    <table>
        <tr>
            <th>Mã Nhân Viên</th>
            <th>Tên Nhân Viên</th>
            <th>Giới tính</th>
            <th>Nơi Sinh</th>
            <th>Phòng Ban</th>
            <th>Lương</th>
            <th>Hành động</th>
        </tr>
        
        <?php while ($row = $result->fetch_assoc()) { 
            $genderIcon = ($row['Phai'] == 'NU') ? 'asset/woman.gif' : 'asset/man.gif';
        ?>
        <tr>
            <td><?= htmlspecialchars($row['Ma_NV']) ?></td>
            <td><?= htmlspecialchars($row['Ten_NV']) ?></td>
            <td>
                <img src="<?= $genderIcon ?>" alt="<?= $row['Phai'] ?>">  
            </td>
            <td><?= htmlspecialchars($row['Noi_Sinh']) ?></td>
            <td><?= htmlspecialchars($row['Ten_Phong']) ?></td>
            <td><?= number_format($row['Luong']) ?> VND</td>
            <td class="action-links">
                <a href="edit.php?Ma_NV=<?= urlencode($row['Ma_NV']) ?>" class="edit">Sửa</a> 
                <a href="delete.php?Ma_NV=<?= urlencode($row['Ma_NV']) ?>" class="delete" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>


</body>
</html>
