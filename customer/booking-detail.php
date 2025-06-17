<?php
require_once '../db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        die("Sesi Anda telah berakhir. Silakan login kembali.");
    }

    $user_id = $_SESSION['id'];
    
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';

    // Ambil data lain dari POST
    $startDate = $_POST['startDate'] ?? '';
    $startTime = $_POST['startTime'] ?? '';
    $endTime = $_POST['endTime'] ?? '';
    $workspace = $_POST['workspace'] ?? '';
    $deskNumber = $_POST['deskNumber'] ?? '';
    $endDate = $startDate; 

    if (empty($user_id) || empty($name) || empty($email) || empty($startDate) || empty($startTime) || empty($workspace) || empty($deskNumber)) {
        die("Data booking tidak lengkap. Silakan coba lagi.");
    }

    try {
        // Query INSERT tetap menyertakan user_id
        $sql = "INSERT INTO bookings (user_id, name, email, start_date, end_date, start_time, end_time, workspace, desk_number) 
                VALUES (:user_id, :name, :email, :start_date, :end_date, :start_time, :end_time, :workspace, :desk_number)";
        
        $stmt = $conn->prepare($sql);

        // Bind semua parameter
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->bindParam(':start_time', $startTime);
        $stmt->bindParam(':end_time', $endTime);
        $stmt->bindParam(':workspace', $workspace);
        $stmt->bindParam(':desk_number', $deskNumber);

        if ($stmt->execute()) {
            header("Location: membatalkanreservasi.php?status=success");
            exit;
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
      background-color: rgb(19,78,74);
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
            <!-- Data ini akan diisi oleh JavaScript dari localStorage -->
            <p>Nama: <span id="display_name" class="font-medium"></span></p>
            <p>Email: <span id="display_email" class="font-medium"></span></p>
            <p>Tanggal: <span id="display_date" class="font-medium"></span></p>
            <p>Waktu: <span id="display_time" class="font-medium"></span></p>
            <p>Workspace: <span id="display_workspace" class="font-medium"></span></p>
            <p>Nomor Meja: <span id="display_desk" class="font-medium"></span></p>
            <p class="mt-4 pt-4 border-t"><b>Silakan lanjutkan pembayaran ke nomor berikut: 08123456789</b></p>
          </div>
        </div>
        
        <!-- Input tersembunyi untuk mengirim data ke server -->
        <input type="hidden" id="hidden_name" name="name">
        <input type="hidden" id="hidden_email" name="email">
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

    document.getElementById("display_name").textContent = bookingData.name || "N/A";
    document.getElementById("display_email").textContent = bookingData.email || "N/A";
    document.getElementById("display_date").textContent = bookingData.startDate || "N/A";
    document.getElementById("display_time").textContent = `${bookingData.startTime || 'N/A'} - ${bookingData.endTime || 'N/A'}`;
    document.getElementById("display_workspace").textContent = bookingData.workspace || "Workspace";
    document.getElementById("display_desk").textContent = selectedDesk;
    
    document.getElementById("hidden_name").value = bookingData.name;
    document.getElementById("hidden_email").value = bookingData.email;
    document.getElementById("hidden_startDate").value = bookingData.startDate;
    document.getElementById("hidden_startTime").value = bookingData.startTime;
    document.getElementById("hidden_endTime").value = bookingData.endTime;
    document.getElementById("hidden_workspace").value = bookingData.workspace;
    document.getElementById("hidden_deskNumber").value = selectedDesk;

    // Tampilkan teks konfirmasi
    document.getElementById("desk-detail").textContent = `Detail: ${bookingData.workspace} / Meja ${selectedDesk}`;
    document.getElementById("confirmation-text").innerHTML =
      `Anda akan memesan untuk <span class="font-bold">${bookingData.name}</span> pada ` +
      `<span class="font-bold">${bookingData.startDate}</span> pukul <span class="font-bold">${bookingData.startTime} - ${bookingData.endTime}</span>.`;

    // Hapus data dari localStorage setelah booking berhasil
    document.getElementById("bookingConfirmationForm").addEventListener("submit", function() {
        localStorage.removeItem("bookingData");
    });
  </script>

</body>
</html>
