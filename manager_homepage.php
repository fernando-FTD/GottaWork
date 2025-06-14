<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] !== 'Manager') {
    header("Location: manager_homepage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Homepage</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    body { font-family: 'Lora', serif; }
    .headerr {
      background-color: rgb(19 78 74);
    }
  </style>
</head>
<body class="text-gray-800">

<header class="headerr text-white py-8">
  <div class="hero-section relative">
    <!-- Navigation -->
    <nav class="absolute w-full py-6 px-8 flex justify-between items-center z-10">
      <a href="manager_homepage.php" class="text-white text-3xl font-bold">GottaWork</a>
      
      <div class="hidden md:flex items-center space-x-8">
        <a href="manager_homepage.php" class="text-yellow-400 hover:text-yellow-500 transition-colors">Home</a>
        <a href="pendapatan.html" class="text-white hover:text-yellow-400 transition-colors">Finance</a>
        <a href="manager_skematarif.php" class="text-white hover:text-yellow-400 transition-colors">Price Scheme</a>
        <a href="login.php" class="border border-white text-white px-6 py-2 rounded-md hover:bg-white hover:bg-opacity-10 transition-colors">Log Out ›</a>
      </div>

      <!-- Mobile menu button -->
      <button id="menu-btn" class="md:hidden text-white focus:outline-none text-xl">
        <i class="fas fa-bars"></i>
      </button>
    </nav>

    <!-- Mobile Navigation -->
    <div id="mobile-menu" class="md:hidden hidden flex-col items-center space-y-4 bg-teal-900 py-6 mt-20 text-white text-center">
      <a href="manager_homepage.php" class="hover:text-yellow-400">Home</a>
      <a href="pendapatan.html" class="hover:text-yellow-400">Finance</a>
      <a href="manager_skematarif.php" class="hover:text-yellow-400">Price Scheme</a>
      <a href="login.php" class="hover:text-yellow-400">Log Out</a>
    </div>

    <!-- Hero -->
    <div class="container mx-auto px-4 md:px-8 pt-32">
      <div class="flex items-center text-yellow-400 mb-2">
        <i class="fas fa-chevron-down mr-1 text-xs"></i>
        <span class="ml-1 text-sm uppercase">— Your Insights Show the Way</span>
      </div>
      <h1 class="text-2xl md:text-3xl font-bold mb-6 text-white">Leading with data, growing with purpose</h1>
      <h3 class="text-sm md:text-base text-gray-300 mt-6 max-w-2xl">Together realize efficient workspaces through careful management to deliver the best business value.</h3>
      <br>
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="pendapatan.html" class="bg-yellow-400 text-black px-4 sm:px-6 py-2 sm:py-3 rounded font-medium flex items-center text-sm sm:text-base">
            Finance
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a>

          <a href="manager_skematarif.php" class="border border-yellow-400 text-yellow-400 px-4 sm:px-6 py-2 sm:py-3 rounded font-medium flex items-center text-sm sm:text-base">
            Price Scheme
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>

        <div class="text-sm text-white mt-2 sm:mt-0">
          <span>Home</span>
          <i class="fas fa-chevron-right mx-1 text-xs"></i>
          <span>Finance</span>
        </div>
      </div>
    </div>
  </div>
</header>

<!-- SECTION: Finance -->
<section class="bg-teal-900 text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
    <p class="text-sm text-yellow-300 mb-2 uppercase">— Finance</p>
    <h2 class="text-3xl font-semibold mb-2">Finance</h2>
  </div>
</section>

<section class="bg-white py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php
    $Finance = [
      ["title" => "Pendapatan", "desc" => "Pantau pemasukan", "img" => "https://radarlampung.disway.id/upload/28f353be6ec97564bc41050400697b64.jpg"],
      ["title" => "Pengeluaran", "desc" => "Pantau pengeluaran", "img" => "https://radarlampung.disway.id/upload/28f353be6ec97564bc41050400697b64.jpg"],
      ["title" => "Statistik", "desc" => "Pantau statistik perusahaan", "img" => "https://indopajak.id/wp-content/uploads/2024/07/jakub-zerdzicki-ip7GFn5JqX8-unsplash-1.jpg"],
    ];
    foreach ($Finance as $item):
    ?>
    <div class="border rounded shadow overflow-hidden bg-white">
      <img src="<?= $item['img'] ?>" alt="<?= $item['title'] ?>" class="w-full h-48 object-cover">
      <div class="p-4">
        <h3 class="font-semibold text-lg mb-1"><?= $item['title'] ?></h3>
        <p class="text-sm text-gray-600 mb-3"><?= $item['desc'] ?></p>
        <button class="bg-yellow-400 text-black px-4 py-1 rounded text-sm">Manage ›</button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- SECTION: Price Scheme -->
<section class="bg-teal-900 text-white mt-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
    <p class="text-sm text-yellow-300 mb-2 uppercase">— Price Scheme</p>
    <h2 class="text-3xl font-semibold mb-2">Price Scheme</h2>
    <p class="text-sm text-white/70">Home › Price Scheme</p>
  </div>
</section>

<section class="bg-white py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 flex justify-center">
    <?php
    $skema = [
      ["title" => "Harga", "desc" => "Harga Workspace", "img" => "https://assets.kompasiana.com/items/album/2015/11/08/images-563f5f2a117b61ae09d6e14b.jpg?v=400&t=o?t=o&v=770"],
    ];
    foreach ($skema as $item):
    ?>
    <div class="w-full sm:w-96 border rounded shadow overflow-hidden bg-white">
      <img src="<?= $item['img'] ?>" alt="<?= $item['title'] ?>" class="w-full h-48 object-cover">
      <div class="p-4">
        <h3 class="font-semibold text-lg mb-1"><?= $item['title'] ?></h3>
        <p class="text-sm text-gray-600 mb-3"><?= $item['desc'] ?></p>
        <a href="manager_skematarif.php"><button class="bg-yellow-400 text-black px-4 py-1 rounded text-sm">Manage ›</button></a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-16 mt-16">
  <div class="container mx-auto px-4 sm:px-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
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

    </div>

    <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
      © 2025 GottaWork. Powered by GW
    </div>
  </div>
</footer>

<!-- Mobile Menu Toggle -->
<script>
  const btn = document.getElementById('menu-btn');
  const menu = document.getElementById('mobile-menu');

  btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
  });
</script>

</body>
</html>
