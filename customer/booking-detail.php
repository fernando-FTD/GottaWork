<?php
require_once '../db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin'] || !isset($_SESSION['id'])) {
        die("Sesi Anda telah berakhir. Silakan login kembali.");
    }

    $user_id = $_SESSION['id'];
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $startDate = $_POST['startDate'] ?? '';
    $startTime = $_POST['startTime'] ?? '';
    $endTime = $_POST['endTime'] ?? '';
    $workspaceType = $_POST['workspace_type'] ?? '';
    $workspaceName = $_POST['workspace_name'] ?? '';
    $workspaceLocation = $_POST['location'] ?? '';
    $imagePath = $_POST['image_path'] ?? ''; 
    $deskNumber = $_POST['deskNumber'] ?? '';
    $endDate = $startDate; 

    if (empty($user_id) || empty($name) || empty($email) || empty($startDate) || empty($startTime) || empty($endTime) || empty($workspaceType) || empty($deskNumber)) {
        die("Data booking tidak lengkap. Silakan coba lagi.");
    }

    $conn->beginTransaction();

    try {
        $price_sql = "SELECT price FROM workspaces WHERE name = :workspaceName LIMIT 1";
        $price_stmt = $conn->prepare($price_sql);
        $price_stmt->bindParam(':workspaceName', $workspaceName);
        $price_stmt->execute();
        $price_stmt->setFetchMode(PDO::FETCH_ASSOC);
        $price_result = $price_stmt->fetch(); 
        $price_per_hour = ($price_result) ? (int) $price_result['price'] : 0;

        $start_timestamp = strtotime($startTime);
        $end_timestamp = strtotime($endTime);

        $difference_seconds = $end_timestamp - $start_timestamp;
        if ($difference_seconds < 0) {
            $difference_seconds += 24 * 3600; // Tambah 24 jam jika melewati tengah malam
        }
        $duration_hours = $difference_seconds / 3600;

        if ($duration_hours == 0) $duration_hours = 1; // Durasi minimal 1 jam
        
        $total_price = $price_per_hour * $duration_hours;

        $sql_booking = "INSERT INTO bookings (user_id, name, email, start_date, end_date, start_time, end_time, workspace_type, workspace_name, location, image_path, desk_number) 
                        VALUES (:user_id, :name, :email, :start_date, :end_date, :start_time, :end_time, :workspace_type, :workspace_name, :location, :image_path, :desk_number)";
        
        $stmt_booking = $conn->prepare($sql_booking);
        $stmt_booking->execute([
            ':user_id' => $user_id, ':name' => $name, ':email' => $email, ':start_date' => $startDate,
            ':end_date' => $endDate, ':start_time' => $startTime, ':end_time' => $endTime,
            ':workspace_type' => $workspaceType, ':workspace_name' => $workspaceName, ':location' => $workspaceLocation,
            ':image_path' => $imagePath, ':desk_number' => $deskNumber
        ]);
        $last_booking_id = $conn->lastInsertId();

        $sql_transaction = "INSERT INTO transactions (tanggal, deskripsi, kategori, jumlah, status, booking_id) 
                            VALUES (:tanggal, :deskripsi, :kategori, :jumlah, :status, :booking_id)";
        
        $stmt_transaction = $conn->prepare($sql_transaction);
        $deskripsi = "Booking " . htmlspecialchars($workspaceName) . " Meja " . htmlspecialchars($deskNumber) . " (" . number_format($duration_hours, 1) . " jam)";
        $stmt_transaction->execute([
            ':tanggal' => $startDate, ':deskripsi' => $deskripsi, ':kategori' => "Booking Workspace",
            ':jumlah' => $total_price, ':status' => "Tertunda", ':booking_id' => $last_booking_id
        ]);

        $conn->commit();
        header("Location: membatalkanreservasi.php?status=success");
        exit;

    } catch (Exception $e) {
        $conn->rollBack();
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
    body { font-family: 'Lora', serif; background-color: rgb(19,78,74); display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
    .card { background-color: white; border-radius: 10px; padding: 30px; max-width: 600px; width: 90%; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
  </style>
</head>
<body>

  <div class="card">
    <h2 id="desk-detail" class="text-2xl font-bold">Detail Reservasi</h2>
    <hr class="my-4">
    
    <form id="bookingConfirmationForm" method="POST" action="booking-detail.php">
        <div class="mt-6">
          <h4 class="font-semibold">Detail Booking:</h4>
          <div class="text-gray-800 space-y-2 mt-2">
            <p>Nama: <span id="display_name" class="font-medium"></span></p>
            <p>Email: <span id="display_email" class="font-medium"></span></p>
            <p>Workspace: <span id="display_workspace_name" class="font-medium"></span></p>
            <p>Jenis: <span id="display_workspace_type" class="font-medium"></span></p>
            <p>Lokasi: <span id="display_location" class="font-medium"></span></p>
            <p>Nomor Meja: <span id="display_desk" class="font-medium"></span></p>
            <p>Tanggal: <span id="display_date" class="font-medium"></span></p>
            <p>Waktu: <span id="display_time" class="font-medium"></span></p>
            <p>Durasi: <span id="display_duration" class="font-medium"></span></p>
            <div class="mt-4 pt-4 border-t">
                <p class="text-lg font-bold">Total Biaya: <span id="display_total_price" class="text-teal-600"></span></p>
                <p class="text-sm text-gray-500">Silakan lanjutkan pembayaran ke nomor berikut: 08123456789</p>
            </div>
          </div>
        </div>
        
        <input type="hidden" id="hidden_name" name="name">
        <input type="hidden" id="hidden_email" name="email">
        <input type="hidden" id="hidden_startDate" name="startDate">
        <input type="hidden" id="hidden_startTime" name="startTime">
        <input type="hidden" id="hidden_endTime" name="endTime">
        <input type="hidden" id="hidden_workspace_type" name="workspace_type">
        <input type="hidden" id="hidden_workspace_name" name="workspace_name">
        <input type="hidden" id="hidden_location" name="location">
        <input type="hidden" id="hidden_image_path" name="image_path">
        <input type="hidden" id="hidden_deskNumber" name="deskNumber">

        <div class="flex justify-between mt-6">
          <button type="button" class="bg-gray-200 text-gray-800 px-6 py-2 rounded hover:bg-gray-300" onclick="window.history.back()">Kembali</button>
          <button type="submit" class="bg-[#004d40] text-white px-6 py-2 rounded hover:bg-[#00695c]">Book Now</button>
        </div>
    </form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        const bookingData = JSON.parse(localStorage.getItem("bookingData")) || {};
        const selectedDesk = params.get('selectedDesk') || "???";

        //Kalkulasi dan tampilan harga di sisi klien ---
        const startTime = new Date(`1970-01-01T${bookingData.startTime}`);
        const endTime = new Date(`1970-01-01T${bookingData.endTime}`);
        let differenceMs = endTime - startTime;
        if (differenceMs < 0) {
            differenceMs += 24 * 60 * 60 * 1000; // Tambah 24 jam dalam milidetik
        }
        const durationHours = differenceMs / (1000 * 60 * 60);

        const pricePerHour = parseFloat(bookingData.pricePerHour) || 0;
        const totalPrice = pricePerHour * (durationHours > 0 ? durationHours : 1);

        // Menampilkan data
        document.getElementById("display_name").textContent = bookingData.name || "N/A";
        document.getElementById("display_email").textContent = bookingData.email || "N/A";
        document.getElementById("display_workspace_name").textContent = bookingData.workspaceName || "N/A";
        document.getElementById("display_workspace_type").textContent = bookingData.workspace || "N/A";
        document.getElementById("display_location").textContent = bookingData.workspaceLocation || "N/A";
        document.getElementById("display_desk").textContent = selectedDesk;
        document.getElementById("display_date").textContent = bookingData.startDate || "N/A";
        document.getElementById("display_time").textContent = `${bookingData.startTime || 'N/A'} - ${bookingData.endTime || 'N/A'}`;
        document.getElementById("display_duration").textContent = `${durationHours.toFixed(1)} jam`;
        document.getElementById("display_total_price").textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(totalPrice);

        // Mengisi form tersembunyi
        document.getElementById("hidden_name").value = bookingData.name;
        document.getElementById("hidden_email").value = bookingData.email;
        document.getElementById("hidden_startDate").value = bookingData.startDate;
        document.getElementById("hidden_startTime").value = bookingData.startTime;
        document.getElementById("hidden_endTime").value = bookingData.endTime;
        document.getElementById("hidden_workspace_type").value = bookingData.workspace;
        document.getElementById("hidden_workspace_name").value = bookingData.workspaceName;
        document.getElementById("hidden_location").value = bookingData.workspaceLocation;
        document.getElementById("hidden_image_path").value = bookingData.imagePath;
        document.getElementById("hidden_deskNumber").value = selectedDesk;

        document.getElementById("bookingConfirmationForm").addEventListener("submit", function() {
            localStorage.removeItem("bookingData");
        });
    });
  </script>

</body>
</html>
