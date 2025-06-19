<?php
require_once '../db.php';

try {
    $sql = "SELECT id, name, tipe, location, description, price, duration_unit, image_path 
            FROM workspaces 
            WHERE status = 'Aktif' 
            ORDER BY id DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $workspaces = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $workspaces = [];
    $error_message = "Gagal memuat data workspace: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Explore Workspaces</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Lora&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Lora', serif; }
    .bg-custom-gray { background-color: #095151; }
  </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-50">

  <div class="flex-grow">
    <?php
      $active_page = 'Locations';
      require_once '../includes/header_customer.php';
    ?>

    <div class="bg-custom-gray text-white">
      <section class="max-w-7xl mx-auto px-6 py-12">
        <h4 class="text-yellow-400 font-semibold uppercase text-sm mb-2">—Locations</h4>
        <h2 class="text-4xl font-bold mb-4">Explore Available Office Spaces</h2>
        <p class="text-gray-300 mb-10 max-w-2xl">Daily/monthly desk, chair and room facilities with fast internet and an atmosphere that motivates productivity.</p>
      </section>
    </div>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <?php if (isset($error_message)): ?>
            <p class="text-red-500 text-center"><?php echo htmlspecialchars($error_message); ?></p>
        <?php elseif (empty($workspaces)): ?>
            <p class="text-gray-500 text-center text-lg">Saat ini belum ada workspace yang tersedia untuk dipesan.</p>
        <?php else: ?>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
          <?php foreach ($workspaces as $row): ?>
            <div class="bg-white border rounded-lg shadow-lg overflow-hidden flex flex-col">
              <img src="../<?= htmlspecialchars($row['image_path']) ?>" class="w-full h-48 object-cover" alt="<?= htmlspecialchars($row['name']) ?>" />
              <div class="p-4 space-y-2 flex flex-col flex-grow">
                <h3 class="font-semibold text-lg"><?= htmlspecialchars($row['name']) ?></h3>
                <p class="text-sm text-gray-600 flex-grow"><?= htmlspecialchars($row['description']) ?></p>
                <div class="flex items-center text-sm text-gray-500 pt-2">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13401 2 5 5.13401 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.86599-3.13401-7-7-7z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="9" r="2.5" fill="currentColor" /></svg>
                  <?= htmlspecialchars($row['location']) ?>
                </div>
                <div class="flex justify-between items-center mt-2">
                  <span class="text-red-500 font-semibold">
                    Rp <?= number_format($row['price'], 0, ',', '.') ?>
                    <span class="text-sm text-gray-500">/<?= $row['duration_unit'] ?></span>
                  </span>
                  <a href="bookingdate.php?id=<?= $row['id'] ?>" class="bg-yellow-400 hover:bg-yellow-500 text-black px-4 py-1 rounded text-sm font-medium inline-block text-center">Book now →</a>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </main>
  </div>

  <?php
    require_once '../includes/footer.php';
  ?>

</body>
</html>
