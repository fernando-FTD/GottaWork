<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reservation List</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">

<!-- Header Utama -->
<header class="bg-[#3e3e3e] text-white">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
    <!-- Logo -->
    <h1 class="text-2xl font-bold">GottaWork</h1>

    <!-- Navigation -->
    <nav class="hidden md:flex items-center space-x-6 text-sm">
      <a href="#" class="hover:text-yellow-400">Home</a>
      <a href="#" class="hover:text-yellow-400">Reservation List</a>
      <a href="#" class="hover:text-yellow-400">Manage Workspace</a>
      <a href="#" class="ml-4 border border-white px-3 py-1 rounded hover:bg-white hover:text-black transition">Log Out <span>›</span></a>
    </nav>
  </div>

  <!-- Judul dan Breadcrumb -->
  <div class="max-w-7xl mx-auto px-6 pt-6 pb-12">
    <!-- Breadcrumb -->
    <div class="text-sm text-white/70 mb-4">Home › Reservation list</div>

    <!-- Judul Utama -->
    <h2 class="text-4xl font-bold mb-6">Reservation list</h2>

    <!-- Tombol Aksi -->
    <div class="space-x-4">
      <button class="bg-yellow-400 hover:bg-yellow-300 text-black font-medium px-4 py-2 rounded shadow-sm transition">Reservation list</button>
      <button class="bg-yellow-400 hover:bg-yellow-300 text-black font-medium px-4 py-2 rounded shadow-sm transition">Manage Workspace</button>
    </div>
  </div>
</header>

  <!-- Location Info -->
  <section class="text-center py-10">
    <h2 class="text-sm text-red-500 tracking-widest">LOKASI</h2>
    <h1 class="text-2xl font-bold">Mall Boemi Kedaton</h1>
    <p class="text-gray-600">Bandar Lampung, Lampung</p>
    <p class="text-sm text-gray-500 mt-2">Jl. Teuku Umar No.1, Labuhan Ratu, Kec. Kedaton, Kota Bandar Lampung, Lampung 35132</p>
  </section>

  <!-- Search Form -->
  <div class="max-w-6xl mx-auto mb-4 px-4">
    <form method="GET" action="index.php" class="flex items-center border border-gray-300 rounded px-3 py-2">
      <input type="text" name="search" placeholder="Search Reservation" class="flex-grow outline-none" />
      <button type="submit">
        🔍
      </button>
    </form>
  </div>

  <!-- Reservation Table -->
  <div class="max-w-6xl mx-auto bg-white shadow rounded overflow-hidden px-4">
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
        <?php
        $search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
        $sql = "SELECT * FROM reservations";
        if ($search) {
          $sql .= " WHERE reservation_code LIKE '%$search%' 
            OR name LIKE '%$search%' 
            OR workspace LIKE '%$search%' 
            OR date LIKE '%$search%' 
            OR user_status LIKE '%$search%'";
        }
        $result = $conn->query($sql);
        if ($result->num_rows > 0):
          while($row = $result->fetch_assoc()):
        ?>
        <tr class="border-t hover:bg-gray-100">
          <td class="p-2"><?= $row['reservation_code'] ?></td>
          <td class="p-2"><?= $row['name'] ?></td>
          <td class="p-2"><?= $row['workspace'] ?></td>
          <td class="p-2"><?= $row['date'] ?></td>
          <td class="p-2"><?= $row['start_time'] ?></td>
          <td class="p-2"><?= $row['finish_time'] ?></td>
          <td class="p-2 text-<?= $row['user_status'] == 'Membership' ? 'red' : 'gray' ?>-500">
            <?= $row['user_status'] ?>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="7" class="p-4 text-center text-gray-500">No results found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- Footer -->
  <footer class="bg-gray-800 text-white mt-16 p-10 text-sm">
    <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6">
      <div>
        <h3 class="font-bold text-lg">GottaWork</h3>
        <p class="text-gray-400">768 Market Street Bandar Lampung, Indonesia</p>
        <p class="mt-2">📞 (+62) 123 456 789</p>
        <p>✉️ customer@gottawork.com</p>
        <div class="flex space-x-2 mt-2">
          <a href="#">🌐</a>
          <a href="#">📸</a>
          <a href="#">🐦</a>
        </div>
      </div>
      <div>
        <h4 class="font-bold">Company</h4>
        <ul>
          <li>Meeting Room</li>
          <li>Individual Desk</li>
          <li>Group Desk</li>
          <li>Private Office</li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold">Locations</h4>
        <ul><li>Lampung City Mall</li></ul>
      </div>
      <div>
        <h4 class="font-bold">Support</h4>
        <ul>
          <li>Book a Tour</li>
          <li>Inquire</li>
          <li>FAQ</li>
          <li>Terms of Use</li>
        </ul>
      </div>
    </div>
    <div class="text-center text-gray-500 mt-6">&copy; 2025 GottaWork. Powered by GW</div>
  </footer>
</body>
</html>