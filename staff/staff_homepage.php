<?php
require_once '../db.php'; 

//hanya staff atau manager yang bisa mengakses
if (!isset($_SESSION['loggedin']) || !in_array($_SESSION['role'], ['Staff', 'Manager'])) {
    header("Location: ../login.php");
    exit;
}

// Menangani aksi dari modal (Tambah/Edit Workspace)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    try {
        if ($action === 'create' || $action === 'update') {
            
            $image_path = $_POST['existing_image_path'] ?? 'assets/default.jpg'; 

            if (isset($_FILES['gambar_workspace']) && $_FILES['gambar_workspace']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['gambar_workspace'];
                $uploadDir = '../assets/'; 
                
                $fileName = uniqid() . '-' . basename($file['name']);
                $targetPath = $uploadDir . $fileName;

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($file['type'], $allowedTypes)) {
                    throw new Exception("Error: Tipe file tidak diizinkan. Harap unggah file gambar (jpg, png, gif, webp).");
                }
                if ($file['size'] > 5000000) { 
                    throw new Exception("Error: Ukuran file terlalu besar. Maksimal 5MB.");
                }

                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    if ($action === 'update' && !empty($_POST['existing_image_path']) && $_POST['existing_image_path'] !== 'assets/default.jpg') {
                        $oldImagePath = '../' . $_POST['existing_image_path'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                    $image_path = 'assets/' . $fileName; 
                } else {
                    throw new Exception("Gagal memindahkan file yang diunggah. Pastikan folder 'assets' dapat ditulisi.");
                }
            }

            $params = [
                ':name' => $_POST['nama_workspace'],
                ':description' => $_POST['deskripsi'],
                ':location' => $_POST['alamat'],
                ':tipe' => $_POST['tipe_workspace'],
                ':kapasitas' => $_POST['kapasitas'],
                ':status' => $_POST['status'],
                ':fasilitas' => json_encode($_POST['fasilitas'] ?? []),
                ':image_path' => $image_path
            ];
            
            if ($action === 'create') {
                $sql = "INSERT INTO workspaces (name, description, location, tipe, kapasitas, status, fasilitas, image_path, price, duration_unit) 
                        VALUES (:name, :description, :location, :tipe, :kapasitas, :status, :fasilitas, :image_path, 9999999, 'hour')";
            } else { 
                $sql = "UPDATE workspaces SET name = :name, description = :description, location = :location, tipe = :tipe, kapasitas = :kapasitas, status = :status, fasilitas = :fasilitas, image_path = :image_path WHERE id = :id";
                $params[':id'] = $_POST['workspace_id'];
            }

            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

        } elseif ($action === 'delete') {
            $stmt_get = $conn->prepare("SELECT image_path FROM workspaces WHERE id = :id");
            $stmt_get->execute([':id' => $_POST['workspace_id']]);
            $ws_to_delete = $stmt_get->fetch(PDO::FETCH_ASSOC);

            if ($ws_to_delete && $ws_to_delete['image_path'] !== 'assets/default.jpg') {
                $filePath = '../' . $ws_to_delete['image_path'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $sql = "DELETE FROM workspaces WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([':id' => $_POST['workspace_id']]);
        }
        header("Location: " . $_SERVER['PHP_SELF'] . "?status=success&action=" . $action);
        exit;
    } catch (Exception $e) {
        die("Terjadi kesalahan: " . $e->getMessage());
    }
}


$search_query = $_GET['search'] ?? '';
$filter_type = $_GET['filter_type'] ?? '';

$sql = "SELECT * FROM bookings";
$conditions = [];
$params = [];

if (!empty($filter_type)) {
    $conditions[] = "workspace_type = :filter_type";
    $params[':filter_type'] = $filter_type;
}

if (!empty($search_query)) {
    $conditions[] = "(name LIKE :query OR email LIKE :query OR workspace_name LIKE :query)";
    $params[':query'] = '%' . $search_query . '%';
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

// Mengambil data untuk daftar workspace
try {
    $workspace_sql = "SELECT * FROM workspaces ORDER BY FIELD(status, 'Aktif', 'Maintenance', 'Tidak Aktif'), id DESC";
    $workspace_stmt = $conn->prepare($workspace_sql);
    $workspace_stmt->execute();
    $workspaces = $workspace_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Mengambil daftar unik jenis workspace untuk dropdown filter
    $type_stmt = $conn->query("SELECT DISTINCT tipe FROM workspaces ORDER BY tipe ASC");
    $workspace_types = $type_stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    $workspaces = [];
    $workspace_types = [];
    $workspace_error = "Gagal memuat data workspace: " . $e->getMessage();
}

// Daftar fasilitas untuk modal
$fasilitas_list = ["WiFi", "AC", "Proyektor", "Whiteboard", "Printer", "Coffee/Tea", "Stopkontak"];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard</title>
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
            $active_page = 'Home'; 
            require_once '../includes/header_staff.php'; 
        ?>

        <!-- Hero Section Utama -->
        <div class="bg-custom-gray text-white">
          <section class="px-6 py-12 max-w-7xl mx-auto">
            <h1 class="text-4xl font-bold">Staff Dashboard</h1>
            <p class="text-gray-300 mt-2">Lihat data reservasi terbaru dan kelola daftar workspace.</p>
          </section>
        </div>
        
        <!-- Bagian Tabel Reservasi -->
        <div class="bg-white py-10">
            <main class="container mx-auto px-6">
                <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Reservasi Terbaru</h2>
                    <form method="GET" action="" class="flex flex-wrap items-center gap-4">
                        <div>
                            <select name="filter_type" onchange="this.form.submit()" class="border rounded-md py-2 px-3 focus:outline-none focus:ring-2 focus:ring-teal-500 text-sm">
                                <option value="">Semua Jenis</option>
                                <?php foreach($workspace_types as $type): ?>
                                    <option value="<?= htmlspecialchars($type) ?>" <?= ($filter_type === $type) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($type) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="relative">
                            <input type="text" name="search" placeholder="Cari nama, email, ws..." 
                                   class="border rounded-full py-2 px-4 w-56 focus:outline-none focus:ring-2 focus:ring-teal-500"
                                   value="<?= htmlspecialchars($search_query) ?>">
                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-teal-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <a href="staff_homepage.php" class="text-sm text-gray-500 hover:text-black">Reset</a>
                    </form>
                </div>

                <div class="overflow-x-auto shadow-md sm:rounded-lg">
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
                        </tbody>
                    </table>
                </div>
            </main>
        </div>

        <!-- Bagian Mengatur Workspace -->
        <section>
            <div class="bg-teal-900 text-white">
                <div class="container mx-auto px-6 py-12 max-w-7xl">
                    <h2 class="text-3xl font-bold">Manage Workspace</h2>
                    <p class="text-gray-300 mt-2">Lihat dan kelola semua ruang kerja yang terdaftar.</p>
                </div>
            </div>

            <div class="bg-white py-10">
                <main class="container mx-auto px-6">
                     <div class="flex flex-wrap justify-end mb-6 gap-4">
                        <button onclick="openModal()" class="bg-yellow-400 text-black px-4 py-2 rounded font-medium hover:bg-yellow-500 transition">
                            + Tambah Workspace
                        </button>
                        <a href="mengaturworkspace.php" class="bg-custom-gray text-white px-5 py-2 rounded-lg font-semibold shadow-md hover:opacity-90 transition-opacity duration-300">
                            Kelola Semua Workspace →
                        </a>
                    </div>

                    <?php if (isset($workspace_error)): ?>
                        <p class="text-red-500 text-center"><?= htmlspecialchars($workspace_error); ?></p>
                    <?php elseif (empty($workspaces)): ?>
                        <p class="text-gray-500 text-center">Belum ada workspace yang ditambahkan.</p>
                    <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach (array_slice($workspaces, 0, 3) as $ws): ?>
                            <?php
                                $status_class = '';
                                switch ($ws['status']) {
                                    case 'Aktif':
                                        $status_class = 'border-l-4 border-teal-400 shadow-lg';
                                        break;
                                    case 'Maintenance':
                                        $status_class = 'border-l-4 border-yellow-400 shadow-lg';
                                        break;
                                    case 'Tidak Aktif':
                                        $status_class = 'border-l-4 border-gray-300 shadow-md opacity-70';
                                        break;
                                }
                            ?>
                            <div class="rounded-lg overflow-hidden bg-white flex flex-col <?= $status_class ?>">
                                <img src="../<?= htmlspecialchars($ws['image_path']) ?>" alt="Gambar <?= htmlspecialchars($ws['name']) ?>" class="w-full h-48 object-cover">
                                <div class="p-4 flex flex-col flex-grow">
                                    <h3 class="text-xl font-semibold"><?= htmlspecialchars($ws['name']) ?></h3>
                                    <p class="text-sm text-gray-500 mt-1">Status: <span class="font-bold"><?= htmlspecialchars($ws['status']) ?></span></p>
                                    <p class="text-gray-600 text-sm mt-1">📍 <?= htmlspecialchars($ws['location']) ?></p>
                                    <div class="mt-auto pt-4 flex justify-between items-center">
                                        <button onclick='openModal(<?= json_encode($ws, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="border border-gray-300 text-gray-700 px-4 py-2 rounded font-medium text-sm hover:bg-gray-100">Edit</button>
                                        <button onclick='openDetailModal(<?= json_encode($ws, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' class="bg-yellow-400 text-black px-4 py-2 rounded font-medium text-sm hover:bg-yellow-500">Lihat Detail</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </main>
            </div>
        </section>
    </div>

    <!-- Modal Tambah/Edit Workspace -->
    <div id="workspaceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">
            <div class="p-6 border-b">
                <h2 id="modalTitle" class="text-xl font-bold">Tambah Workspace</h2>
            </div>
            <form id="workspaceForm" method="POST" action="" class="p-6 space-y-4 overflow-y-auto" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction">
                <input type="hidden" name="workspace_id" id="workspace_id">
                <input type="hidden" name="existing_image_path" id="existing_image_path">
                
                <div><label for="nama_workspace" class="block text-sm font-medium">Nama Workspace*</label><input type="text" name="nama_workspace" id="nama_workspace" class="w-full border rounded p-2 mt-1" required></div>
                <div><label for="alamat" class="block text-sm font-medium">Alamat*</label><input type="text" name="alamat" id="alamat" class="w-full border rounded p-2 mt-1" required></div>
                <div>
                    <label for="tipe_workspace" class="block text-sm font-medium">Tipe Workspace*</label>
                    <select name="tipe_workspace" id="tipe_workspace" class="w-full border rounded p-2 mt-1" required>
                        <option value="Individual Desk">Individual Desk</option>
                        <option value="Group Desk">Group Desk</option>
                        <option value="Meeting Room">Meeting Room</option>
                        <option value="Private Office">Private Office</option>
                    </select>
                </div>
                <div><label for="kapasitas" class="block text-sm font-medium">Kapasitas (Orang)*</label><input type="number" name="kapasitas" id="kapasitas" class="w-full border rounded p-2 mt-1" required></div>
                <div><label for="deskripsi" class="block text-sm font-medium">Deskripsi*</label><textarea name="deskripsi" id="deskripsi" class="w-full border rounded p-2 h-24 mt-1" required></textarea></div>
                
                <div>
                    <label for="gambar_workspace" class="block text-sm font-medium">Gambar Workspace</label>
                    <input type="file" name="gambar_workspace" id="gambar_workspace" class="w-full border rounded p-2 mt-1 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100" accept="image/*">
                    <small class="text-gray-500">Hanya file gambar. Kosongkan jika tidak ingin mengubah gambar.</small>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Fasilitas</label>
                    <div id="fasilitas-checkboxes" class="grid grid-cols-2 md:grid-cols-3 gap-2">
                        <?php foreach($fasilitas_list as $fasilitas): ?>
                        <label class="inline-flex items-center"><input type="checkbox" name="fasilitas[]" value="<?= $fasilitas ?>" class="mr-2 form-checkbox h-5 w-5 text-yellow-500"><?= $fasilitas ?></label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium">Status</label>
                    <select name="status" id="status" class="w-full border rounded p-2 mt-1">
                        <option value="Aktif">Aktif</option><option value="Tidak Aktif">Tidak Aktif</option><option value="Maintenance">Maintenance</option>
                    </select>
                </div>
            </form>
            <div class="p-6 border-t mt-auto flex justify-end gap-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">Batal</button>
                <button type="submit" form="workspaceForm" class="px-4 py-2 rounded bg-yellow-400 text-black hover:bg-yellow-500">Simpan</button>
            </div>
        </div>
    </div>

    <!-- Modal Detail Workspace -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="p-6 border-b flex justify-between items-center">
                <h2 id="detailModalTitle" class="text-xl font-bold">Detail Workspace</h2>
                <button onclick="closeDetailModal()" class="text-2xl font-bold hover:text-red-600">&times;</button>
            </div>
            <div class="p-6 space-y-2">
                <p><strong>Lokasi:</strong> <span id="detail_lokasi"></span></p>
                <p><strong>Tipe:</strong> <span id="detail_tipe"></span></p>
                <p><strong>Kapasitas:</strong> <span id="detail_kapasitas"></span> orang</p>
                <p><strong>Deskripsi:</strong> <span id="detail_deskripsi"></span></p>
                <p><strong>Fasilitas:</strong> <span id="detail_fasilitas" class="font-medium text-teal-800"></span></p>
                <p><strong>Status:</strong> <span id="detail_status" class="font-bold"></span></p>
            </div>
            <div class="p-6 border-t mt-auto flex justify-end gap-2">
                 <form id="deleteForm" method="POST" action="mengaturworkspace.php" onsubmit="return confirm('Anda yakin ingin menghapus workspace ini secara permanen? Tindakan ini tidak dapat diurungkan.');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="workspace_id" id="delete_workspace_id">
                    <button type="submit" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
                </form>
            </div>
        </div>
    </div>

    <?php require_once '../includes/footer.php'; ?>
    
    <script>
        const modal = document.getElementById('workspaceModal');
        const detailModal = document.getElementById('detailModal');
        const form = document.getElementById('workspaceForm');
        const modalTitle = document.getElementById('modalTitle');
        
        function openModal(data = null) {
            form.reset();
            document.querySelectorAll('#fasilitas-checkboxes input').forEach(cb => cb.checked = false);
            document.getElementById('gambar_workspace').value = ''; 

            if (data) { // Mode Edit
                modalTitle.textContent = 'Edit Workspace';
                form.action.value = 'update';
                form.workspace_id.value = data.id;
                form.existing_image_path.value = data.image_path; 
                form.nama_workspace.value = data.name;
                form.deskripsi.value = data.description;
                form.alamat.value = data.location;
                form.tipe_workspace.value = data.tipe;
                form.kapasitas.value = data.kapasitas;
                form.status.value = data.status;
                if (data.fasilitas) {
                    try {
                        const fasilitasData = JSON.parse(data.fasilitas);
                        if(Array.isArray(fasilitasData)) {
                            fasilitasData.forEach(f => {
                                const checkbox = document.querySelector(`#fasilitas-checkboxes input[value="${f}"]`);
                                if (checkbox) checkbox.checked = true;
                            });
                        }
                    } catch(e) { console.error("Error parsing fasilitas JSON:", e); }
                }
            } else { // Mode Tambah Baru
                modalTitle.textContent = 'Tambah Workspace Baru';
                form.action.value = 'create';
                form.workspace_id.value = '';
                form.existing_image_path.value = '';
            }
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
        }

        function openDetailModal(data) {
            document.getElementById('detailModalTitle').textContent = data.name;
            document.getElementById('detail_lokasi').textContent = data.location;
            document.getElementById('detail_tipe').textContent = data.tipe;
            document.getElementById('detail_kapasitas').textContent = data.kapasitas;
            document.getElementById('detail_deskripsi').textContent = data.description;
            document.getElementById('detail_status').textContent = data.status;
            document.getElementById('delete_workspace_id').value = data.id;
            
            let fasilitasText = 'Tidak ada fasilitas terdaftar';
            if (data.fasilitas) {
                try {
                    const fasilitasData = JSON.parse(data.fasilitas);
                    if(Array.isArray(fasilitasData) && fasilitasData.length > 0) {
                       fasilitasText = fasilitasData.join(', ');
                    }
                } catch(e) {}
            }
            document.getElementById('detail_fasilitas').textContent = fasilitasText;
            
            detailModal.classList.remove('hidden');
        }

        function closeDetailModal() {
            detailModal.classList.add('hidden');
        }

        window.onclick = function(event) {
            if (event.target == modal) closeModal();
            if (event.target == detailModal) closeDetailModal();
        }
    </script>
</body>
</html>
