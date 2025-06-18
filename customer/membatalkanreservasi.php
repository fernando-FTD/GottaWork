<?php
require_once '../db.php'; 

// Pastikan pengguna sudah login dan memiliki ID sesi
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}
$user_id = $_SESSION['id'];

// Mengatur zona waktu default untuk semua fungsi tanggal/waktu di PHP ke WIB
date_default_timezone_set('Asia/Jakarta');

// Logika untuk menangani pembatalan reservasi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_to_cancel'])) {
    $reservation_id = $_POST['id_to_cancel'];
    
    $conn->beginTransaction();
    try {
        $stmt_trans = $conn->prepare("DELETE FROM transactions WHERE booking_id = :booking_id");
        $stmt_trans->bindParam(':booking_id', $reservation_id, PDO::PARAM_INT);
        $stmt_trans->execute();

        $stmt_book = $conn->prepare("DELETE FROM bookings WHERE id = :id AND user_id = :user_id");
        $stmt_book->bindParam(':id', $reservation_id, PDO::PARAM_INT);
        $stmt_book->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt_book->execute();
        
        $conn->commit();
        
        header("Location: membatalkanreservasi.php?status=cancelled");
        exit;
    } catch (PDOException $e) {
        $conn->rollBack();
        die("Error saat membatalkan reservasi: " . $e->getMessage());
    }
}

$upcoming_reservations = [];
try {
    // 1. Ambil SEMUA reservasi milik pengguna, tanpa filter waktu di SQL.
    $sql = "SELECT b.*, t.jumlah as total_price, t.status as transaction_status
            FROM bookings b
            LEFT JOIN transactions t ON b.id = t.booking_id
            WHERE b.user_id = :user_id 
            ORDER BY b.start_date ASC, b.start_time ASC";
            
    $stmt = $conn->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);
    $all_reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Filter reservasi yang akan datang menggunakan logika PHP
    $now = new DateTime(); // Waktu saat ini (sudah diatur ke WIB)

    foreach ($all_reservations as $res) {
        // Gabungkan tanggal dan jam berakhir menjadi satu objek DateTime
        $end_datetime_str = $res['start_date'] . ' ' . $res['end_time'];
        $end_datetime = new DateTime($end_datetime_str);

        // Jika jam berakhir lebih kecil dari jam mulai, berarti booking melewati tengah malam
        if ($res['end_time'] < $res['start_time']) {
            $end_datetime->modify('+1 day');
        }

        // Jika waktu berakhir reservasi masih di masa depan, masukkan ke array untuk ditampilkan
        if ($end_datetime >= $now) {
            $upcoming_reservations[] = $res;
        }
    }

} catch (PDOException $e) {
    $upcoming_reservations = [];
    $error_message = "Gagal memuat data reservasi: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Reservasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
      .bg-custom-gray { background-color: #095151; }
      body { font-family: 'Lora', serif; }
    </style>
  </head>
  <body class="flex flex-col min-h-screen bg-gray-50">
    <div class="flex-grow">
        <?php 
          $active_page = 'reservation'; 
          require_once '../includes/header_customer.php'; 
        ?>

        <div class="bg-custom-gray">
          <section class="px-6 py-16 max-w-7xl mx-auto text-white">
            <p class="text-yellow-400 uppercase text-sm tracking-widest">— My Reservations</p>
            <h1 class="text-5xl font-bold mt-4 leading-tight">Manage Reservations</h1>
            <p class="text-gray-300 mt-2 max-w-2xl">Lihat dan kelola semua reservasi ruang kerja Anda yang akan datang.</p>
          </section>
        </div>

        <main class="container mx-auto p-8">
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Booking Berhasil!</p>
                    <p>Reservasi Anda telah berhasil disimpan.</p>
                </div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] == 'cancelled'): ?>
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>Reservasi Anda dan catatan pendapatan terkait telah berhasil dibatalkan.</p>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (!empty($upcoming_reservations)): ?>
                    <?php foreach ($upcoming_reservations as $row):
                        $image_path = (!empty($row['image_path']) && file_exists('../' . $row['image_path'])) 
                                        ? $row['image_path'] 
                                        : 'https://placehold.co/600x400/EEE/31343C?text=Image+Not+Found';
                        
                        $src = (filter_var($image_path, FILTER_VALIDATE_URL)) ? $image_path : '../' . $image_path;
                    ?>
                        <div class="bg-white border rounded-lg shadow-md hover:shadow-lg transition-shadow flex flex-col">
                          <img src="<?= htmlspecialchars($src); ?>" class="rounded-t-lg h-48 w-full object-cover" alt="<?= htmlspecialchars($row['workspace_name']); ?>">
                          <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-xl font-semibold mb-1"><?= htmlspecialchars($row['workspace_name']) ?></h3>
                            <p class="text-sm text-gray-500">
                                <?= htmlspecialchars($row['workspace_type']) ?> / Meja <?= htmlspecialchars($row['desk_number']) ?>
                            </p>
                            <p class="text-sm text-gray-600 mb-2 mt-2">
                              📍 <?= htmlspecialchars($row['location']) ?>
                            </p>
                            <div class="mt-auto space-y-2 pt-4">
                                <button type="button" onclick='openReservationDetailModal(<?= json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="w-full bg-gray-200 text-gray-800 text-sm font-semibold px-4 py-2 rounded hover:bg-gray-300 transition">
                                    Lihat Detail
                                </button>
                                
                                <?php if (isset($row['transaction_status']) && $row['transaction_status'] !== 'Selesai'): ?>
                                    <form method="POST" action="membatalkanreservasi.php" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?');">
                                        <input type="hidden" name="id_to_cancel" value="<?= htmlspecialchars($row['id']); ?>">
                                        <button type="submit" class="w-full bg-red-500 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-red-600 transition">
                                            Batalkan Reservasi
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <div class="w-full bg-green-200 text-green-800 text-sm font-semibold px-4 py-2 rounded text-center cursor-not-allowed">
                                        Pembayaran Selesai
                                    </div>
                                <?php endif; ?>
                            </div>
                          </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="col-span-3 text-center text-gray-500 text-lg">Anda tidak memiliki reservasi yang akan datang.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
    <?php
      require_once '../includes/footer.php';
    ?>

    <div id="reservationDetailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="p-6 border-b flex justify-between items-center">
                <h2 id="detailModalTitle" class="text-xl font-bold">Detail Reservasi</h2>
                <button onclick="closeReservationDetailModal()" class="text-2xl font-bold hover:text-red-600">&times;</button>
            </div>
            <div class="p-6 space-y-2">
                <p><strong>Nama Pemesan:</strong> <span id="detail_name"></span></p>
                <p><strong>Email:</strong> <span id="detail_email"></span></p>
                <hr class="my-2">
                <p><strong>Workspace:</strong> <span id="detail_workspace_name"></span></p>
                <p><strong>Jenis / Meja:</strong> <span id="detail_workspace_type"></span> / Meja <span id="detail_desk_number"></span></p>
                <p><strong>Lokasi:</strong> <span id="detail_location"></span></p>
                <hr class="my-2">
                <p><strong>Tanggal:</strong> <span id="detail_date"></span></p>
                <p><strong>Waktu:</strong> <span id="detail_time"></span></p>
                <p><strong>Durasi:</strong> <span id="detail_duration"></span></p>
                <div class="mt-4 pt-4 border-t">
                    <p class="text-lg font-bold">Total Biaya: <span id="detail_total_price" class="text-teal-600"></span></p>
                    <p class="font-bold">Status Pembayaran: <span id="detail_payment_status"></span></p>
                    <p class="text-sm text-gray-500 mt-2">Pembayaran dapat dilakukan ke nomor berikut: 08123456789</p>
                </div>
            </div>
            <div class="p-6 border-t flex justify-end">
                <button onclick="closeReservationDetailModal()" class="px-6 py-2 rounded bg-gray-200 hover:bg-gray-300">Tutup</button>
            </div>
        </div>
    </div>

  </body>

  <script>
    const detailModal = document.getElementById('reservationDetailModal');

    function formatDate(dateString) {
        const options = { year: 'numeric', month: 'short', day: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }

    function formatTime(timeString) {
        return timeString.substring(0, 5);
    }

    function openReservationDetailModal(data) {
        const startTime = new Date(`1970-01-01T${data.start_time}`);
        const endTime = new Date(`1970-01-01T${data.end_time}`);
        let differenceMs = endTime - startTime;
        if (differenceMs < 0) {
            differenceMs += 24 * 60 * 60 * 1000; // Handle overnight
        }
        const durationHours = differenceMs / (1000 * 60 * 60);

        document.getElementById('detail_name').textContent = data.name;
        document.getElementById('detail_email').textContent = data.email;
        document.getElementById('detail_workspace_name').textContent = data.workspace_name;
        document.getElementById('detail_workspace_type').textContent = data.workspace_type;
        document.getElementById('detail_desk_number').textContent = data.desk_number;
        document.getElementById('detail_location').textContent = data.location;
        document.getElementById('detail_date').textContent = formatDate(data.start_date);
        document.getElementById('detail_time').textContent = `${formatTime(data.start_time)} - ${formatTime(data.end_time)}`;
        document.getElementById('detail_duration').textContent = `${durationHours.toFixed(1)} jam`;
        document.getElementById('detail_total_price').textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(data.total_price || 0);
        
        const statusElement = document.getElementById('detail_payment_status');
        const statusText = data.transaction_status || 'Tertunda';
        statusElement.textContent = statusText;
        if (statusText === 'Selesai') {
            statusElement.className = 'font-bold text-green-600';
        } else {
            statusElement.className = 'font-bold text-yellow-600';
        }
        
        detailModal.classList.remove('hidden');
    }

    function closeReservationDetailModal() {
        detailModal.classList.add('hidden');
    }

    window.onclick = function(event) {
        if (event.target == detailModal) {
            closeReservationDetailModal();
        }
    }
  </script>
</html>
