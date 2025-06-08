<?php
session_start();
include 'db.php'; // pastikan db.php ini sudah setup koneksi PDO di $conn

// Cek apakah user sudah login
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = (int)$_SESSION['id'];

// Ambil data user dari database pakai PDO
try {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User tidak ditemukan.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error saat mengambil data user: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Saya</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
    .btn-save {
      background-color: #004d40;
      color: white;
      /* Tambahkan properti di bawah ini untuk menonaktifkan efek hover */
      transition: none; /* Menghilangkan transisi jika ada */
    }

    .btn-save:hover {
      background-color: #004d40; /* Pertahankan warna latar belakang yang sama */
      color: white; /* Pertahankan warna teks yang sama */
      border-color: #004d40; /* Pertahankan warna border yang sama (jika ada) */
    }
    </style>
</head>
<body class="container mt-5">
    <h2>Profil Saya</h2>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Profil berhasil diperbarui.</div>
    <?php endif; ?>

    <form method="POST" action="update_profile.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

        <div class="mb-3">
            <label class="form-label">Nama:</label>
            <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email:</label>
            <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role:</label>
            <input type="text" class="form-control" name="role" value="<?= htmlspecialchars($user['role']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password Baru (kosongkan jika tidak ingin ubah):</label>
            <input type="password" class="form-control" name="password">
        </div>

        <button type="submit" class="btn btn-save">Simpan Perubahan</button>
        <!-- <button type="submit" class="btn btn-save" onclick="window.location.href='profile.html';"> Simpan Perubahan</button> -->
    </form>
</body>
</html>