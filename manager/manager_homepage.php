<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manager Homepage</title>
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
      $active_page = 'Home';
      require_once '../includes/header_manager.php';
    ?>

    <!-- Wadah untuk Hero Section -->
    <div class="bg-custom-green text-white">
      <div class="container mx-auto px-8 py-20">
          <div class="flex items-center text-yellow-400 mb-2">
              <i class="fas fa-chevron-down mr-2 text-xs"></i>
              <span class="text-sm uppercase tracking-wider">— Your Insights Show the Way</span>
          </div>
          <h1 class="text-2xl md:text-3xl font-bold mb-6">Leading with data, growing with purpose</h1>
          <p class="text-base text-gray-300 mt-6 max-w-2xl">Together realize efficient workspaces through careful management to deliver the best business value.</p>
          <br>
          <div class="flex flex-col sm:flex-row items-start gap-4 mt-4">
              <a href="finance.php" class="bg-yellow-400 text-black px-6 py-3 rounded font-medium flex items-center text-sm">
                  Finance
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
              </a>
              <a href="manager_skematarif.php" class="border border-yellow-400 text-yellow-400 px-6 py-3 rounded font-medium flex items-center text-sm">
                  Price Scheme
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
              </a>
          </div>
      </div>
    </div>

    <!-- Konten Utama Halaman -->
    <section class="bg-custom-green">
      <div class="max-w-7xl mx-auto px-8 py-12">
        <p class="text-sm text-yellow-300 mb-2 uppercase">— Finance</p>
        <h2 class="text-3xl font-semibold mb-2 text-white">Finance</h2>
      </div>
    </section>
    
    <section class="bg-white py-10">
      <div class="max-w-7xl mx-auto px-8 flex flex-wrap justify-center gap-8">
        <?php
        $Finance = [
          ["title" => "Pendapatan", "desc" => "Pantau pemasukan", "img" => "../assets/pendapatan.jpg", "link" => "finance.php?type=income"],
          ["title" => "Pengeluaran", "desc" => "Pantau pengeluaran", "img" => "../assets/pengeluaran.jpg", "link" => "finance.php?type=expense"],
        ];
        foreach ($Finance as $item):
        ?>
        <div class="border rounded-lg shadow overflow-hidden bg-white">
          <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-lg mb-1"><?= htmlspecialchars($item['title']) ?></h3>
            <p class="text-sm text-gray-600 mb-3"><?= htmlspecialchars($item['desc']) ?></p>
            <a href="<?= $item['link'] ?>" class="inline-block bg-yellow-400 text-black px-4 py-1 rounded text-sm hover:bg-yellow-300 transition">Manage ›</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>
    
    <section class="bg-custom-green mt-16">
      <div class="max-w-7xl mx-auto px-8 py-12">
        <p class="text-sm text-yellow-300 mb-2 uppercase">— Price Scheme</p>
        <h2 class="text-3xl font-semibold mb-2 text-white">Price Scheme</h2>
        <p class="text-sm text-white/70">Home › Price Scheme</p>
      </div>
    </section>
    
    <section class="bg-white py-10">
      <div class="max-w-7xl mx-auto px-8 flex justify-center">
        <?php
        $skema = [
          ["title" => "Harga", "desc" => "Harga Workspace", "img" => "../assets/harga.jpg"],
        ];
        foreach ($skema as $item):
        ?>
        <div class="w-full sm:w-96 border rounded-lg shadow overflow-hidden bg-white">
          <img src="<?= htmlspecialchars($item['img']) ?>" alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-48 object-cover">
          <div class="p-4">
            <h3 class="font-semibold text-lg mb-1"><?= htmlspecialchars($item['title']) ?></h3>
            <p class="text-sm text-gray-600 mb-3"><?= htmlspecialchars($item['desc']) ?></p>
            <a href="manager_skematarif.php" class="inline-block bg-yellow-400 text-black px-4 py-1 rounded text-sm hover:bg-yellow-300 transition">Manage ›</a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>
</div>

<?php
  require_once '../includes/footer.php';
?>

</body>
</html>
