<?php
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi apakah pengguna masih login
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        // Jika tidak, hentikan proses dan beritahu pengguna
        die("Sesi Anda telah berakhir. Silakan login kembali.");
    }

    // Ambil data dari formulir yang disubmit
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $startTime = $_POST['startTime'] ?? '';
    $endTime = $_POST['endTime'] ?? '';
    $workspace = $_POST['workspace'] ?? '';
    $deskNumber = $_POST['deskNumber'] ?? '';
    
    // Asumsikan tanggal akhir sama dengan tanggal mulai
    $endDate = $startDate; 

    // Validasi sederhana
    if (empty($name) || empty($email) || empty($startDate) || empty($startTime) || empty($workspace) || empty($deskNumber)) {
        die("Data booking tidak lengkap. Silakan coba lagi.");
    }

    try {
        // Siapkan query INSERT yang aman
        $sql = "INSERT INTO bookings (name, email, start_date, end_date, start_time, end_time, workspace, desk_number) 
                VALUES (:name, :email, :start_date, :end_date, :start_time, :end_time, :workspace, :desk_number)";
        
        $stmt = $conn->prepare($sql);

        // Bind parameter ke query
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->bindParam(':start_time', $startTime);
        $stmt->bindParam(':end_time', $endTime);
        $stmt->bindParam(':workspace', $workspace);
        $stmt->bindParam(':desk_number', $deskNumber);

        // Eksekusi query
        if ($stmt->execute()) {
            // Jika berhasil, arahkan pengguna ke halaman reservasi
            header("Location: membatalkanreservasi.php?status=success");
            exit; // Penting untuk menghentikan eksekusi skrip setelah redirect
        } else {
            die("Gagal menyimpan booking ke database.");
        }

    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Booking Detail</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Lora', serif;
      background-color: #095151;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .card {
      background-color: white;
      border-radius: 10px;
      padding: 30px;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>

  <div class="card">
    <h2 id="desk-detail" class="text-2xl font-bold">Detail Reservasi</h2>
    <hr class="my-4">
    <h3 class="font-bold">Konfirmasi Booking:</h3>
    <p id="confirmation-text" class="text-gray-700"></p>
    
    <!-- Formulir yang akan mengirim data ke halaman ini sendiri (dirinya sendiri) -->
    <form id="bookingConfirmationForm" method="POST" action="booking-detail.php">
        <div class="mt-6">
          <h4 class="font-semibold">Detail Booking:</h4>
          <div class="text-gray-800 space-y-1 mt-2">
            <!-- Data ini akan diisi oleh JavaScript, tapi juga akan ada di input tersembunyi -->
            <p>Nama: <span class="font-medium"><?= htmlspecialchars($_SESSION['name']) ?></span></p>
            <p>Email: <span class="font-medium"><?= htmlspecialchars($_SESSION['email']) ?></span></p>
            <p>Tanggal: <span id="display_date" class="font-medium"></span></p>
            <p>Waktu: <span id="display_time" class="font-medium"></span></p>
            <p>Workspace: <span id="display_workspace" class="font-medium"></span></p>
            <p>Nomor Meja: <span id="display_desk" class="font-medium"></span></p>
            <p class="mt-4 pt-4 border-t"><b>Silakan lanjutkan pembayaran ke nomor berikut: 08123456789</b></p>
          </div>
        </div>
        
        <!-- Input tersembunyi untuk mengirim data ke server -->
        <input type="hidden" name="name" value="<?= htmlspecialchars($_SESSION['name']) ?>">
        <input type="hidden" name="email" value="<?= htmlspecialchars($_SESSION['email']) ?>">
        <input type="hidden" id="hidden_startDate" name="startDate">
        <input type="hidden" id="hidden_startTime" name="startTime">
        <input type="hidden" id="hidden_endTime" name="endTime">
        <input type="hidden" id="hidden_workspace" name="workspace">
        <input type="hidden" id="hidden_deskNumber" name="deskNumber">

        <div class="flex justify-between mt-6">
          <button type="button" class="bg-gray-200 text-gray-800 px-6 py-2 rounded hover:bg-gray-300" onclick="window.history.back()">Kembali</button>
          <button type="submit" class="bg-[#004d40] text-white px-6 py-2 rounded hover:bg-[#00695c]">Book Now</button>
        </div>
    </form>
  </div>

  <script>
    // Ambil data dari localStorage dan URL
    const params = new URLSearchParams(window.location.search);
    const bookingData = JSON.parse(localStorage.getItem("bookingData")) || {};
    const selectedDesk = params.get('selectedDesk') || "???";

    // Isi detail ke elemen tampilan (span)
    document.getElementById("display_date").textContent = bookingData.startDate || "N/A";
    document.getElementById("display_time").textContent = `${bookingData.startTime || 'N/A'} - ${bookingData.endTime || 'N/A'}`;
    document.getElementById("display_workspace").textContent = bookingData.workspace || "Workspace";
    document.getElementById("display_desk").textContent = selectedDesk;
    
    //input tersembunyi (hidden input) agar bisa dikirim ke server
    document.getElementById("hidden_startDate").value = bookingData.startDate;
    document.getElementById("hidden_startTime").value = bookingData.startTime;
    document.getElementById("hidden_endTime").value = bookingData.endTime;
    document.getElementById("hidden_workspace").value = bookingData.workspace;
    document.getElementById("hidden_deskNumber").value = selectedDesk;

    // Tampilkan teks konfirmasi
    document.getElementById("desk-detail").textContent = `Detail: ${bookingData.workspace} / Meja ${selectedDesk}`;
    document.getElementById("confirmation-text").innerHTML =
      `Anda akan memesan meja nomor <span class="font-bold">${selectedDesk}</span> pada ` +
      `<span class="font-bold">${bookingData.startDate}</span> pukul <span class="font-bold">${bookingData.startTime} - ${bookingData.endTime}</span>.`;

    // Hapus data dari localStorage setelah booking berhasil
    document.getElementById("bookingConfirmationForm").addEventListener("submit", function() {
        localStorage.removeItem("bookingData");
    });
  </script>

</body>
</html>
