<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM nhanvien");
$totalRow = $totalQuery->fetch_assoc();
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

$stmt = $conn->prepare("SELECT nhanvien.Ma_NV, nhanvien.Ten_NV, nhanvien.Phai, nhanvien.Noi_Sinh, phongban.Ten_Phong, nhanvien.Luong 
                        FROM nhanvien 
                        JOIN phongban ON nhanvien.Ma_Phong = phongban.Ma_Phong
                        LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
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
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 2px;
            border: 1px solid #007BFF;
            color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
        }
        .pagination a.active {
            background-color: #007BFF;
            color: white;
        }
        .pagination a:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Danh sách Nhân Viên</h2>
    
    <p>Xin chào, <b><?= htmlspecialchars($_SESSION['user']['username']) ?></b> |
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
                <img src="<?= $genderIcon ?>" alt="<?= htmlspecialchars($row['Phai']) ?>">  
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

    <!-- Phân trang -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">« Trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Tiếp »</a>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
