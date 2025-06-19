<?php
require_once '../db.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'Manager') {
    header("Location: ../logout.php");
    exit;
}

// Menentukan halaman aktif untuk styling link navigasi
$active_page = $active_page ?? 'home';

function is_manager_nav_active($page, $active_page) {
    return $page === $active_page ? 'text-orange-400 font-bold' : 'text-white hover:text-orange-400 font-medium';
}
?>
<!-- Header -->
<header class="bg-custom-gray bg-[#095151]">
  <div class="container mx-auto px-6 py-4 max-w-7xl">
    <div class="flex items-center justify-between">
      <a href="manager_homepage.php" class="text-white text-2xl font-bold">GottaWork</a>
      
      <!-- Navigasi Desktop -->
      <nav class="hidden md:flex items-center space-x-6">
        <span class="text-white">Hi, <?= htmlspecialchars($_SESSION['name'] ?? 'Manager') ?>!</span>
        <a href="manager_homepage.php" class="<?= is_manager_nav_active('Home', $active_page) ?>">Home</a>
        <a href="finance.php" class="<?= is_manager_nav_active('Finance', $active_page) ?>">Finance</a>
        <a href="manager_skematarif.php" class="<?= is_manager_nav_active('Price Scheme', $active_page) ?>">Price Scheme</a>
        <a href="profile.php" class="<?= is_manager_nav_active('Profile', $active_page) ?>">Profile</a>
        <a href="../logout.php" class="ml-4 border border-white text-white px-5 py-2 rounded hover:bg-white hover:bg-opacity-10 transition-colors">Log Out ›</a>
      </nav>

      <!-- Tombol Menu Mobile -->
      <div class="md:hidden">
        <button id="menu-btn-manager" class="text-white focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        </button>
      </div>
    </div>
  </div>
  
  <!-- Menu Tampilan Mobile (Dropdown) -->
  <div id="mobile-menu-manager" class="hidden md:hidden bg-[#084a4a]">
      <a href="manager_homepage.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Home</a>
      <a href="finance.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Finance</a>
      <a href="manager_skematarif.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Price Scheme</a>
      <a href="profile.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Profile</a>
      <a href="../logout.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Log Out</a>
  </div>
</header>

<script>
  const mobileMenuButtonManager = document.getElementById('menu-btn-manager');
  const mobileMenuManager = document.getElementById('mobile-menu-manager');

  if (mobileMenuButtonManager && mobileMenuManager) {
    mobileMenuButtonManager.addEventListener('click', function() {
      mobileMenuManager.classList.toggle('hidden');
    });
  }
</script>
