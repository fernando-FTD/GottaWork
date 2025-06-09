<?php
$host="localhost"; $user="root"; $pass=""; $db="database_gottawork";
$conn = new mysqli($host,$user,$pass,$db);
if($conn->connect_error) die("Conn failed: ".$conn->connect_error);

$search      = isset($_GET['search'])      ? $conn->real_escape_string($_GET['search'])      : '';
$workspace   = isset($_GET['workspace'])   ? $conn->real_escape_string($_GET['workspace'])   : '';
$date        = isset($_GET['date'])        ? $conn->real_escape_string($_GET['date'])        : '';
$start_time  = isset($_GET['start_time'])  ? $conn->real_escape_string($_GET['start_time'])  : '';
$finish_time = isset($_GET['finish_time']) ? $conn->real_escape_string($_GET['finish_time']) : '';

$query = "SELECT * FROM reservations WHERE 1";
if($search) {
  $s = $search;
  $query .= " AND (
     reservation_code LIKE '%$s%' OR
     name             LIKE '%$s%' OR
     workspace        LIKE '%$s%' OR
     date             LIKE '%$s%' OR
     TIME_FORMAT(start_time, '%H:%i')   LIKE '%$s%' OR
     TIME_FORMAT(finish_time,'%H:%i')   LIKE '%$s%'
  )";
}
if($workspace)   $query .= " AND workspace      = '$workspace'";
if($date)        $query .= " AND date           = '$date'";
if($start_time)  $query .= " AND TIME_FORMAT(start_time, '%H:%i')  = '$start_time'";
if($finish_time) $query .= " AND TIME_FORMAT(finish_time,'%H:%i')  = '$finish_time'";

$reservations = $conn->query($query);

$workspaces = ["Individual Desk","Meeting Room","Group Desk","Private Office"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Reservation List</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>body { font-family: 'Lora', serif; }</style>
  <style>
  .headerr {
      background-color: rgb(19 78 74);
    }
  </style>
</head>
<body class="bg-white text-gray-800">

<header class="headerr text-white py-4 md:py-8 relative">
  <div class="container mx-auto px-4">
    <!-- Baris Atas: Logo dan Navigasi -->
    <div class="flex justify-between items-center">
      <!-- Logo -->
      <div>
        <a href="staff_homepage.php" class="text-xl md:text-2xl font-bold text-white">GottaWork</a>
      </div>

      <!-- Navigasi -->
      <nav class="desktop-nav">
        <ul class="flex items-center space-x-6">
          <li><a href="staff_homepage.php" class="text-white hover:text-orange-400 font-medium">Home</a></li>
          <li><a href="staff_daftarreservasi.php" class="text-orange-400 font-medium  underline-offset-4">Reservation List</a></li>
          <li><a href="mengaturworkspace.html" class="text-white hover:text-orange-400 font-medium">Manage Workspace</a></li>
          <li>
            <a href="login.php" class="border border-white text-white px-6 py-2 rounded-md flex items-center justify-center hover:bg-white hover:text-[#234e52] transition-colors">
              Log Out
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </a>
          </li>
        </ul>
      </nav>
    </div>

    <!-- Baris Bawah: Judul + Tombol kiri, breadcrumb kanan -->
    <div class="mt-6 flex flex-col md:flex-row md:justify-between md:items-start gap-4">
      <!-- Kiri: Judul + tombol -->
      <div>
        <div class="mb-2">
        <span class="text-yellow-500 font-semibold text-sm md:text-base">— Reservation List</span>
      </div>
        <h1 class="text-2xl md:text-4xl font-bold mb-2">Reservation List</h1>
        <div class="space-x-4">
          <a href="staff_daftarreservasi.php"
            role="button"
            class="inline-block bg-yellow-400 hover:bg-yellow-300 text-black font-medium px-4 py-2 rounded shadow-sm transition">Reservation List</a>
          <a href="mengaturworkspace.html"
            role="button"
            class="inline-block bg-yellow-400 hover:bg-yellow-300 text-black font-medium px-4 py-2 rounded shadow-sm transition">Manage Workspace</a>
        </div>
      </div>

      <!-- Kanan: Breadcrumb -->
      <div class="flex items-center text-xs md:text-sm mt-6 md:mt-16">
        <a href="staff_homepage.php" class="text-white hover:underline">Home</a>
        <span class="mx-2">›</span>
        <span>Reservation List</span>
      </div>

    </div>
  </div>
</header>



  <section class="text-center py-10">
    <h2 class="text-sm text-red-500 tracking-widest">LOCATION</h2>
    <h1 class="text-2xl font-bold">Mall Boemi Kedaton</h1>
    <p class="text-gray-600">Bandar Lampung, Lampung</p>
    <p class="text-sm text-gray-500 mt-2">Jl. Teuku Umar No.1, Labuhan Ratu, Kec. Kedaton, Kota Bandar Lampung, Lampung 35132</p>
  </section>

  <section class="container mx-auto px-4 sm:px-6 lg:px-8 py-4 flex flex-col md:flex-row md:items-center md:space-x-2 space-y-4 md:space-y-0">
    <button id="openDrawer"
            class="flex items-center bg-teal-900 text-white px-3 py-2 rounded hover:bg-teal-800 transition">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
           viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M3 5h18M3 10h18M3 15h18"/>
      </svg>
      <span class="ml-2 font-medium">Filter</span>
    </button>

    <form method="GET" action=""
          class="flex items-center border rounded overflow-hidden flex-grow">
      <?php if(!empty($workspace)): ?>
        <input type="hidden" name="workspace"  value="<?=htmlspecialchars($workspace)?>">
      <?php endif; ?>
      <?php if(!empty($date)): ?>
        <input type="hidden" name="date"       value="<?=htmlspecialchars($date)?>">
      <?php endif; ?>
      <?php if(!empty($start_time)): ?>
        <input type="hidden" name="start_time" value="<?=htmlspecialchars($start_time)?>">
      <?php endif; ?>
      <?php if(!empty($finish_time)): ?>
        <input type="hidden" name="finish_time" value="<?=htmlspecialchars($finish_time)?>">
      <?php endif; ?>

      <input name="search"
             value="<?=htmlspecialchars($search)?>"
             placeholder="Search all fields…"
             class="flex-grow px-3 py-2 text-sm outline-none"/>

      <button type="submit"
              class="bg-yellow-400 hover:bg-yellow-300 px-3 py-2 transition">
        🔍
      </button>
    </form>
  </section>

  <div id="drawerOverlay"
       class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 transition-opacity"></div>

  <aside id="filterDrawer"
         class="fixed top-0 left-0 h-full w-80 bg-white shadow-xl z-50
                transform -translate-x-full transition-transform duration-300 flex flex-col">
    <header class="px-6 py-4 bg-teal-900 flex items-center justify-between">
      <h3 class="text-white text-lg font-semibold">Advanced Filter</h3>
      <button id="closeDrawer" class="text-white text-2xl hover:text-gray-200">&times;</button>
    </header>

    <form id="filterForm" method="GET" action=""
          class="flex-1 overflow-y-auto px-6 py-4 space-y-5">
      <?php if(!empty($search)): ?>
        <input type="hidden" name="search" value="<?=htmlspecialchars($search)?>">
      <?php endif; ?>

      <div>
        <label class="block text-sm font-medium text-gray-700">Workspace</label>
        <select name="workspace"
                class="mt-1 block w-full border-gray-300 rounded-md
                       focus:outline-none focus:ring-2 focus:ring-teal-500">
          <option value="">All</option>
          <?php foreach($workspaces as $wopt): ?>
            <option value="<?=$wopt?>"
              <?=$workspace===$wopt?'selected':''?>>
              <?=$wopt?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Date</label>
        <input type="date" name="date" value="<?=htmlspecialchars($date)?>"
               class="mt-1 block w-full border-gray-300 rounded-md
                      focus:outline-none focus:ring-2 focus:ring-teal-500"/>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Start Time</label>
        <input type="time" name="start_time" value="<?=htmlspecialchars($start_time)?>"
               class="mt-1 block w-full border-gray-300 rounded-md
                      focus:outline-none focus:ring-2 focus:ring-teal-500"/>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Finish Time</label>
        <input type="time" name="finish_time" value="<?=htmlspecialchars($finish_time)?>"
               class="mt-1 block w-full border-gray-300 rounded-md
                      focus:outline-none focus:ring-2 focus:ring-teal-500"/>
      </div>
    </form>

    <footer class="px-6 py-4 bg-gray-50 flex justify-between border-t">
      <a href="staff_daftarreservasi.php"
         class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-100 transition">
        Reset
      </a>
      <button type="submit" form="filterForm"
              class="px-4 py-2 bg-yellow-400 text-black rounded-md hover:bg-yellow-300 transition">
        Apply
      </button>
    </footer>
  </aside>


  <section class="bg-white py-3">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
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
            <?php if($reservations && $reservations->num_rows): ?>
              <?php while($r = $reservations->fetch_assoc()): ?>
                <tr class="border-t hover:bg-gray-50">
                  <td class="p-2"><?=$r['reservation_code']?></td>
                  <td class="p-2"><?=$r['name']?></td>
                  <td class="p-2"><?=$r['workspace']?></td>
                  <td class="p-2"><?=date("d/m/Y",strtotime($r['date']))?></td>
                  <td class="p-2"><?=date("H:i",  strtotime($r['start_time']))?></td>
                  <td class="p-2"><?=date("H:i",  strtotime($r['finish_time']))?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" class="p-4 text-center text-gray-500">No results found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>


  <script>
    const drawer = document.getElementById('filterDrawer');
    const overlay= document.getElementById('drawerOverlay');
    document.getElementById('openDrawer').addEventListener('click', ()=>{
      drawer.classList.remove('-translate-x-full');
      overlay.classList.remove('hidden');
    });
    document.getElementById('closeDrawer').addEventListener('click', ()=>{
      drawer.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
    });
    overlay.addEventListener('click', ()=>{
      drawer.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
    });
  </script>


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

  <script>
window.addEventListener('load', () => {
  if (history.replaceState) {
    history.replaceState(null, '', 'staff_daftarreservasi.php');
  }
});
</script>


</body>
</html>
