<?php
require_once '../db.php'; 

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['id'])) {
    header("Location: ../login.php");
    exit;
}
$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_to_cancel'])) {
    $reservation_id = $_POST['id_to_cancel'];
    try {
        $stmt = $conn->prepare("DELETE FROM bookings WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $reservation_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        header("Location: membatalkanreservasi.php?status=cancelled");
        exit;
    } catch (PDOException $e) {
        die("Error saat membatalkan reservasi: " . $e->getMessage());
    }
}

try {
    $stmt = $conn->prepare("SELECT * FROM bookings WHERE user_id = :user_id ORDER BY start_date DESC, start_time ASC");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $reservations = [];
    $error_message = "Gagal memuat data reservasi: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Membatalkan Reservasi</title>
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

        <!-- Hero Section -->
        <div class="bg-custom-gray">
          <section class="px-6 py-16 max-w-7xl mx-auto text-white">
            <p class="text-yellow-400 uppercase text-sm tracking-widest">— My Reservations</p>
            <h1 class="text-5xl font-bold mt-4 leading-tight">Manage Reservations</h1>
            <p class="text-gray-300 mt-2 max-w-2xl">Lihat dan kelola semua reservasi ruang kerja Anda di sini.</p>
          </section>
        </div>

        <!-- Main Content -->
        <main class="container mx-auto p-8">
            <!-- Notifikasi sukses -->
            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Booking Berhasil!</p>
                    <p>Reservasi Anda telah berhasil disimpan.</p>
                </div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] == 'cancelled'): ?>
                <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>Reservasi Anda telah berhasil dibatalkan.</p>
                </div>
            <?php endif; ?>

            <!-- Kontainer untuk kartu-kartu reservasi -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (!empty($reservations)): ?>
                    <?php foreach ($reservations as $row):
                        $image_map = [
                            'Individual' => 'assets/individualdesk.jpeg',
                            'Group' => 'assets/groupdesk.jpeg',
                            'Meeting Room' => 'assets/meetingroom.jpg',
                            'Private Office' => 'assets/privateoffice.jpeg'
                        ];
                        $image_path = $image_map[$row['workspace']] ?? 'https://placehold.co/400x200?text=Workspace';
                    ?>
                        <div class="bg-white border rounded-lg shadow-md hover:shadow-lg transition-shadow flex flex-col">
                          <img src="../<?php echo htmlspecialchars($image_path); ?>" class="rounded-t-lg h-48 w-full object-cover" alt="<?php echo htmlspecialchars($row['workspace']); ?>">
                          <div class="p-5 flex flex-col flex-grow">
                            <p class="text-sm text-gray-500">Dipesan oleh: <strong><?= htmlspecialchars($row['name']) ?></strong></p>
                            <h3 class="text-lg font-semibold mb-1 mt-1"><?php echo htmlspecialchars($row['workspace']); ?> Desk <?php echo htmlspecialchars($row['desk_number']); ?></h3>
                            <p class="text-sm text-gray-600 mb-2">
                              Tanggal: <strong><?php echo date('d M Y', strtotime($row['start_date'])); ?></strong>
                            </p>
                            <p class="text-sm text-gray-600 mb-4">
                              Waktu: <strong><?php echo date('H:i', strtotime($row['start_time'])); ?> - <?php echo date('H:i', strtotime($row['end_time'])); ?></strong>
                            </p>
                            <form method="POST" action="membatalkanreservasi.php" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?');" class="mt-auto">
                                <input type="hidden" name="id_to_cancel" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" class="w-full bg-red-500 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-red-600 transition">
                                    Batalkan Reservasi
                                </button>
                            </form>
                          </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="col-span-3 text-center text-gray-500 text-lg">Anda belum memiliki reservasi aktif.</p>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <p class="col-span-3 text-center text-red-500"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
            </div>
        </main>
    </div>
  <?php
    require_once '../includes/footer.php';
  ?>
  </body>
</html>
