<?php
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

  <form id="filterForm" method="GET" action=""
        class="flex-1 overflow-y-auto px-6 py-4 space-y-6">
    <?php if(!empty($search)): ?>
      <input type="hidden" name="search" value="<?=htmlspecialchars($search)?>"/>
    <?php endif; ?>

    <div>
      <label for="code" class="block text-sm font-medium text-gray-700">Reservation Code</label>
      <input id="code" name="code"
             value="<?=htmlspecialchars($code)?>"
             type="text"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm
                    px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
    </div>

    <div>
      <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
      <input id="name" name="name"
             value="<?=htmlspecialchars($name)?>"
             type="text"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm
                    px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
    </div>

    <div>
      <label for="workspace-select" class="block text-sm font-medium text-gray-700">Workspace</label>
      <select id="workspace-select" name="workspace"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm
                     focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500">
        <option value="">All</option>
        <?php foreach($workspaces as $wopt): ?>
          <option value="<?= $wopt?>" <?= $workspace===$wopt?'selected':''?>>
            <?= $wopt ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
      <input id="date" name="date"
             value="<?=htmlspecialchars($date)?>"
             type="date"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm
                    px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
    </div>

    <div>
      <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
      <input id="start_time" name="start_time"
             value="<?=htmlspecialchars($start_time)?>"
             type="time"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm
                    px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
    </div>

    <div>
      <label for="finish_time" class="block text-sm font-medium text-gray-700">Finish Time</label>
      <input id="finish_time" name="finish_time"
             value="<?=htmlspecialchars($finish_time)?>"
             type="time"
             class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm
                    px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500"/>
    </div>
  </form>

  <footer class="px-6 py-4 border-t bg-white flex justify-between">
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

<button id="openDrawer"
        class="fixed bottom-8 right-8 flex items-center space-x-2
               bg-teal-900 text-white px-4 py-3 rounded-full shadow-lg
               hover:bg-teal-800 transition z-50">
  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
       viewBox="0 0 24 24" stroke="currentColor">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 5h18M3 12h18M3 19h18"/>
  </svg>
  <span>Filter</span>
</button>

<script>
  const overlay = document.getElementById('drawerOverlay');
  const drawer  = document.getElementById('filterDrawer');
  document.getElementById('openDrawer').addEventListener('click', () => {
    drawer.classList.remove('translate-x-full');
    overlay.classList.remove('hidden');
  });
  document.getElementById('closeDrawer').addEventListener('click', () => {
    drawer.classList.add('translate-x-full');
    overlay.classList.add('hidden');
  });
  overlay.addEventListener('click', () => {
    drawer.classList.add('translate-x-full');
    overlay.classList.add('hidden');
  });
</script>
