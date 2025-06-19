<?php
require_once '../db.php';

// Jika tidak login atau bukan 'Customer', akan diarahkan keluar.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'Customer') {
    header("Location: ../logout.php"); // Arahkan keluar jika peran tidak sesuai
    exit;
}

// Variabel '$active_page' harus didefinisikan di halaman yang memanggil header ini.
$current_page = $active_page ?? '';

// Fungsi untuk memberikan kelas CSS jika link navigasi aktif.
function is_active($page_name, $current_page) {
    return $page_name === $current_page ? 'text-orange-400 font-bold' : 'text-white hover:text-orange-400 font-medium';
}
?>

<!-- Header -->
<header class="bg-[#095151] text-white">
  <div class="container mx-auto px-6 py-4 max-w-7xl">
    <div class="flex items-center justify-between">
      <a href="homepage.php" class="text-2xl font-bold">GottaWork</a>
      
      <!-- Navigasi Desktop (Tersembunyi di layar kecil) -->
      <nav class="hidden md:flex items-center space-x-6">
          <span class="text-white">Halo, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Guest'); ?>!</span>
          <a href="homepage.php" class="<?php echo is_active('Home', $current_page); ?>">Home</a>
          <a href="ketersediaanws.php" class="<?php echo is_active('Locations', $current_page); ?>">Locations</a>
          <a href="membatalkanreservasi.php" class="<?php echo is_active('Reservation', $current_page); ?>">Reservation</a>
          <a href="profile.php" class="<?php echo is_active('Profile', $current_page); ?>">Profile</a>
          <a href="ketersediaanws.php" class="ml-4 border border-white text-white px-4 py-2 rounded hover:bg-white hover:text-gray-800 transition">
            Book a Space <span class="ml-1">➤</span>
          </a>
      </nav>
      
      <!-- Tombol Menu Mobile (Hanya muncul di layar kecil) -->
      <div class="md:hidden">
        <button id="mobile-menu-button" class="text-white focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Menu Mobile (Dropdown) -->
  <div id="mobile-menu" class="hidden md:hidden bg-[#084a4a]">
    <a href="homepage.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Home</a>
    <a href="ketersediaanws.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Locations</a>
    <a href="membatalkanreservasi.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Reservation</a>
    <a href="profile.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Profile</a>
    <a href="ketersediaanws.php" class="block py-3 px-6 text-sm hover:bg-teal-700">Book a Space</a>
  </div>
</header>

<script>
  // Pastikan script tidak error jika elemen tidak ditemukan
  const mobileMenuButton = document.getElementById('mobile-menu-button');
  const mobileMenu = document.getElementById('mobile-menu');

  if (mobileMenuButton && mobileMenu) {
    mobileMenuButton.addEventListener('click', function() {
      mobileMenu.classList.toggle('hidden');
    });
  }
</script>
