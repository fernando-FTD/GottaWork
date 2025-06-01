<?php
// Konfigurasi koneksi database
$host = "localhost";
$user = "root";
$password = ""; // Sesuaikan
$database = "database_gottawork"; // Ganti sesuai nama database kamu

$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari POST request
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$startDate = $_POST['startDate'] ?? '';
$endDate = $_POST['endDate'] ?? '';
$startTime = $_POST['startTime'] ?? '';
$endTime = $_POST['endTime'] ?? '';
$workspace = $_POST['workspace'] ?? '';
$deskNumber = $_POST['deskNumber'] ?? '';

// Validasi sederhana
if (empty($name) || empty($email) || empty($startDate) || empty($endDate)) {
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap."]);
    exit;
}

// Simpan ke database
$stmt = $conn->prepare("INSERT INTO bookings (name, email, start_date, end_date, start_time, end_time, workspace, desk_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $name, $email, $startDate, $endDate, $startTime, $endTime, $workspace, $deskNumber);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Booking berhasil disimpan."]);
} else {
    echo json_encode(["status" => "error", "message" => "Gagal menyimpan booking."]);
}

$stmt->close();
$conn->close();
?>
