<?php
require_once '../db.php'; 

//hanya staff atau manager yang bisa mengakses
if (!isset($_SESSION['loggedin']) || !in_array($_SESSION['role'], ['Staff', 'Manager'])) {
    header("Location: ../login.php");
    exit;
}

// Inisialisasi variabel filter dari GET
$search = $_GET['search'] ?? '';
$code = $_GET['code'] ?? '';
$name = $_GET['name'] ?? '';
$email = $_GET['email'] ?? '';
$workspace = $_GET['workspace'] ?? '';
$location = $_GET['location'] ?? '';
$desk_number = $_GET['desk_number'] ?? '';
$date = $_GET['date'] ?? '';
$start_time = $_GET['start_time'] ?? '';
$finish_time = $_GET['finish_time'] ?? '';

// Query filter
$sql = "SELECT * FROM bookings";
$conditions = [];
$params = [];

if (!empty($code)) {
    $conditions[] = "id = :code";
    $params[':code'] = $code;
}
if (!empty($name)) {
    $conditions[] = "name LIKE :name";
    $params[':name'] = "%$name%";
}
if (!empty($email)) {
    $conditions[] = "email LIKE :email";
    $params[':email'] = "%$email%";
}
if (!empty($workspace)) {
    $conditions[] = "workspace_name = :workspace";
    $params[':workspace'] = $workspace;
}
if (!empty($location)) {
    $conditions[] = "location = :location";
    $params[':location'] = $location;
}
if (!empty($desk_number)) {
    $conditions[] = "desk_number = :desk_number";
    $params[':desk_number'] = $desk_number;
}
if (!empty($date)) {
    $conditions[] = "start_date = :date";
    $params[':date'] = $date;
}
if (!empty($start_time)) {
    $conditions[] = "start_time >= :start_time";
    $params[':start_time'] = $start_time;
}
if (!empty($finish_time)) {
    $conditions[] = "end_time <= :finish_time";
    $params[':finish_time'] = $finish_time;
}
if (!empty($search)) {
    $conditions[] = "(name LIKE :search OR email LIKE :search OR workspace_name LIKE :search OR start_date LIKE :search)";
    $params[':search'] = "%$search%";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
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

// Ambil workspace_name unik untuk filter drawer
try {
    $ws_stmt = $conn->query("SELECT DISTINCT workspace_name FROM bookings ORDER BY workspace_name ASC");
    $workspaces = $ws_stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $workspaces = [];
}
// Ambil lokasi unik untuk filter drawer
try {
    $loc_stmt = $conn->query("SELECT DISTINCT location FROM bookings ORDER BY location ASC");
    $locations = $loc_stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $locations = [];
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
            $active_page = 'Reservations';
            require_once '../includes/header_staff.php'; 
        ?>

        <div class="bg-custom-gray text-white">
          <section class="px-6 py-12 max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold">Daftar Reservasi</h1>
            <p class="text-gray-300 mt-2">Lihat dan kelola semua data reservasi yang masuk.</p>
          </section>
        </div>
        <?php include 'staff_daftarreservasi_filter.php'; ?>
        <main class="container mx-auto p-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold text-gray-800">Semua Reservasi</h2>
                    <div class="flex items-center gap-4">
                        <button type="button" id="openDrawerBtn"
                            class="flex items-center space-x-2 bg-teal-900 text-white px-4 py-2 rounded shadow hover:bg-teal-800 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h18M3 12h18M3 19h18"/>
                            </svg>
                            <span>Filter</span>
                        </button>
                        <a href="staff_daftarreservasi.php" class="text-sm text-gray-500 hover:text-black">Reset</a>
                    </div>
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
                                        Tidak ada data reservasi yang cocok dengan kriteria filter.
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
