<?php
require_once '../db.php';

// Jika tidak login atau bukan 'Customer', akan diarahkan keluar.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] !== 'Customer') {
    header("Location: logout.php"); // Arahkan keluar jika peran tidak sesuai
    exit;
}

// Variabel '$active_page' harus didefinisikan di halaman yang memanggil header ini.
$current_page = $active_page ?? '';

// Fungsi untuk memberikan kelas CSS jika link navigasi aktif.
function is_active($page_name, $current_page) {
    return $page_name === $current_page ? 'text-orange-400 font-medium underline-offset-4' : 'text-white hover:text-orange-400 font-medium';
}
?>

<!-- Header -->
<div class="bg-custom-gray">
  <header class="text-white py-6 px-6 flex items-center justify-between max-w-7xl mx-auto">
    <div class="text-2xl font-bold">GottaWork</div>
    <div class="flex items-center">
        <!-- Menampilkan nama pengguna yang login -->
        <span class="mr-6">Halo, <?php echo htmlspecialchars($_SESSION['name']); ?>!</span>
        <nav class="flex items-center space-x-6">
            <a href="homepage.php" class="<?php echo is_active('home', $current_page); ?>">Home</a>
            <a href="ketersediaanws.php" class="<?php echo is_active('locations', $current_page); ?>">Locations</a>
            <a href="membatalkanreservasi.php" class="<?php echo is_active('reservation', $current_page); ?>">Reservation</a>
            <a href="profile.php" class="<?php echo is_active('profile', $current_page); ?>">Profile</a>
            
            <a href="bookingdate.php" class="ml-4 border border-white text-white px-4 py-2 rounded hover:bg-white hover:text-gray-800 transition">
              Book a Space <span class="ml-1">➤</span>
            </a>
        </nav>
    </div>
  </header>
</div>
