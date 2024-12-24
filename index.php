<?php
// Veritabanı bağlantısı
$host = 'localhost';      // Veritabanı sunucusu (XAMPP için genellikle localhost)
$dbname = 'user_management'; // Veritabanınızın adı
$username = 'root';       // XAMPP varsayılan kullanıcı adı
$password = '';           // XAMPP'de varsayılan şifre (boş)

try {
    // PDO ile veritabanına bağlantı
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Kullanıcıları çekme
    $stmt = $conn->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Yeni kullanıcı ekleme
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $stmt = $conn->prepare("INSERT INTO users (name, email, age) VALUES (:name, :email, :age)");
        $stmt->execute(['name' => $name, 'email' => $email, 'age' => $age]);

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Kullanıcı güncelleme
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $age = $_POST['age'];

        $stmt = $conn->prepare("UPDATE users SET name = :name, email = :email, age = :age WHERE id = :id");
        $stmt->execute(['name' => $name, 'email' => $email, 'age' => $age, 'id' => $id]);

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }

    // Kullanıcı silme
    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];

        $stmt = $conn->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Yönetim Sistemi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h1 class="text-center mb-4">Kullanıcı Yönetim Sistemi</h1>

        <!-- Yeni Kullanıcı Ekleme Formu -->
        <div class="card mb-4">
            <div class="card-header">Yeni Kullanıcı Ekle</div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="add">
                    <div class="mb-3">
                        <label for="name" class="form-label">İsim</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="age" class="form-label">Yaş</label>
                        <input type="number" id="age" name="age" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ekle</button>
                </form>
            </div>
        </div>

        <!-- Kullanıcı Güncelleme Formu -->
        <?php if (isset($_GET['edit'])): ?>
            <?php
            $id = $_GET['edit'];
            $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <div class="card mb-4">
                <div class="card-header">Kullanıcı Güncelle</div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label">İsim</label>
                            <input type="text" id="name" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="age" class="form-label">Yaş</label>
                            <input type="number" id="age" name="age" class="form-control" value="<?php echo $user['age']; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success">Güncelle</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Kullanıcı Listesi -->
        <div class="card">
            <div class="card-header">Kullanıcı Listesi</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
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
                                    <a href="?edit=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">Güncelle</a>
                                    <a href="?delete=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bu kullanıcıyı silmek istediğinize emin misiniz?')">Sil</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
