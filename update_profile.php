<?php
session_start();
include 'db.php'; // Koneksi PDO di $conn

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = (int)$_SESSION['id'];

// Ambil data dari form
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? '';
$password = $_POST['password'] ?? '';

try {
    if (!empty($password)) {
        // Update dengan password baru, hash dulu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET name = :name, email = :email, role = :role, password = :password WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':password', $hashed_password);
    } else {
        // Update tanpa password
        $sql = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
        $stmt = $conn->prepare($sql);
    }

    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Setelah update, redirect ke profile.html
    header("Location: profile.php");
    exit();

} catch (PDOException $e) {
    echo "Gagal update profil: " . $e->getMessage();
}
