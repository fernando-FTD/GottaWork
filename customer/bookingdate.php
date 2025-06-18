<?php
require_once '../db.php';

// Validasi Sesi dan Peran Pengguna.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'Customer') {
    header("Location: ../login.php");
    exit;
}

// Mengambil ID workspace dari URL
$workspace_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$workspace_id) {
    header("Location: ketersediaanws.php?error=invalid_id");
    exit;
}

// Mengambil semua detail workspace dari database
try {
    $stmt = $conn->prepare("SELECT name, tipe, location, image_path, price FROM workspaces WHERE id = :id");
    $stmt->bindParam(':id', $workspace_id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $workspace = $stmt->fetch(); 

    if (!$workspace) {
        header("Location: ketersediaanws.php?error=not_found");
        exit;
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Menyiapkan variabel untuk ditampilkan di HTML
$workspace_name = htmlspecialchars($workspace['name']);
$workspace_tipe = htmlspecialchars($workspace['tipe']);
$workspace_location = htmlspecialchars($workspace['location']);
$image_path = htmlspecialchars($workspace['image_path']);
$price_per_hour = htmlspecialchars($workspace['price']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Booking Date</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#024545] min-h-screen flex items-center justify-center text-white">
  
  <div class="bg-white text-black rounded-lg shadow-lg p-8 w-full max-w-lg">
    <h2 class="text-2xl font-bold mb-6 text-center text-[#024545]">Pilih Tanggal Booking</h2>
    
    <form id="bookingForm" class="space-y-4">
      <div>
        <label for="name" class="block mb-1 font-semibold">Nama Lengkap Pemesan</label>
        <input type="text" id="name" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]" placeholder="Masukkan nama pemesan">
      </div>
      <div>
        <label for="email" class="block mb-1 font-semibold">Email Pemesan</label>
        <input type="email" id="email" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]" placeholder="Masukkan email pemesan">
      </div>
      <div>
        <label class="block mb-2 font-semibold">Workspace Dipilih</label>
        <div class="w-full p-4 border rounded bg-gray-100 text-gray-800 space-y-1">
            <p class="font-bold text-lg"><?= $workspace_name ?></p>
            <p class="text-sm"><span class="font-semibold">Jenis:</span> <?= $workspace_tipe ?></p>
            <p class="text-sm"><span class="font-semibold">Alamat:</span> <?= $workspace_location ?></p>
        </div>
        <input type="hidden" id="workspaceName" value="<?= $workspace_name ?>">
        <input type="hidden" id="workspaceType" value="<?= $workspace_tipe ?>">
        <input type="hidden" id="workspaceLocation" value="<?= $workspace_location ?>">
        <input type="hidden" id="imagePath" value="<?= $image_path ?>">
        <input type="hidden" id="pricePerHour" value="<?= $price_per_hour ?>">
      </div>
      <div>
        <label for="date" class="block mb-1 font-semibold">Tanggal Booking</label>
        <input type="date" id="date" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]">
      </div>
      <div>
        <label for="startTime" class="block mb-1 font-semibold">Jam Mulai</label>
        <input type="time" id="startTime" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]">
      </div>
      <div>
        <label for="endTime" class="block mb-1 font-semibold">Jam Berakhir</label>
        <input type="time" id="endTime" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]">
      </div>

      <div id="timeError" class="text-red-500 text-sm hidden"></div>

      <div class="pt-4 text-right">
        <button type="submit" class="bg-[#024545] text-white px-6 py-2 rounded hover:bg-[#036b6b]">
          Lanjut ke Denah
        </button>
      </div>
    </form>
  </div>

  <script>
    // --- Validasi jam & tanggal ---
    const dateInput = document.getElementById("date");
    const today = new Date();
    today.setDate(today.getDate()); // Bisa pesan untuk hari ini
    const minDate = today.toISOString().split("T")[0];
    dateInput.setAttribute("min", minDate);

    document.getElementById("bookingForm").addEventListener("submit", function(event) {
      event.preventDefault();

      const startTimeValue = document.getElementById("startTime").value;
      const endTimeValue = document.getElementById("endTime").value;
      const dateValue = document.getElementById("date").value;
      const timeErrorDiv = document.getElementById("timeError");

      const operatingStart = "08:00";
      const operatingEnd = "22:00";

      // Validasi jam operasional
      if (startTimeValue < operatingStart || startTimeValue >= operatingEnd || endTimeValue <= operatingStart || endTimeValue > operatingEnd) {
          timeErrorDiv.textContent = "Jam pemesanan hanya tersedia antara pukul 08:00 dan 22:00.";
          timeErrorDiv.classList.remove("hidden");
          return;
      }
      
      // Validasi jam berakhir harus setelah jam mulai (kecuali untuk lewat tengah malam)
      if (endTimeValue <= startTimeValue && endTimeValue !== "00:00") {
          timeErrorDiv.textContent = "Jam berakhir harus setelah jam mulai.";
          timeErrorDiv.classList.remove("hidden");
          return;
      }
      
      timeErrorDiv.classList.add("hidden");

      // Menyiapkan data untuk disimpan
      const bookingData = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        workspace: document.getElementById("workspaceType").value, 
        workspaceName: document.getElementById("workspaceName").value,
        workspaceLocation: document.getElementById("workspaceLocation").value,
        imagePath: document.getElementById("imagePath").value,
        pricePerHour: document.getElementById("pricePerHour").value,
        startDate: dateValue,
        endDate: dateValue, 
        startTime: startTimeValue,
        endTime: endTimeValue
      };

      localStorage.setItem("bookingData", JSON.stringify(bookingData));
      window.location.href = "denah.html";
    });
  </script>
</body>
</html>
