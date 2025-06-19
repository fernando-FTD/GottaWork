<?php
require_once '../db.php';

// Memeriksa apakah pengguna sudah login dan memiliki peran 'Staff'
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}
if (isset($_SESSION['role']) && $_SESSION['role'] !== 'Staff') {
    header("Location: ../logout.php");
    exit;
}

// Menentukan halaman aktif untuk styling link navigasi
$active_page = $active_page ?? 'home';

function is_staff_nav_active($page, $active_page) {
    return $page === $active_page ? 'text-orange-400 font-bold' : 'text-white hover:text-orange-400 font-medium';
}
?>
<header class="bg-[#095151] text-white">
  <div class="container mx-auto px-6 py-4 max-w-7xl">
    <div class="flex items-center justify-between">
      <a href="staff_homepage.php" class="text-2xl font-bold">GottaWork</a>

      <!-- Navigasi Desktop -->
      <nav class="hidden md:flex items-center space-x-6">
        <span class="text-white">Halo, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Staff'); ?>!</span>
        <a href="staff_homepage.php" class="<?= is_staff_nav_active('home', $active_page) ?>">Home</a>
        <a href="staff_daftarreservasi.php" class="<?= is_staff_nav_active('Reservations', $active_page) ?>">Reservation List</a>
        <a href="mengaturworkspace.php" class="<?= is_staff_nav_active('Workspace', $active_page) ?>">Manage Workspace</a>
        <a href="profile.php" class="<?= is_staff_nav_active('Profile', $active_page) ?>">Profile</a>
        <a href="../logout.php" class="ml-4 border border-white text-white px-5 py-2 rounded-md flex items-center hover:bg-white hover:bg-opacity-10 transition-colors">
          Log Out
        </a>
      </nav>

      <!-- Tombol Menu Mobile -->
      <div class="md:hidden">
        <button id="menu-btn-staff" class="text-white focus:outline-none">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Menu Tampilan Mobile (Dropdown) -->
  <div id="mobile-menu-staff" class="hidden md:hidden bg-[#084a4a]">
      <a href="staff_homepage.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Home</a>
      <a href="staff_daftarreservasi.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Reservation List</a>
      <a href="mengaturworkspace.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Manage Workspace</a>
      <a href="profile.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Profile</a>
      <a href="../logout.php" class="block py-3 px-6 text-sm text-white hover:bg-teal-700">Log Out</a>
  </div>
</header>

<script>
  const mobileMenuButtonStaff = document.getElementById('menu-btn-staff');
  const mobileMenuStaff = document.getElementById('mobile-menu-staff');
  
  if(mobileMenuButtonStaff && mobileMenuStaff) {
    mobileMenuButtonStaff.addEventListener('click', function() {
      mobileMenuStaff.classList.toggle('hidden');
    });
  }
</script>
