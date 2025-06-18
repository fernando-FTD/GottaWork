<?php
require_once '../db.php'; 

//hanya staff atau manager yang bisa mengakses
if (!isset($_SESSION['loggedin']) || !in_array($_SESSION['role'], ['Staff', 'Manager'])) {
    header("Location: ../login.php");
    exit;
}

// Logika untuk pencarian
$search_query = $_GET['search'] ?? '';
$sql = "SELECT * FROM bookings";
$params = [];

if (!empty($search_query)) {
    // Mencari di beberapa kolom
    $sql .= " WHERE name LIKE :query OR email LIKE :query OR workspace_name LIKE :query";
    $params[':query'] = '%' . $search_query . '%';
}

$sql .= " ORDER BY created_at DESC";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $bookings = [];
    $error_message = "Gagal memuat data booking: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation List - Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
      .bg-custom-gray { background-color: #095151; }
      body { font-family: 'Lora', serif; }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

    <div class="flex-grow">
        <?php 
            // Mengatur halaman aktif untuk navigasi header
            $active_page = 'reservations';
            require_once '../includes/header_staff.php'; 
        ?>

        <!-- Hero Section untuk halaman Daftar Reservasi -->
        <div class="bg-custom-gray text-white">
          <section class="px-6 py-12 max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold">Daftar Reservasi</h1>
            <p class="text-gray-300 mt-2">Lihat dan kelola semua data reservasi yang masuk.</p>
          </section>
        </div>

        <main class="container mx-auto p-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Semua Reservasi</h2>
                    <form method="GET" action="reservation_list.php">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Cari nama, email, workspace..." 
                                   class="border rounded-full py-2 px-4 w-64 focus:outline-none focus:ring-2 focus:ring-teal-500"
                                   value="<?= htmlspecialchars($search_query) ?>">
                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-teal-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Tabel Reservasi -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Nama Pemesan</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Workspace</th>
                                <th scope="col" class="px-6 py-3 text-center">Meja</th>
                                <th scope="col" class="px-6 py-3">Tanggal</th>
                                <th scope="col" class="px-6 py-3">Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($bookings)): ?>
                                <?php foreach ($bookings as $row): ?>
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($row['id']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($row['name']) ?></td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($row['email']) ?></td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold"><?= htmlspecialchars($row['workspace_name'] ?? 'N/A') ?></div>
                                            <div class="text-xs text-gray-500"><?= htmlspecialchars($row['workspace_type'] ?? 'N/A') ?></div>
                                            <div class="text-xs text-gray-500">📍 <?= htmlspecialchars($row['location'] ?? 'N/A') ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-center"><?= htmlspecialchars($row['desk_number']) ?></td>
                                        <td class="px-6 py-4"><?= date('d M Y', strtotime($row['start_date'])) ?></td>
                                        <td class="px-6 py-4"><?= date('H:i', strtotime($row['start_time'])) ?> - <?= date('H:i', strtotime($row['end_time'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        Tidak ada data reservasi yang ditemukan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <?php if (isset($error_message)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-red-500">
                                        <?= htmlspecialchars($error_message); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <?php require_once '../includes/footer.php'; ?>
</body>
</html>
