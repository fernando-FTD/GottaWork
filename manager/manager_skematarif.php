<?php
require_once '../db.php'; 

// Mengambil data harga menggunakan koneksi PDO ($conn)
$spaces = [];
try {
    $query = "SELECT * FROM harga";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $spaces = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Tangani error jika gagal
    die("Error mengambil data harga: " . $e->getMessage());
}

// Fungsi untuk mendapatkan URL gambar
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
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Lora', serif; }
    .bg-custom-green { background-color: #095151; }
  </style>
</head>
<body class="flex flex-col min-h-screen text-gray-800 bg-gray-50">

<div class="flex-grow">
    <?php
        $active_page = 'Price Scheme';
        require_once '../includes/header_manager.php';
    ?>

    <div class="bg-custom-green text-white">
        <section class="max-w-7xl mx-auto px-8 py-12">
            <h4 class="text-yellow-400 font-semibold uppercase text-sm mb-2">— Price Scheme</h4>
            <h1 class="text-4xl font-bold">Workspace Pricing</h1>
            <p class="text-gray-300 mt-2 max-w-2xl">Lihat dan atur skema harga untuk setiap ruang kerja.</p>
        </section>
    </div>

    <!-- Section -->
    <main class="py-16">
      <div class="text-center mb-12">
        <p class="text-red-400 uppercase text-xs tracking-widest">Price Scheme</p>
        <h2 class="text-3xl font-bold">Current Prices</h2>
        <p class="text-sm text-gray-500 mt-2">Set up Price Scheme, current price workspace.</p>
      </div>

      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4">
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
              class="bg-yellow-400 text-black px-4 py-2 rounded shadow text-sm w-full hover:bg-yellow-500 transition">
              Edit Price
            </button>
          </div>
        <?php endforeach; ?>
      </div>
    </main>
</div>

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

<!-- Footer -->
<?php require_once '../includes/footer.php'; ?>

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

    // Mengirim pembaruan ke server
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
        alert("Gagal memperbarui harga di database."); // Beri tahu pengguna jika gagal
      }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Terjadi kesalahan koneksi.");
    });

    closeModal();
  });
</script>

</body>
</html>
