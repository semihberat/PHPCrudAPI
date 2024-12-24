<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_management";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $stmt = $conn->prepare("INSERT INTO users (name, email, age) VALUES (:name, :email, :age)");
        $stmt->execute(['name' => $name, 'email' => $email, 'age' => $age]);
        echo "<script>alert('Kullanıcı başarıyla eklendi.');</script>";
    }

    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $stmt = $conn->prepare("UPDATE users SET name = :name, email = :email, age = :age WHERE id = :id");
        $stmt->execute(['name' => $name, 'email' => $email, 'age' => $age, 'id' => $id]);
        echo "<script>alert('Kullanıcı başarıyla güncellendi.');</script>";
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        echo "<script>alert('Kullanıcı başarıyla silindi.');</script>";
    }

    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Yönetim Sistemi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        form {
            margin-bottom: 20px;
        }
        button {
            padding: 8px 16px;
            margin: 5px 0;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Kullanıcı Yönetim Sistemi</h1>

    <!-- Kullanıcı Ekleme Formu -->
    <h2>Yeni Kullanıcı Ekle</h2>
    <form method="POST" action="">
        <input type="hidden" name="action" value="add">
        <label for="name">İsim:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="age">Yaş:</label>
        <input type="number" id="age" name="age" required>
        <button type="submit">Ekle</button>
    </form>

    <!-- Kullanıcı Güncelleme Formu -->
    <?php if (isset($_GET['edit'])): ?>
        <?php
        $id = $_GET['edit'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
        <h2>Kullanıcı Güncelle</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <label for="name">İsim:</label>
            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            <label for="age">Yaş:</label>
            <input type="number" id="age" name="age" value="<?php echo $user['age']; ?>" required>
            <button type="submit">Güncelle</button>
        </form>
    <?php endif; ?>

    <!-- Kullanıcı Listesi -->
    <h2>Kullanıcı Listesi</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>İsim</th>
                <th>Email</th>
                <th>Yaş</th>
                <th>Oluşturulma Tarihi</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo $user['name']; ?></td>
                    <td><?php echo $user['email']; ?></td>
                    <td><?php echo $user['age']; ?></td>
                    <td><?php echo $user['created_at']; ?></td>
                    <td>
                        <a href="?edit=<?php echo $user['id']; ?>">Güncelle</a> |
                        <a href="?delete=<?php echo $user['id']; ?>" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">Sil</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
