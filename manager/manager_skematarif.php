<?php
require_once '../db.php'; 

//hanya manager yang bisa mengakses
if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'Manager') {
    header("Location: ../login.php");
    exit;
}

//--- LOGIKA PEMBARUAN HARGA ---
// Cek jika ada form yang di-submit untuk update harga
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_price') {
    try {
        $workspaceId = filter_input(INPUT_POST, 'workspace_id', FILTER_VALIDATE_INT);
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_INT);
        
        // Pastikan data valid
        if ($workspaceId && $price >= 0) {
            $sql = "UPDATE workspaces SET price = :price WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':price' => $price, ':id' => $workspaceId]);
            
            // Redirect untuk mencegah re-submit form saat refresh
            header("Location: manager_skematarif.php?status=updatesuccess");
            exit;
        } else {
            $update_error = "Data yang dimasukkan tidak valid.";
        }
    } catch (PDOException $e) {
        $update_error = "Database error: " . $e->getMessage();
    }
}


$workspaces = [];
try {
    $query = "SELECT id, name, description, location, price, duration_unit, image_path FROM workspaces ORDER BY id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $workspaces = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error mengambil data workspace: " . $e->getMessage());
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
            <p class="text-gray-300 mt-2 max-w-2xl">Lihat dan atur skema harga untuk setiap ruang kerja yang terdaftar.</p>
        </section>
    </div>

    <main class="py-16">
      <div class="text-center mb-12">
        <p class="text-teal-600 uppercase text-xs tracking-widest">Price Scheme</p>
        <h2 class="text-3xl font-bold">Current Prices</h2>
        <p class="text-sm text-gray-500 mt-2">Atur skema harga untuk setiap workspace yang ada.</p>
      </div>
      
      <div class="max-w-7xl mx-auto px-4">
        <?php if (isset($_GET['status']) && $_GET['status'] == 'updatesuccess'): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p>Harga berhasil diperbarui!</p>
            </div>
        <?php endif; ?>
        <?php if (isset($update_error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p><?= htmlspecialchars($update_error) ?></p>
            </div>
        <?php endif; ?>
      </div>

      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4">
        <?php if (empty($workspaces)): ?>
            <p class="col-span-full text-center text-gray-500">Belum ada workspace yang ditambahkan. Silakan tambah melalui halaman "Manage Workspace".</p>
        <?php else: ?>
            <?php foreach ($workspaces as $space): ?>
              <div class="bg-white border rounded-lg shadow-lg p-4 flex flex-col">
                <img src="../<?= htmlspecialchars($space['image_path']) ?>" alt="<?= htmlspecialchars($space['name']) ?>" class="h-40 w-full object-cover mb-4 rounded">
                <h3 class="text-xl font-semibold mb-1"><?= htmlspecialchars($space['name']) ?></h3>
                
                <p class="text-sm text-gray-500 mb-2 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <?= htmlspecialchars($space['location']) ?>
                </p>

                <p class="text-sm text-gray-600 mb-4 flex-grow"><?= htmlspecialchars($space['description']) ?></p>

                <p class="text-teal-600 font-bold mb-3 price-text">
                  <?= "Rp " . number_format($space['price'], 0, ',', '.') ?>
                  <span class="text-xs text-gray-500 font-normal">/ <?= htmlspecialchars($space['duration_unit']) ?></span>
                </p>
                <button onclick="openModal('<?= $space['id'] ?>', '<?= $space['price'] ?>')"
                  class="bg-yellow-400 text-black px-4 py-2 rounded shadow text-sm w-full hover:bg-yellow-500 transition mt-auto">
                  Edit Harga
                </button>
              </div>
            <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </main>
</div>

<!-- Modal -->
<div id="priceModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
  <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-6">
    <h2 class="text-xl font-bold mb-4">Edit Harga</h2>
    <form id="editPriceForm" method="POST" action="manager_skematarif.php">
      <input type="hidden" name="action" value="update_price">
      <input type="hidden" id="editWorkspaceId" name="workspace_id">
      <div>
        <label for="newPrice" class="block text-sm font-medium mb-1">Harga Baru (IDR)</label>
        <input type="number" id="newPrice" name="price" class="w-full border px-3 py-2 mb-4 rounded" placeholder="Contoh: 50000" required>
      </div>
      <div class="flex justify-end gap-2">
        <button type="button" onclick="closeModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Batal</button>
        <button type="submit" class="px-4 py-2 rounded bg-yellow-400 hover:bg-yellow-500 text-black font-semibold">Simpan</button>
      </div>
    </form>
  </div>
</div>

<?php require_once '../includes/footer.php'; ?>

<script>
  function openModal(workspaceId, currentPrice) {
    document.getElementById("priceModal").classList.remove("hidden");
    document.getElementById("editWorkspaceId").value = workspaceId;
    document.getElementById("newPrice").value = currentPrice;
  }

  function closeModal() {
    document.getElementById("priceModal").classList.add("hidden");
  }
</script>

</body>
</html>
