<?php
$conn = new mysqli("localhost", "root", "", "database_gottawork");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM workspaces";
$result = $conn->query($sql);

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
        <a href="membatalkanreservasi.html" class="text-white hover:text-orange-400 font-medium">Reservation</a>
        <a href="profile.php" class="text-white hover:text-orange-400 font-medium">Profile</a>
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

  <section class="max-w-7xl mx-auto px-6 py-12">
    <h4 class="text-green-600 font-semibold uppercase text-sm mb-2">—Locations</h4>
    <h2 class="text-4xl font-bold mb-4">Explore Available Office Spaces</h2>
    <p class="text-gray-500 mb-10">Daily/monthly desk, chair and room facilities with fast internet and an atmosphere that motivates productivity.</p>

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

   <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16 mt-16">
      <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
          <!-- Company Info -->
          <div class="col-span-1">
            <h2 class="text-2xl font-bold mb-6">GottaWork</h2>
            <p class="text-gray-400 mb-4">7101 Market Street Bandung, Indonesia</p>
            <p class="text-gray-400 mb-2"><i class="fas fa-phone mr-2"></i> (+62) 123 456 789</p>
            <p class="text-gray-400 mb-6"><i class="fas fa-envelope mr-2"></i> customer@gottawork.com</p>
            <div class="flex space-x-4">
              <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
              <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
              <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-youtube"></i></a>
            </div>
          </div>
          
          <!-- Company Links -->
          <div class="col-span-1">
            <h3 class="text-lg font-semibold mb-4">Company</h3>
            <ul class="space-y-2">
              <li><a href="#" class="text-gray-400 hover:text-white">Meeting Room</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">Individual Desk</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">Group Desk</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">Private Office</a></li>
            </ul>
          </div>
          
          <!-- Locations Links -->
          <div class="col-span-1">
            <h3 class="text-lg font-semibold mb-4">Locations</h3>
            <ul class="space-y-2">
              <li><a href="#" class="text-gray-400 hover:text-white">Mall Bintaro Xchange</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">Lampung City Mall</a></li>
            </ul>
          </div>
          
          <!-- Partnerships Links -->
          <div class="col-span-1">
            <h3 class="text-lg font-semibold mb-4">Partnerships</h3>
            <ul class="space-y-2">
              <li><a href="#" class="text-gray-400 hover:text-white">Event Venues</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">Brokers</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">Community Events</a></li>
            </ul>
          </div>
          
          <!-- Support/Spaces Links -->
          <div class="col-span-1">
            <h3 class="text-lg font-semibold mb-4">Spaces</h3>
            <ul class="space-y-2">
              <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">About</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">Jobs</a></li>
              <li><a href="#" class="text-gray-400 hover:text-white">Franchise</a></li>
            </ul>
          </div>
        </div>
        
        <!-- Copyright -->
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
          © 2025 GottaWork. Powered by GW
        </div>
      </div>
    </footer>
</body>
</html>

<?php
$conn->close();
?>
