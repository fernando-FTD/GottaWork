<?php
// db_harga.php — dipanggil oleh AJAX

// Koneksi database
$koneksi = new mysqli("localhost", "root", "", "database_gottawork");
if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

// Validasi dan ambil data dari POST
$title = isset($_POST['title']) ? $koneksi->real_escape_string($_POST['title']) : '';
$newPrice = isset($_POST['price']) ? $koneksi->real_escape_string($_POST['price']) : '';

if ($title && $newPrice) {
  $stmt = $koneksi->prepare("UPDATE harga SET price = ? WHERE title = ?");
  $stmt->bind_param("ss", $newPrice, $title);
  $stmt->execute();

  echo ($stmt->affected_rows > 0) ? "success" : "failed";

  $stmt->close();
} else {
  echo "invalid";
}

$koneksi->close();
