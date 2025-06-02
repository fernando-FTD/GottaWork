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
</head>
<body class="bg-white text-gray-800">

<header class="bg-teal-900 text-white">
  <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">
    <h1 class="text-2xl font-bold">GottaWork</h1>

    <nav class="hidden md:flex items-center space-x-6 text-sm">
      <a href="staff_homepage.php" class="hover:text-yellow-400">Home</a>
      <a href="staff_daftarreservasi.php" class="hover:text-yellow-400">Reservation List</a>
      <a href="mengaturworkspace.html" class="hover:text-yellow-400">Manage Workspace</a>
      <a href="login.php" class="ml-4 border border-white px-3 py-1 rounded hover:bg-white hover:text-black transition">Log Out <span>›</span></a>
    </nav>
  </div>

    <div class="max-w-7xl mx-auto px-6 pt-6 pb-12">
      <div class="text-sm text-white/70 mb-4">Home › Reservation list</div>

      <h2 class="text-4xl font-bold mb-6">Reservation list</h2>

      <div class="space-x-4">
      <a href="staff_daftarreservasi.php"
      role="button"
      class="inline-block bg-yellow-400 hover:bg-yellow-300 text-black font-medium px-4 py-2 rounded shadow-sm transition">Reservation List</a>
      <a href="staff_manageworkspace.php"
      role="button"
      class="inline-block bg-yellow-400 hover:bg-yellow-300 text-black font-medium px-4 py-2 rounded shadow-sm transition">Manage Workspace</a>
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

  <script>
window.addEventListener('load', () => {
  if (history.replaceState) {
    history.replaceState(null, '', 'staff_daftarreservasi.php');
  }
});
</script>


</body>
</html>