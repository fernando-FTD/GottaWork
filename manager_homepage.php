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

<header class="bg-gray-700 text-white py-6 px-6">
  <div class="hero-section relative">
    <!-- Navigation -->
    <nav class="absolute w-full py-6 px-8 flex justify-between items-center z-10">
      <a href="#" class="text-white text-3xl font-bold">GottaWork</a>
      
      <div class="flex items-center space-x-8">
        <a href="#" class="text-yellow-400 hover:text-yellow-500 transition-colors">
          Home
        </a>
        <a href="pendapatan.html" class="text-white hover:text-yellow-400 transition-colors">
          Keuangan
        </a>
        <a href="#" class="text-white hover:text-yellow-400 transition-colors">
          Skema Tarif
        </a>
        
        <a href="#" class="border border-white text-white px-6 py-2 rounded-md flex items-center justify-center hover:bg-white hover:bg-opacity-10 transition-colors">
          Log Out ›
        </a>
      </div>
    </nav>
    
    <!-- Page Title -->
    <div class="container mx-auto px-8 pt-32">
      <div class="flex items-center text-yellow-400 mb-2">
        <i class="fas fa-chevron-down mr-1 text-xs"></i>
        <span class="ml-1 text-sm uppercase">— Memimpin dengan data, bertumbuh dengan tujuan</span>
      </div>
      <h1 class="text-3xl font-bold mb-6 text-white">Wawasan Anda Menunjukkan Jalan</h1>
      
      <div class="flex justify-between items-center">
        <div class="flex space-x-4">
          <!-- Tombol Keuangan dengan style yang diminta -->
          <a href="pendapatan.html" class="bg-yellow-400 text-black px-6 py-3 rounded font-medium flex items-center">
            Keuangan
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a>
          
          <!-- Tombol Skema Tarif dengan style yang diminta -->
          <a href="#" class="border border-yellow-400 text-yellow-400 px-6 py-3 rounded font-medium flex items-center">
            Skema Tarif
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>
        
        <div class="flex items-center text-sm text-white">
          <span>Home</span>
          <i class="fas fa-chevron-right mx-1 text-xs"></i>
          <span>Keuangan</span>
        </div>
      </div>
      
      <p class="text-gray-300 mt-6 max-w-2xl">Bersama mewujudkan ruang kerja efisien melalui pengelolaan yang cermat untuk menghadirkan nilai bisnis terbaik.</p>
    </div>
  </div>
</header>

<!-- SECTION: KEUANGAN -->
<section class="bg-teal-900 text-white">
  <div class="max-w-7xl mx-auto px-6 py-12">
    <p class="text-sm text-yellow-300 mb-2 uppercase">— Keuangan</p>
    <h2 class="text-3xl font-semibold mb-2">Keuangan</h2>
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


<!-- Footer -->
  <footer class="bg-gray-900 text-white py-16 mt-16">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
        <!-- Company Info -->
        <div class="col-span-1">
          <h2 class="text-2xl font-bold mb-6">GottaWork</h2>
          <p class="text-gray-400 mb-4">7101 Market Street Lampung, Indonesia</p>
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
            <li><a href="#" class="text-gray-400 hover:text-white">ciplaz</a></li>
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
