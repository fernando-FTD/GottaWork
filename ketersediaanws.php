<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "database_gottawork");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data workspaces
$sql = "SELECT * FROM workspaces";
$result = $conn->query($sql);

// Simpan data ke array agar bisa digunakan 2 kali
$workspaces = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $workspaces[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Explore Workspaces</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Lora', serif;
    }
    .bg-custom-gray {
      background-color: #095151;
    }
  </style>
</head>
<body class="bg-white text-gray-900 font-[Lora]">
  <!-- Header -->
  <header class="bg-custom-gray text-white">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
      <div class="text-2xl font-bold text-white">GottaWork</div>
      <nav class="flex items-center space-x-6">
        <a href="homepage.php" class="text-white hover:text-orange-400 font-medium">Home</a>
        <a href="ketersediaanws.php" class="text-orange-400 font-medium underline-offset-4">Locations</a>
        <a href="pembayaran.html" class="text-white hover:text-orange-400 font-medium">Payment</a>
        <a href="membatalkanreservasi.html" class="text-white hover:text-orange-400 font-medium">Reservation</a>
        <a href="#" class="text-white hover:text-orange-400 font-medium">Profile</a>
        <a href="bookingdate.html" class="ml-4 border border-white text-white px-4 py-2 rounded hover:bg-white hover:text-gray-800 transition">Book a Space ➤</a>
      </nav>
    </div>
    <div class="max-w-7xl mx-auto px-6 py-12 flex justify-between items-start">
      <div>
        <p class="text-yellow-400 uppercase tracking-wider text-sm mb-2">Book a Space</p>
        <h1 class="text-4xl font-bold">Book a Space</h1>
      </div>
      <div class="text-sm text-white mt-2 flex items-center space-x-2">
        <span>Home</span>
        <span>➤</span>
        <span class="font-medium">Book a Space</span>
      </div>
    </div>
  </header>

  <!-- Konten utama -->
  <section class="max-w-7xl mx-auto px-6 py-12">
    <h4 class="text-green-600 font-semibold uppercase text-sm mb-2">—Locations</h4>
    <h2 class="text-4xl font-bold mb-4">Explore Available Office Spaces</h2>
    <p class="text-gray-500 mb-10">Fasilitas meja, kursi, dan ruang harian/bulanan dengan internet cepat dan suasana yang memotivasi produktivitas.</p>

    <!-- Grid workspace baris pertama -->
    <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($workspaces as $row): ?>
        <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
          <img src="<?= htmlspecialchars($row['image_path']) ?>" class="w-full h-48 object-cover" />
          <div class="p-4 space-y-2">
            <h3 class="font-semibold text-lg"><?= htmlspecialchars($row['name']) ?></h3>
            <p class="text-sm text-gray-600"><?= htmlspecialchars($row['description']) ?></p>
            <div class="flex items-center text-sm text-gray-500">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C8.13401 2 5 5.13401 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.86599-3.13401-7-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="9" r="2.5" fill="currentColor" />
              </svg>
              <?= htmlspecialchars($row['location']) ?>
            </div>
            <div class="flex justify-between items-center mt-2">
              <span class="text-red-500 font-semibold">
                Rp <?= number_format($row['price'], 0, ',', '.') ?>
                <span class="text-sm text-gray-500">/<?= $row['duration_unit'] ?></span>
              </span>
              <a href="bookingdate.html" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-1 rounded text-sm font-medium inline-block text-center">Book now →</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Grid workspace baris kedua -->
    <div class="grid gap-8 mt-12 md:grid-cols-2 lg:grid-cols-4">
      <?php foreach ($workspaces as $row): ?>
        <div class="bg-white border rounded-lg shadow-sm overflow-hidden">
          <img src="<?= htmlspecialchars($row['image_path']) ?>" class="w-full h-48 object-cover" />
          <div class="p-4 space-y-2">
            <h3 class="font-semibold text-lg"><?= htmlspecialchars($row['name']) ?></h3>
            <p class="text-sm text-gray-600"><?= htmlspecialchars($row['description']) ?></p>
            <div class="flex items-center text-sm text-gray-500">
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C8.13401 2 5 5.13401 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.86599-3.13401-7-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="12" cy="9" r="2.5" fill="currentColor" />
              </svg>
              <?= htmlspecialchars($row['location']) ?>
            </div>
            <div class="flex justify-between items-center mt-2">
              <span class="text-red-500 font-semibold">
                Rp <?= number_format($row['price'], 0, ',', '.') ?>
                <span class="text-sm text-gray-500">/<?= $row['duration_unit'] ?></span>
              </span>
              <a href="bookingdate.html" class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-1 rounded text-sm font-medium inline-block text-center">Book now →</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>
</body>
</html>

<?php
$conn->close();
?>
