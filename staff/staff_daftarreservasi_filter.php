
<?php
// Pastikan variabel filter terdefinisi agar tidak error
$id = isset($code) ? $code : '';
$name = isset($name) ? $name : '';
$email = isset($email) ? $email : '';
$workspace = isset($workspace) ? $workspace : '';
$desk_number = isset($desk_number) ? $desk_number : '';
$date = isset($date) ? $date : '';
$start_time = isset($start_time) ? $start_time : '';
$finish_time = isset($finish_time) ? $finish_time : '';
$workspaces = isset($workspaces) ? $workspaces : [];
$location = isset($location) ? $location : '';
$locations = isset($locations) ? $locations : [];
?>
<div id="drawerOverlay"
     class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 transition-opacity"></div>
<aside id="filterDrawer"
       class="fixed top-0 right-0 h-full w-80 bg-white shadow-xl
              transform translate-x-full transition-transform duration-300 z-50 flex flex-col">
  <header class="flex items-center justify-between px-6 py-4 bg-teal-900">
    <h3 class="text-lg font-semibold text-white">Filter Reservations</h3>
    <button id="closeDrawer" class="text-white hover:text-gray-200 text-2xl">&times;</button>
  </header>
  <form id="filterForm" method="GET" action="staff_daftarreservasi.php"
        class="flex-1 overflow-y-auto px-6 py-4 space-y-6">
    <div>
      <label for="code" class="block text-sm font-medium text-gray-700">ID</label>
      <input id="code" name="code"
             value="<?=htmlspecialchars($id)?>"
             type="text"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
    </div>
    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">Nama Pemesan</label>
      <input id="name" name="name"
             value="<?=htmlspecialchars($name)?>"
             type="text"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
    </div>
    <div>
      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
      <input id="email" name="email"
             value="<?=htmlspecialchars($email)?>"
             type="text"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
    </div>
    <div>
      <label for="workspace-select" class="block text-sm font-medium text-gray-700">Workspace</label>
      <select id="workspace-select" name="workspace"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500">
        <option value="">All</option>
        <?php foreach($workspaces as $wopt): ?>
          <option value="<?= htmlspecialchars($wopt) ?>" <?= $workspace===$wopt?'selected':''?>>
            <?= htmlspecialchars($wopt) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label for="location-select" class="block text-sm font-medium text-gray-700">Lokasi (Mall)</label>
      <select id="location-select" name="location"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500">
        <option value="">All</option>
        <?php foreach($locations as $lopt): ?>
          <option value="<?= htmlspecialchars($lopt) ?>" <?= $location===$lopt?'selected':''?>>
            <?= htmlspecialchars($lopt) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label for="desk_number" class="block text-sm font-medium text-gray-700">Meja</label>
      <input id="desk_number" name="desk_number"
             value="<?=htmlspecialchars($desk_number)?>"
             type="text"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
    </div>
    <div>
      <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
      <input id="date" name="date"
             value="<?=htmlspecialchars($date)?>"
             type="date"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
    </div>
    <div class="flex gap-2">
      <div class="w-1/2">
        <label for="start_time" class="block text-sm font-medium text-gray-700">Mulai</label>
        <input id="start_time" name="start_time"
               value="<?=htmlspecialchars($start_time)?>"
               type="time"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
      </div>
      <div class="w-1/2">
        <label for="finish_time" class="block text-sm font-medium text-gray-700">Selesai</label>
        <input id="finish_time" name="finish_time"
               value="<?=htmlspecialchars($finish_time)?>"
               type="time"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
      </div>
    </div>
    <div class="flex justify-between pt-4">
      <a href="staff_daftarreservasi.php"
         class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-100 transition">
        Reset
      </a>
      <button type="submit"
              class="px-4 py-2 bg-yellow-400 text-black rounded-md hover:bg-yellow-300 transition">
        Apply
      </button>
    </div>
  </form>
  <footer class="px-6 py-4 border-t bg-white flex justify-between"></footer>
</aside>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const overlay = document.getElementById('drawerOverlay');
    const drawer  = document.getElementById('filterDrawer');
    const openDrawerBtn = document.getElementById('openDrawerBtn');
    if (openDrawerBtn) {
      openDrawerBtn.addEventListener('click', function() {
        drawer.classList.remove('translate-x-full');
        overlay.classList.remove('hidden');
      });
    }
    document.getElementById('closeDrawer').addEventListener('click', function() {
      drawer.classList.add('translate-x-full');
      overlay.classList.add('hidden');
    });
    overlay.addEventListener('click', function() {
      drawer.classList.add('translate-x-full');
      overlay.classList.add('hidden');
    });
  });
</script>

