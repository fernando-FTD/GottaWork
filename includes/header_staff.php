<?php
require_once '../db.php';

// Memeriksa apakah pengguna sudah login dan memiliki peran 'Staff'
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit;
}
if ($_SESSION['role'] !== 'Staff') {
    header("Location: ../logout.php");
    exit;
}

// Menentukan halaman aktif untuk styling link navigasi
$active_page = $active_page ?? 'home';

function is_staff_nav_active($page, $active_page) {
    return $page === $active_page ? 'text-orange-400 font-medium' : 'text-white hover:text-orange-400 font-medium';
}
?>
<header class="bg-teal-900 text-white">
  <div class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
    <a href="staff_homepage.php" class="text-2xl font-bold">GottaWork</a>
    <nav>
      <ul class="flex items-center space-x-6">
        <li><span class="text-white">Halo, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span></li>
        <li><a href="staff_homepage.php" class="<?= is_staff_nav_active('home', $active_page) ?>">Home</a></li>
        <li><a href="staff_daftarreservasi.php" class="<?= is_staff_nav_active('reservations', $active_page) ?>">Reservation List</a></li>
        <li><a href="mengaturworkspace.php" class="<?= is_staff_nav_active('workspace', $active_page) ?>">Manage Workspace</a></li>
        <a href="profile.php" class="<?= is_staff_nav_active('profile', $active_page) ?>">Profile</a>
        <li>
          <a href="../logout.php" class="border border-white text-white px-5 py-2 rounded-md flex items-center hover:bg-white hover:bg-opacity-10 transition-colors">
            Log Out
          </a>
        </li>
      </ul>
    </nav>
  </div>
</header>
