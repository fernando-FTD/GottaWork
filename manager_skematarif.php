<?php
require_once 'db.php';



// Cek apakah user sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Cek apakah user adalah Manager
if ($_SESSION['role'] !== 'Manager') {
    header("Location: manager_homepage.php");
    exit;
}

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

function getImageURL($title) {
  $title = strtolower($title);
  if (strpos($title, 'meeting') !== false) {
    return "https://s3-ap-southeast-1.amazonaws.com/xwork-gallery/rooms/images/6387/1678867571.77/6387_1678867571.77.ori.JPEG";
  } elseif (strpos($title, 'group') !== false) {
    return "https://enjoywolverhampton.com/media/pages/business-directory/spaces/d4e79d4b90-1724152261/385306743-713127874181856-1024382709204890441-n-1200x.jpg";
  } elseif (strpos($title, 'individual') !== false) {
    return "https://www.e-abangmantek.com/wp-content/uploads/2023/02/working-space-am.jpeg";
  } elseif (strpos($title, 'private') !== false) {
    return "https://bluehomediy.com/wp-content/uploads/2019/04/glass-private-gardencity-1024x767.jpg";
  } else {
    return "https://via.placeholder.com/300x200?text=Workspace"; // default
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Manager - Price Scheme</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { font-family: 'Lora', serif; }
    .headerr { background-color: rgb(19 78 74); }
  </style>
</head>
<body class="text-gray-800">

<!-- Header -->
<header class="headerr text-white py-8">
  <nav class="absolute w-full py-6 px-8 flex justify-between items-center z-10">
    <a href="manager_homepage.php" class="text-white text-3xl font-bold">GottaWork</a>
    <div class="flex items-center space-x-8">
      <a href="manager_homepage.php" class="text-yellow-400 hover:text-yellow-500 transition-colors">Home</a>
      <a href="pendapatan.html" class="text-white hover:text-yellow-400 transition-colors">Finance</a>
      <a href="manager_skematarif.php" class="text-white hover:text-yellow-400 transition-colors">Price Scheme</a>
      <a href="login.php" class="border border-white text-white px-6 py-2 rounded-md hover:bg-white hover:bg-opacity-10">Log Out ›</a>
    </div>
  </nav>

  <div class="container mx-auto px-8 pt-32">
    <h1 class="text-5xl font-bold mb-6 text-white">Price Scheme</h1>
    <div class="flex">
      <a href="manager_skematarif.php" class="bg-yellow-400 text-black px-6 py-3 rounded font-medium flex items-center">
        Price Scheme
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </a>
    </div>
  </div>
</header>

<!-- Section -->
<section class="py-16">
  <div class="text-center mb-12">
    <p class="text-red-400 uppercase text-xs tracking-widest">Price Scheme</p>
    <h2 class="text-3xl font-bold">Price</h2>
    <p class="text-sm text-gray-500 mt-2">Set up Price Scheme, current price workspace.</p>
  </div>

  <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4 py-5">
    <?php foreach ($spaces as $space): ?>
      <div class="bg-white border rounded shadow p-4">
        <img src="<?= htmlspecialchars(getImageURL($space['title'])) ?>" alt="<?= htmlspecialchars($space['title']) ?>" class="h-40 w-full object-cover mb-4 rounded">
        <h3 class="text-xl font-semibold mb-1"><?= htmlspecialchars($space['title']) ?></h3>
        <p class="text-sm text-gray-600 mb-2"><?= htmlspecialchars($space['description']) ?></p>
        <p class="text-red-500 font-bold mb-3 price-text">
          <?= htmlspecialchars($space['price']) ?>
          <span class="text-xs text-gray-500"><?= htmlspecialchars($space['unit']) ?></span>
        </p>
        <button onclick="openModal('<?= htmlspecialchars($space['title']) ?>', '<?= htmlspecialchars($space['price']) ?>', this.closest('div'))"
          class="bg-yellow-400 text-black px-4 py-2 rounded shadow text-sm">
          Edit Price
        </button>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Modal -->
<div id="priceModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-lg shadow-lg w-96 p-6">
    <h2 class="text-xl font-bold mb-4">Edit Price</h2>
    <form id="editPriceForm">
      <input type="hidden" id="editSpaceTitle">
      <label class="block text-sm font-medium mb-1">New Price</label>
      <input type="text" id="newPrice" class="w-full border px-3 py-2 mb-4 rounded" placeholder="Misal: Rp 40k" required>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancel</button>
        <button type="submit" class="px-4 py-2 rounded bg-yellow-400 hover:bg-yellow-500 text-black font-semibold">Save</button>
      </div>
    </form>
  </div>
</div>

<!-- Script -->
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
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `title=${encodeURIComponent(title)}&price=${encodeURIComponent(newPrice)}`
    })
    .then(response => response.text())
    .then(data => {
      if (data.trim() === "success") {
        console.log("Harga berhasil diperbarui.");
      } else {
        console.error("Gagal memperbarui harga:", data);
      }
    })
    .catch(error => console.error("Error:", error));

    closeModal();
  });
</script>

<!-- Footer -->
<footer class="bg-gray-900 text-white mt-16 text-sm">
  <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-6 px-6 py-10">
    <div>
      <h3 class="font-bold text-lg mb-2">GottaWork</h3>
      <p>768 Market Street Bandar Lampung, Indonesia</p>
      <p class="mt-2">📞 (+62) 123 456 789</p>
      <p>✉️ customer@gottawork.com</p>
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
