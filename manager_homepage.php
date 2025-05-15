<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("login.php");
    exit;
}

// Check if user has admin role
if ($_SESSION['role'] !== 'Administrator') {
    header("manager_homepage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manager Homepage</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="text-gray-800">

<!-- HEADER -->
<header class="bg-[#3e3e3e] text-white">
  <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
    <h1 class="text-xl font-bold">GottaWork</h1>
    <nav class="space-x-6 text-sm hidden md:flex">
      <a href="#" class="text-orange-300">Home</a>
      <a href="pendatapan.html">Keuangan</a>
      <a href="#">Skema Tarif</a>
      <a href="#" class="border border-white px-3 py-1 rounded hover:bg-white hover:text-black">Log Out ›</a>
    </nav>
  </div>

  <!-- HERO -->
  <div class="px-6 pb-16 pt-12 max-w-7xl mx-auto">
    <p class="text-sm text-yellow-300 tracking-wide mb-2 uppercase">— Memimpin dengan data, bertumbuh dengan tujuan</p>
    <h2 class="text-4xl font-bold leading-tight mb-4">Wawasan Anda Menunjukkan Jalan</h2>
    <p class="text-gray-300 mb-6">Bersama mewujudkan ruang kerja efisien melalui pengelolaan yang cermat untuk menghadirkan nilai bisnis terbaik.</p>
    <div class="space-x-4">
      <button class="bg-yellow-400 text-black font-semibold px-4 py-2 rounded">Keuangan</button>
      <button class="bg-yellow-400 text-black font-semibold px-4 py-2 rounded">Skema Tarif</button>
    </div>
  </div>
</header>

<!-- SECTION: KEUANGAN -->
<section class="bg-teal-900 text-white">
  <div class="max-w-7xl mx-auto px-6 py-12">
    <p class="text-sm text-yellow-300 mb-2 uppercase">— Keuangan</p>
    <h2 class="text-3xl font-semibold mb-2">Keuangan</h2>
    <p class="text-sm text-white/70">Home › Keuangan</p>
  </div>
</section>

<section class="bg-white py-10">
  <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-6">
    <?php
    $keuangan = [
      ["title" => "Pendapatan", "desc" => "Pantau pemasukan", "img" => "img/pendapatan.jpg"],
      ["title" => "Pengeluaran", "desc" => "Pantau pengeluaran", "img" => "img/pengeluaran.jpg"],
      ["title" => "Statistik", "desc" => "Pantau statistik perusahaan", "img" => "img/statistik.jpg"],
    ];
    foreach ($keuangan as $item):
    ?>
    <div class="border rounded shadow overflow-hidden bg-white">
      <img src="<?= $item['img'] ?>" alt="<?= $item['title'] ?>" class="w-full h-48 object-cover">
      <div class="p-4">
        <h3 class="font-semibold text-lg mb-1"><?= $item['title'] ?></h3>
        <p class="text-sm text-gray-600 mb-3"><?= $item['desc'] ?></p>
        <button class="bg-yellow-400 text-black px-4 py-1 rounded text-sm">Masuk ›</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- SECTION: SKEMA TARIF -->
<section class="bg-teal-900 text-white mt-16">
  <div class="max-w-7xl mx-auto px-6 py-12">
    <p class="text-sm text-yellow-300 mb-2 uppercase">— Skema Tarif</p>
    <h2 class="text-3xl font-semibold mb-2">Skema Tarif</h2>
    <p class="text-sm text-white/70">Home › Skema Tarif</p>
  </div>
</section>

<section class="bg-white py-10">
  <div class="max-w-7xl mx-auto px-6 flex justify-center">
    <?php
    $skema = [
      ["title" => "Harga", "desc" => "Harga Workspace", "img" => "img/harga.jpg"],
    ];
    foreach ($skema as $item):
    ?>
    <div class="w-80 border rounded shadow overflow-hidden bg-white">
      <img src="<?= $item['img'] ?>" alt="<?= $item['title'] ?>" class="w-full h-48 object-cover">
      <div class="p-4">
        <h3 class="font-semibold text-lg mb-1"><?= $item['title'] ?></h3>
        <p class="text-sm text-gray-600 mb-3"><?= $item['desc'] ?></p>
        <button class="bg-yellow-400 text-black px-4 py-1 rounded text-sm">Masuk ›</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>


<!-- FOOTER -->
<footer class="bg-gray-900 text-white mt-16 text-sm">
  <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 px-6 py-10">
    <div>
      <h3 class="font-bold text-lg mb-2">GottaWork</h3>
      <p>768 Market Street Bandar Lampung, Indonesia</p>
      <p class="mt-2">📞 (+62) 123 456 789</p>
      <p>✉️ customer@zottawork.com</p>
      <div class="flex space-x-2 mt-2">
        <a href="#">🌐</a><a href="#">📸</a><a href="#">🐦</a>
      </div>
    </div>
    <div>
      <h4 class="font-bold">Company</h4>
      <ul><li>Meeting Room</li><li>Individual Desk</li><li>Group Desk</li><li>Private Office</li></ul>
    </div>
    <div>
      <h4 class="font-bold">Locations</h4>
      <ul><li>Mall Boemi Kedaton</li><li>Lampung City Mall</li></ul>
    </div>
    <div>
      <h4 class="font-bold">Support</h4>
      <ul><li>Book a Tour</li><li>Inquire</li><li>FAQ</li><li>Terms of Use</li></ul>
    </div>
  </div>
  <div class="text-center text-gray-500 pb-6">&copy; 2025 GottaWork. Powered by GW</div>
</footer>

</body>
</html>
