<?php
require_once '../db.php';

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}

// Memeriksa apakah pengguna memiliki peran 'Customer'
if ($_SESSION['role'] !== 'Customer') {
    header("Location: ../logout.php");
    exit;
}

// --- PERBAIKAN: Mengambil data workspace yang aktif dari database ---
try {
    // Query ini hanya akan mengambil workspace dengan status 'Aktif'
    $sql = "SELECT * FROM workspaces WHERE status = 'Aktif' ORDER BY id DESC LIMIT 4";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $workspaces = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $workspaces = [];
    // Anda bisa menambahkan pesan error di sini jika diperlukan
    // $error_message = "Gagal memuat data workspace: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GottaWork - Coworking Space</title>
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Lora', serif;
    }
    .bg-custom-gray {
      background-color: #095151;
    }
  </style>
</head>
<body class="bg-custom-gray text-white">
  <?php
      $active_page = 'Home';
      require_once '../includes/header_customer.php';
  ?>

  <section class="px-6 py-16 max-w-7xl mx-auto">
    <p class="text-yellow-400 uppercase text-sm tracking-widest">The Features of Office</p>
    <h1 class="text-5xl font-bold mt-4 leading-tight">We’re the Perfect <br />Working Space for You</h1>
    <p class="mt-4 max-w-2xl text-white/80">Comfortable work, maintained focus, increased productivity? It's all at GottaWork. Find your best space now!</p>
    
  </section>

  <section class="bg-white text-gray-800 py-20">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10">
      <div>
        <p class="text-orange-500 uppercase text-sm font-semibold mb-2">Features</p>
        <h2 class="text-4xl font-bold mb-4">We Offer Creative Environment</h2>
        <p class="text-gray-600 mb-8">Amet diam lorem dui ornare libero magnis erat blandit massa semper egestas sed ultrices enim lectus magna.</p>
        <img src="../assets/background.png" class="rounded-lg shadow-lg">
      </div>
  
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white border shadow p-6 rounded-lg">
          <div class="w-4 h-4 bg-teal-900 mb-4"></div>
          <h3 class="text-xl font-bold mb-2">Office Space</h3>
          <p class="text-gray-500 mb-4 text-sm">Amet diam lorem ornare libero magnis erat blandit massa lorem ornare libero magnis.</p>
          <ul class="space-y-2 text-sm text-gray-600 mb-4">
            <li>✔ Flexible contracts</li>
            <li>✔ Access your office 24/7/365</li>
            <li>✔ No down payment</li>
            <li>✔ All inclusive</li>
            <li>✔ Cancel anytime</li>
            <li>✔ High-speed internet access</li>
          </ul>
          <a href="ketersediaanws.php" class="bg-yellow-400 text-sm text-gray-900 font-semibold px-4 py-2 rounded hover:bg-yellow-300">Learn more ➤</a>
        </div>
  
        <div class="bg-white border shadow p-6 rounded-lg">
          <div class="w-4 h-4 bg-teal-900 mb-4"></div>
          <h3 class="text-xl font-bold mb-2">Dedicated Desk</h3>
          <p class="text-gray-500 mb-4 text-sm">Amet diam lorem ornare libero magnis erat blandit massa lorem ornare libero magnis.</p>
          <ul class="space-y-2 text-sm text-gray-600 mb-4">
            <li>✔ Flexible contracts</li>
            <li>✔ Access your office 24/7/365</li>
            <li>✔ No down payment</li>
            <li>✔ All inclusive</li>
            <li>✔ Cancel anytime</li>
            <li>✔ High-speed internet access</li>
          </ul>
           <a href="ketersediaanws.php" class="bg-yellow-400 text-sm text-gray-900 font-semibold px-4 py-2 rounded hover:bg-yellow-300">Learn more ➤</a>
        </div>
  
        <div class="bg-white border shadow p-6 rounded-lg">
          <div class="w-4 h-4 bg-teal-900 mb-4"></div>
          <h3 class="text-xl font-bold mb-2">Virtual Office</h3>
          <p class="text-gray-500 mb-4 text-sm">Amet diam lorem ornare libero magnis erat blandit massa lorem ornare libero magnis.</p>
          <ul class="space-y-2 text-sm text-gray-600 mb-4">
            <li>✔ Flexible contracts</li>
            <li>✔ Access your office 24/7/365</li>
            <li>✔ No down payment</li>
            <li>✔ All inclusive</li>
            <li>✔ Cancel anytime</li>
            <li>✔ High-speed internet access</li>
          </ul>
          <a href="ketersediaanws.php" class="bg-yellow-400 text-sm text-gray-900 font-semibold px-4 py-2 rounded hover:bg-yellow-300">Learn more ➤</a>
        </div>
  
        <div class="bg-white border shadow p-6 rounded-lg">
          <div class="w-4 h-4 bg-teal-900 mb-4"></div>
          <h3 class="text-xl font-bold mb-2">Meeting Rooms</h3>
          <p class="text-gray-500 mb-4 text-sm">Amet diam lorem ornare libero magnis erat blandit massa lorem ornare libero magnis.</p>
          <ul class="space-y-2 text-sm text-gray-600 mb-4">
            <li>✔ Flexible contracts</li>
            <li>✔ Access your office 24/7/365</li>
            <li>✔ No down payment</li>
            <li>✔ All inclusive</li>
            <li>✔ Cancel anytime</li>
            <li>✔ High-speed internet access</li>
          </ul>
           <a href="ketersediaanws.php" class="bg-yellow-400 text-sm text-gray-900 font-semibold px-4 py-2 rounded hover:bg-yellow-300">Learn more ➤</a>
        </div>
      </div>
    </div>
  </section>
  
  <section class="bg-white text-gray-800 py-20">
    <div class="max-w-7xl mx-auto px-6">
      <div class="mb-12">
        <p class="text-teal-600 uppercase text-sm font-semibold mb-1 tracking-wide">Locations</p>
        <h2 class="text-4xl font-bold">Explore Available Office Spaces</h2>
        <div class="text-gray-600 text-sm max-w-md mt-4">
          Daily/monthly desk, chair and room facilities with fast internet and an atmosphere that motivates productivity.
          <div class="mt-3">
            <a href="ketersediaanws.php" class="inline-block border border-gray-800 px-4 py-2 text-sm font-medium hover:bg-gray-100 rounded">View all →</a>
          </div>
        </div>
      </div>
  
      <!-- --- PERBAIKAN: Menampilkan workspace dari database --- -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <?php if (empty($workspaces)): ?>
            <p class="col-span-full text-center text-gray-500">Tidak ada workspace yang tersedia saat ini.</p>
        <?php else: ?>
            <?php foreach ($workspaces as $ws): ?>
            <div class="bg-white border rounded-lg shadow hover:shadow-md transition">
              <img src="../<?= htmlspecialchars($ws['image_path']) ?>" class="rounded-t-lg h-48 w-full object-cover" alt="<?= htmlspecialchars($ws['name']) ?>">
              <div class="p-5">
                <h3 class="text-lg font-semibold mb-1"><?= htmlspecialchars($ws['name']) ?></h3>
                <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars($ws['description']) ?></p>
                <p class="text-sm text-gray-500 mb-2">📍 <?= htmlspecialchars($ws['location']) ?></p>
                <p class="text-sm text-orange-600 font-semibold mb-4">
                    Rp <?= number_format($ws['price'], 0, ',', '.') ?>/<?= htmlspecialchars($ws['duration_unit']) ?>
                </p>
                <a href="bookingdate.php?id=<?= $ws['id'] ?>" class="inline-block bg-yellow-400 text-gray-900 text-sm font-semibold px-4 py-2 rounded hover:bg-yellow-300">Book now →</a>
              </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="bg-gray-50 py-20">
    <div class="max-w-6xl mx-auto px-6 text-center">
  
      <p class="uppercase text-orange-400 text-sm font-semibold tracking-wide mb-3">Testimonials</p>
      <h2 class="text-3xl md:text-black font-bold mb-4">What Our Customer Says</h2>
      <p class="text-gray-600 mb-12">
        Comfortable work, maintained focus, increased productivity? It's all at GottaWork. Find your best space now!
      </p>
  
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="bg-white rounded-xl shadow p-6">
          <div class="text-orange-500 text-3xl mb-3">“</div>
          <p class="text-gray-600 mb-3 text-sm">
            Comfortable work, maintained focus, increased productivity? It's all at GottaWork. Find your best space now!
          </p>
          <p class="font-semibold text-black mt-4">John William</p>
          <p class="text-gray-500 text-sm">Manager at Furniti</p>
        </div>
  
        <div class="bg-[#003D36] text-white rounded-xl shadow p-6 scale-105">
          <div class="text-3xl mb-3">“</div>
          <p class="mb-3 text-sm">
            Comfortable work, maintained focus, increased productivity? It's all at GottaWork. Find your best space now!
          </p>
          <p class="font-semibold mt-4">John William</p>
          <p class="text-white/70 text-sm">Manager at Furniti</p>
        </div>
  
        <div class="bg-white rounded-xl shadow p-6">
          <div class="text-orange-500 text-3xl mb-3">“</div>
          <p class="text-gray-600 mb-3 text-sm">
            Comfortable work, maintained focus, increased productivity? It's all at GottaWork. Find your best space now!
          </p>
          <p class="font-semibold text-black mt-4">John William</p>
          <p class="text-gray-500 text-sm">Manager at Furniti</p>
        </div>
      </div>
    </div>
  </section>
  
  
  <?php
  require_once '../includes/footer.php';
  ?>

</body>
</html>
