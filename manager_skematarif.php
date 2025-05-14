<?php
$koneksi = new mysqli("localhost", "root", "", "database_gottawork");
if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

$spaces = [];
$query = "SELECT * FROM harga";
$result = $koneksi->query($query);
while ($row = $result->fetch_assoc()) {
  $spaces[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Skema Tarif - GottaWork</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">

<header class="bg-gray-800 text-white">
  <div class="max-w-7xl mx-auto py-6 px-4 flex justify-between items-center">
    <div class="text-2xl font-bold">GottaWork</div>
    <nav class="space-x-6 text-sm">
      <a href="#" class="hover:underline">Home</a>
      <a href="#" class="hover:underline">Keuangan</a>
      <a href="#" class="text-yellow-400">Skema Tarif</a>
      <button class="ml-4 bg-gray-700 border border-white px-3 py-1 rounded text-sm">Log Out</button>
    </nav>
  </div>
</header>

<section class="py-16">
  <div class="text-center mb-12">
    <p class="text-red-400 uppercase text-xs tracking-widest">Skema Tarif</p>
    <h2 class="text-3xl font-bold">Harga</h2>
    <p class="text-sm text-gray-500 mt-2">Mengatur Skema Tarif, Harga Workspace Saat Ini.</p>
  </div>

  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4">
    <?php foreach ($spaces as $space): ?>
      <div class="bg-white border rounded shadow p-4">
        <div class="h-40 bg-gray-100 mb-4 flex items-center justify-center text-gray-400">[Gambar <?= htmlspecialchars($space['title']) ?>]</div>
        <h3 class="text-xl font-semibold mb-1"><?= htmlspecialchars($space['title']) ?></h3>
        <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars($space['description']) ?></p>
        <p class="text-red-500 font-bold mb-3 price-text">
          <?= htmlspecialchars($space['price']) ?>
          <span class="text-xs text-gray-500"><?= htmlspecialchars($space['unit']) ?></span>
        </p>
        <button onclick="openModal('<?= htmlspecialchars($space['title']) ?>', '<?= $space['price'] ?>', this.closest('div'))" class="bg-yellow-400 text-black px-4 py-2 rounded shadow text-sm">
          Edit Harga
        </button>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Modal -->
<div id="priceModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-lg shadow-lg w-96 p-6">
    <h2 class="text-xl font-bold mb-4">Edit Harga</h2>
    <form id="editPriceForm">
      <input type="hidden" id="editSpaceTitle">
      <label class="block text-sm font-medium mb-1">Harga Baru</label>
      <input type="text" id="newPrice" class="w-full border px-3 py-2 mb-4 rounded" placeholder="Misal: Rp 40k">
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 rounded bg-yellow-400 hover:bg-yellow-500 text-black font-semibold">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  let selectedCard = null;

  function openModal(title, currentPrice, cardElement) {
    selectedCard = cardElement;
    document.getElementById("priceModal").classList.remove("hidden");
    document.getElementById("editSpaceTitle").value = title;
    document.getElementById("newPrice").value = currentPrice;
  }

  function closeModal() {
    document.getElementById("priceModal").classList.add("hidden");
  }

  document.getElementById("editPriceForm").addEventListener("submit", function(e) {
    e.preventDefault();
    const newPrice = document.getElementById("newPrice").value;
    const title = document.getElementById("editSpaceTitle").value;

    if (selectedCard) {
      const priceElement = selectedCard.querySelector(".price-text");
      const unit = priceElement.querySelector("span").innerText;
      priceElement.innerHTML = `${newPrice}<span class="text-xs text-gray-500">${unit}</span>`;
    }

    fetch("db_harga.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `title=${encodeURIComponent(title)}&price=${encodeURIComponent(newPrice)}`
    })
    .then(response => response.text())
    .then(data => {
      if (data.trim() === "success") {
        console.log("Harga berhasil diperbarui.");
      } else {
        console.error("Gagal memperbarui harga.");
      }
    })
    .catch(error => console.error("Error:", error));

    closeModal();
  });
</script>

<footer class="bg-gray-900 text-white py-12 mt-12">
  <div class="max-w-7xl mx-auto text-center text-xs text-gray-400">© 2025 GottaWork. Powered by GW</div>
</footer>

</body>
</html>
