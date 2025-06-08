<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "database_gottawork";
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}

// Proses hapus data jika ada request POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
  $id = intval($_POST['id']);
  $conn->query("DELETE FROM bookings WHERE id = $id");
  exit;
}

// Ambil reservasi terakhir
$reservasi = $conn->query("SELECT * FROM bookings ORDER BY id DESC LIMIT 1");
$data = $reservasi->fetch_assoc();

// Cek apakah data tersedia
if (!$data) {
  echo "<p class='text-gray-600'>Belum ada reservasi yang tercatat.</p>";
  exit;
}
?>

<div class="bg-gray-100 rounded-lg overflow-hidden">
  <div class="w-full h-48 overflow-hidden">
    <img src="assets/image.jpg" alt="Office space"
         class="w-full h-full object-cover"
         onerror="this.src='https://via.placeholder.com/400x200?text=Office+Space'" />
  </div>

  <div class="p-4">
    <h3 class="font-semibold"><?= htmlspecialchars($data['workspace']) ?></h3>
    
    <button 
      data-id="<?= $data['id'] ?>" 
      class="cancelBtn bg-red-500 text-white text-sm px-4 py-2 rounded">
      Batalkan Reservasi
    </button>
  </div>
</div>
