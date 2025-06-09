<?php
// Koneksi database
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "database_gottawork";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Ambil data pencarian jika ada
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";
$query = "SELECT * FROM reservations";
if ($search) {
  $formatted_date = '';
  $formats = ['d/m/Y', 'd/m', 'm/Y'];
  foreach ($formats as $format) {
    $date_obj = DateTime::createFromFormat($format, $search);
    if ($date_obj && $date_obj->format($format) === $search) {
      $formatted_date = $date_obj->format('Y-m-d');
      break;
    }
  }

  $is_time = preg_match('/^\d{1,2}:\d{2}$/', $search);

  $query .= " WHERE 
    reservation_code LIKE '%$search%' OR 
    name LIKE '%$search%' OR 
    workspace LIKE '%$search%' OR " .
    ($formatted_date ? "date = '$formatted_date' OR " : "") .
    ($is_time ? "(TIME_FORMAT(start_time, '%H:%i') = '$search' OR TIME_FORMAT(finish_time, '%H:%i') = '$search') OR " : "") .
    "0"; // dummy akhir
}

$reservations = $conn->query($query);

// Dummy data untuk manage workspace
$workspaces = array_fill(0, 8, [
  "title" => "Individual Desk",
  "desc" => "Individual desks, to provide privacy and enhance concentration",
  "location" => "Lampung City Mall",
  "image" => "https://www.e-abangmantek.com/wp-content/uploads/2023/02/working-space-am.jpeg"
]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Staff Homepage</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>body { font-family: 'Lora', serif; }</style>
</head>
<body class="text-gray-800">

<!-- HEADER -->
<header class="bg-teal-900 text-white py-8">
  <div class="container mx-auto px-4">
    <div class="flex justify-between items-center">
      <div>
        <a href="staff_homepage.php" class="text-2xl font-bold">GottaWork</a>
      </div>
      <nav>
        <ul class="flex items-center space-x-6">
          <li><a href="staff_homepage.php" class="text-orange-400 font-medium  underline-offset-4">Home</a></li>
          <li><a href="staff_daftarreservasi.php" class="text-white hover:text-orange-400 font-medium">Reservation List</a></li>
          <li><a href="mengaturworkspace.html" class="text-white hover:text-orange-400 font-medium">Manage Workspace</a></li>
          <li>
            <a href="login.php" class="border border-white text-white px-6 py-2 rounded-md flex items-center hover:bg-white hover:bg-opacity-10 transition-colors">
              Log Out
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</header>

<!-- Hero Section -->
<div class="bg-teal-900 text-white py-8">
  <div class="container mx-auto px-4">
    <div class="py-8 px-6 pb-16 pt-12 max-w-7xl mx-auto">
      <p class="text-sm text-yellow-300 tracking-wide mb-2">— THE PEOPLE BEHIND THE BEST WORKSPACES</p>
      <h2 class="text-4xl font-bold leading-tight mb-4">Your Dedication Shapes the Future of Work</h2>
      <p class="text-gray-300 mb-6">Together, we create an inspiring work environment that supports productivity.</p>
      <div class="space-x-4">
        <a href="staff_daftarreservasi.php"><button class="bg-yellow-400 text-black font-semibold px-4 py-2 rounded">Reservation list</button></a>
        <a href="mengaturworkspace.html"><button class="bg-yellow-400 text-black font-semibold px-4 py-2 rounded">Manage Workspace</button></a>
      </div>
    </div>
  </div>
</div>

<!-- RESERVATION LIST -->
<section class="bg-teal-900 text-white">
  <div class="max-w-7xl mx-auto px-6 py-12">
    <p class="text-sm text-yellow-300 mb-2">— RESERVATION LIST</p>
    <h2 class="text-3xl font-semibold mb-2">Reservation list</h2>
    <p class="text-sm text-white/70">Home › Reservation list</p>
  </div>
</section>

<!-- SEARCH & TABLE -->
<section class="bg-white py-10">
  <div class="px-40">
    <div class="flex gap-4">
      <div onclick="window.location.href='staff_daftarreservasi.php'" class="flex items-center border rounded px-3 py-2 w-[500px] cursor-pointer">
        <span class="text-gray-700 text-sm">
          Gotta Work at Mall Boemi Kedaton, Bandar Lampung, Lampung.
        </span>
      </div>

      <form method="GET" action="" class="flex items-center border rounded px-3 py-2 w-[680px]">
        <input name="search" placeholder="Search Reservation" class="flex-grow outline-none text-sm" />
        <button type="submit" class="ml-2 text-gray-600">🔍</button>
      </form>
    </div>

    <br>

    <div class="overflow-x-auto shadow rounded">
      <table class="min-w-full text-sm text-left">
        <thead class="bg-yellow-400 text-black">
          <tr>
            <th class="p-2">Reservation Code</th>
            <th class="p-2">Name</th>
            <th class="p-2">Workspace</th>
            <th class="p-2">Date</th>
            <th class="p-2">Start Time</th>
            <th class="p-2">Finish Time</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($reservations->num_rows > 0): while ($row = $reservations->fetch_assoc()): ?>
            <tr class="border-t hover:bg-gray-50">
              <td class="p-2"><?= $row['reservation_code'] ?></td>
              <td class="p-2"><?= $row['name'] ?></td>
              <td class="p-2"><?= $row['workspace'] ?></td>
              <td class="p-2"><?= date("d/m/Y", strtotime($row['date'])) ?></td>
              <td class="p-2"><?= date("H:i", strtotime($row['start_time'])) ?></td>
              <td class="p-2"><?= date("H:i", strtotime($row['finish_time'])) ?></td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="6" class="p-4 text-center text-gray-500">No results found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<!-- MANAGE WORKSPACE -->
<section class="bg-teal-900 text-white mt-16">
  <div class="max-w-7xl mx-auto px-6 py-12">
    <p class="text-sm text-yellow-300 mb-2">— MANAGE WORKSPACE</p>
    <h2 class="text-3xl font-semibold mb-2">Manage Workspace</h2>
    <p class="text-sm text-white/70">Home › Manage Workspace</p>
  </div>
</section>

<!-- WORKSPACE GRID -->
<section class="bg-white py-10">
  <div class="max-w-7xl mx-auto px-6">
    <div class="flex space-x-4 mb-6">
      <select class="border px-4 py-2 rounded text-sm">
        <option>All Spaces</option>
      </select>
      <select class="border px-4 py-2 rounded text-sm">
        <option>All location</option>
      </select>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
      <?php foreach ($workspaces as $ws): ?>
        <div class="border rounded shadow overflow-hidden">
          <img src="<?= $ws['image'] ?>" alt="workspace image" class="w-full h-40 object-cover" />
          <div class="p-4">
            <h3 class="font-semibold mb-1"><?= $ws['title'] ?></h3>
            <p class="text-sm text-gray-600 mb-3"><?= $ws['desc'] ?></p>
            <p class="text-sm text-gray-500 mb-2">📍 <?= $ws['location'] ?></p>
            <div class="flex justify-between">
              <button class="border px-3 py-1 text-sm rounded">Edit ›</button>
              <button class="bg-yellow-400 text-black px-3 py-1 text-sm rounded">Lihat Detail ›</button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
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
