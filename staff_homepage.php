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
  $query .= " WHERE reservation_code LIKE '%$search%' OR name LIKE '%$search%' OR workspace LIKE '%$search%' OR date LIKE '%$search%' OR user_status LIKE '%$search%'";
}
$reservations = $conn->query($query);

// Dummy data untuk manage workspace
$workspaces = array_fill(0, 8, [
  "title" => "Individual Desk",
  "desc" => "Meja individu, untuk memberikan privasi dan meningkatkan konsentrasi",
  "location" => "Lampung City Mall",
  "image" => "placeholder.jpg" // Ganti dengan path asli nanti
]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Staff Homepage</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="text-gray-800">

<!-- HEADER -->
<header class="bg-[#3e3e3e] text-white">
  <div class="max-w-7xl mx-auto flex justify-between items-center px-6 py-4">
    <h1 class="text-xl font-bold">GottaWork</h1>
    <nav class="space-x-6 text-sm hidden md:flex">
      <a href="#" class="text-orange-300">Home</a>
      <a href="#">Reservation List</a>
      <a href="#">Manage Workspace</a>
      <a href="#" class="border border-white px-3 py-1 rounded hover:bg-white hover:text-black">Log Out ›</a>
    </nav>
  </div>

  <!-- Hero Section -->
  <div class="px-6 pb-16 pt-12 max-w-7xl mx-auto">
    <p class="text-sm text-yellow-300 tracking-wide mb-2">— THE PEOPLE BEHIND THE BEST WORKSPACES</p>
    <h2 class="text-4xl font-bold leading-tight mb-4">Your Dedication Shapes the Future of Work</h2>
    <p class="text-gray-300 mb-6">Together, we create an inspiring work environment that supports productivity.</p>
    <div class="space-x-4">
      <button class="bg-yellow-400 text-black font-semibold px-4 py-2 rounded">Reservation list</button>
      <button class="bg-yellow-400 text-black font-semibold px-4 py-2 rounded">Manage Workspace</button>
    </div>
  </div>
</header>

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
  <div class="max-w-7xl mx-auto px-6">
    <form method="GET" action="" class="mb-6 flex items-center border rounded px-3 py-2 w-full max-w-2xl">
      <input name="search" placeholder="Search Reservation" class="flex-grow outline-none" />
      <button type="submit">🔍</button>
    </form>

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
            <th class="p-2">User Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($reservations->num_rows > 0): while ($row = $reservations->fetch_assoc()): ?>
            <tr class="border-t hover:bg-gray-50">
              <td class="p-2"><?= $row['reservation_code'] ?></td>
              <td class="p-2"><?= $row['name'] ?></td>
              <td class="p-2"><?= $row['workspace'] ?></td>
              <td class="p-2"><?= $row['date'] ?></td>
              <td class="p-2"><?= $row['start_time'] ?></td>
              <td class="p-2"><?= $row['finish_time'] ?></td>
              <td class="p-2 <?= $row['user_status'] == 'Membership' ? 'text-red-500' : 'text-gray-500' ?>">
                <?= $row['user_status'] ?>
              </td>
            </tr>
          <?php endwhile; else: ?>
            <tr><td colspan="7" class="p-4 text-center text-gray-500">No results found.</td></tr>
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
        <option>All Lokasi</option>
      </select>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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