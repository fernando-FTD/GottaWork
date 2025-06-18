<?php
require_once '../db.php';

// Menentukan tipe halaman (income/pendapatan atau expense/pengeluaran)
$type = $_GET['type'] ?? 'income'; 

// Konfigurasi dinamis berdasarkan tipe halaman
if ($type === 'income') {
    $tableName = 'transactions';
    $pageTitle = 'Pendapatan Perusahaan';
    $pageSubtitle = 'Pendapatan';
    $formTitle = 'Tambah Transaksi Baru';
    $categories = ['Individual Desk', 'Group Desk', 'Meeting Room', 'Private Office', 'Layanan Tambahan'];
} else {
    $tableName = 'expenses';
    $pageTitle = 'Pengeluaran Perusahaan';
    $pageSubtitle = 'Pengeluaran';
    $formTitle = 'Tambah Pengeluaran Baru';
    $categories = ['Operasional', 'Maintenance', 'Utilitas', 'Marketing', 'Gaji', 'Lainnya'];
}

// ---- KELAS GENERIC UNTUK TRANSAKSI ----
class Transaction {
    private $conn;
    private $table_name;

    public function __construct($db, $tableName) {
        $this->conn = $db;
        $this->table_name = $tableName;
    }

    public function getAll($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY tanggal DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " (tanggal, deskripsi, kategori, jumlah, status, booking_id) VALUES (:tanggal, :deskripsi, :kategori, :jumlah, :status, :booking_id)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ":tanggal" => $data['tanggal'],
            ":deskripsi" => $data['deskripsi'],
            ":kategori" => $data['kategori'],
            ":jumlah" => $data['jumlah'],
            ":status" => $data['status'],
            ":booking_id" => $data['booking_id'] ?? null
        ]);
    }

    public function update($data) {
        $query = "UPDATE " . $this->table_name . " SET tanggal=:tanggal, deskripsi=:deskripsi, kategori=:kategori, jumlah=:jumlah, status=:status WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            ":tanggal" => $data['tanggal'],
            ":deskripsi" => $data['deskripsi'],
            ":kategori" => $data['kategori'],
            ":jumlah" => $data['jumlah'],
            ":status" => $data['status'],
            ":id" => $data['id']
        ]);
    }
}

// ---- FUNGSI UNTUK STATISTIK KEUANGAN ----
function getFinancialStats($db) {
    try {
        $income_query = "SELECT SUM(jumlah) as total FROM transactions WHERE status = 'Selesai'";
        $expense_query = "SELECT SUM(jumlah) as total FROM expenses WHERE status = 'Selesai'";

        $income_stmt = $db->query($income_query);
        $total_pendapatan = $income_stmt->fetchColumn() ?? 0;

        $expense_stmt = $db->query($expense_query);
        $total_pengeluaran = $expense_stmt->fetchColumn() ?? 0;
        
        $total_transaksi_pendapatan = $db->query("SELECT COUNT(*) FROM transactions")->fetchColumn();
        $total_transaksi_pengeluaran = $db->query("SELECT COUNT(*) FROM expenses")->fetchColumn();

        return [
            'total_pendapatan' => $total_pendapatan,
            'total_pengeluaran' => $total_pengeluaran,
            'keuntungan_bersih' => $total_pendapatan - $total_pengeluaran,
            'total_transaksi' => $total_transaksi_pendapatan + $total_transaksi_pengeluaran
        ];
    } catch (Exception $e) {
        return ['total_pendapatan' => 0, 'total_pengeluaran' => 0, 'keuntungan_bersih' => 0, 'total_transaksi' => 0];
    }
}

// ---- PENANGANAN REQUEST AJAX ----
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    try {
        $action_type = $_POST['type'] ?? 'income';
        $currentTable = ($action_type === 'income') ? 'transactions' : 'expenses';
        
        $transactionHandler = new Transaction($conn, $currentTable);
        $action = $_POST['action'];

        $result = false;
        if ($action === 'create' || $action === 'update') {
            $data = [
                'id' => $_POST['id'] ?? null,
                'tanggal' => $_POST['tanggal'],
                'deskripsi' => $_POST['deskripsi'],
                'kategori' => $_POST['kategori'],
                'jumlah' => str_replace(',', '', $_POST['jumlah']),
                'status' => $_POST['status']
            ];
            $result = ($action === 'create') ? $transactionHandler->create($data) : $transactionHandler->update($data);
        } elseif ($action === 'delete') {
            $transaction_id = $_POST['id'];

            if ($action_type === 'income') {
                $conn->beginTransaction();
                try {
                    // 1. Ambil booking_id dari transaksi yang akan dihapus
                    $stmt_get_booking = $conn->prepare("SELECT booking_id FROM transactions WHERE id = :id");
                    $stmt_get_booking->execute([':id' => $transaction_id]);
                    $stmt_get_booking->setFetchMode(PDO::FETCH_ASSOC); 
                    $booking = $stmt_get_booking->fetch();

                    // 2. Jika ada booking_id yang terkait, hapus booking-nya
                    if ($booking && !empty($booking['booking_id'])) {
                        $stmt_delete_booking = $conn->prepare("DELETE FROM bookings WHERE id = :booking_id");
                        $stmt_delete_booking->execute([':booking_id' => $booking['booking_id']]);
                    }

                    // 3. Hapus transaksi itu sendiri
                    $stmt_delete_trans = $conn->prepare("DELETE FROM transactions WHERE id = :id");
                    $stmt_delete_trans->execute([':id' => $transaction_id]);

                    $conn->commit();
                    $result = true;

                } catch (Exception $e) {
                    $conn->rollBack();
                    throw $e; // Lempar kembali error untuk ditangani
                }
            } else {
                // Untuk pengeluaran, hapus seperti biasa
                $stmt_delete_expense = $conn->prepare("DELETE FROM expenses WHERE id = :id");
                $result = $stmt_delete_expense->execute([':id' => $transaction_id]);
            }
        }

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Operasi berhasil!']);
        } else {
            throw new Exception("Gagal melakukan operasi database.");
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// ---- PERSIAPAN DATA UNTUK TAMPILAN HALAMAN ----
$transaction = new Transaction($conn, $tableName);

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$total_records = $transaction->getTotalCount();
$total_pages = ceil($total_records / $limit);

$stmt = $transaction->getAll($page, $limit);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$financial_stats = getFinancialStats($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GottaWork - <?= htmlspecialchars($pageSubtitle) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Lora', serif; }
    .bg-custom-green { background-color: #095151; }
  </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-100">

  <?php
    $active_page = 'finance';
    require_once '../includes/header_manager.php';
  ?>
  
  <div class="bg-custom-green text-white">
    <div class="container mx-auto px-8 py-12">
        <div class="flex items-center text-yellow-400 mb-2">
            <i class="fas fa-chevron-down mr-1 text-xs"></i>
            <span class="ml-1 text-sm">Finance</span>
        </div>
        <h1 class="text-3xl font-bold mb-6 text-white"><?= htmlspecialchars($pageTitle) ?></h1>
        
        <div class="flex justify-between items-center">
            <div class="flex space-x-4">
                <a href="?type=income" class="<?= $type === 'income' ? 'bg-yellow-400 text-black' : 'border border-yellow-400 text-yellow-400' ?> px-6 py-3 rounded font-medium flex items-center transition">Pendapatan</a>
                <a href="?type=expense" class="<?= $type === 'expense' ? 'bg-yellow-400 text-black' : 'border border-yellow-400 text-yellow-400' ?> px-6 py-3 rounded font-medium flex items-center transition">Pengeluaran</a>
            </div>
            <div class="flex items-center text-sm text-white">
                <span>Home</span><i class="fas fa-chevron-right mx-1 text-xs"></i><span>Finance</span>
            </div>
        </div>
    </div>
  </div>
  
  <main class="container mx-auto px-6 py-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
          <div class="bg-white p-4 rounded shadow"><h3 class="text-gray-600 text-sm">Total Pendapatan</h3><p class="text-xl font-bold">Rp <?= number_format($financial_stats['total_pendapatan'], 0, ',', '.') ?></p></div>
          <div class="bg-white p-4 rounded shadow"><h3 class="text-gray-600 text-sm">Total Pengeluaran</h3><p class="text-xl font-bold">Rp <?= number_format($financial_stats['total_pengeluaran'], 0, ',', '.') ?></p></div>
          <div class="bg-white p-4 rounded shadow"><h3 class="text-gray-600 text-sm">Keuntungan Bersih</h3><p class="text-xl font-bold">Rp <?= number_format($financial_stats['keuntungan_bersih'], 0, ',', '.') ?></p></div>
          <div class="bg-white p-4 rounded shadow"><h3 class="text-gray-600 text-sm">Total Transaksi</h3><p class="text-xl font-bold"><?= $financial_stats['total_transaksi'] ?></p></div>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Rincian <?= htmlspecialchars($pageSubtitle) ?></h2>
          <button id="btnTambah" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center"><i class="fas fa-plus mr-2"></i>Tambah Data</button>
        </div>
        
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="text-left text-gray-600">
                <th class="pb-3">Tanggal</th><th class="pb-3">Deskripsi</th><th class="pb-3">Kategori</th><th class="pb-3">Jumlah (Rp)</th><th class="pb-3">Status</th><th class="pb-3">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($transactions as $row): ?>
              <tr class="border-t">
                <td class="py-3"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                <td class="py-3"><?= htmlspecialchars($row['deskripsi']) ?></td>
                <td class="py-3"><?= htmlspecialchars($row['kategori']) ?></td>
                <td class="py-3"><?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                <td class="py-3"><span class="px-2 py-1 rounded text-xs <?= $row['status'] == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>"><?= $row['status'] ?></span></td>
                <td class="py-3">
                  <button class="edit-btn text-blue-600 hover:text-blue-800 mr-2" data-item='<?= json_encode($row) ?>'><i class="fas fa-edit"></i></button>
                  <button class="delete-btn text-red-600 hover:text-red-800" data-id="<?= $row['id'] ?>" data-deskripsi="<?= htmlspecialchars($row['deskripsi']) ?>"><i class="fas fa-trash"></i></button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        
        <div class="flex justify-center mt-4">
          <nav class="flex items-center space-x-1">
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
              <a href="?type=<?= $type ?>&page=<?= $i ?>" class="w-8 h-8 flex items-center justify-center <?= $i == $page ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200' ?> rounded"><?= $i ?></a>
            <?php endfor; ?>
          </nav>
        </div>
      </div>
  </main>
  
  <?php require_once '../includes/footer.php'; ?>

  <!-- Modal Form (untuk Tambah/Edit) -->
  <div id="formModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h3 id="formTitle" class="text-xl font-bold"></h3>
        <button id="btnCloseForm" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      </div>
      <form id="transactionForm">
        <input type="hidden" name="id" id="form_id">
        <input type="hidden" name="action" id="form_action">
        <input type="hidden" name="type" value="<?= $type ?>">
        <div class="space-y-4">
          <div><label for="tanggal" class="block font-medium">Tanggal</label><input type="date" id="tanggal" name="tanggal" class="w-full px-3 py-2 border rounded" required></div>
          <div><label for="deskripsi" class="block font-medium">Deskripsi</label><input type="text" id="deskripsi" name="deskripsi" class="w-full px-3 py-2 border rounded" required></div>
          <div>
            <label for="kategori" class="block font-medium">Kategori</label>
            <select id="kategori" name="kategori" class="w-full px-3 py-2 border rounded" required>
                <option value="">Pilih Kategori</option>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat ?>"><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
          </div>
          <div><label for="jumlah" class="block font-medium">Jumlah (Rp)</label><input type="number" id="jumlah" name="jumlah" class="w-full px-3 py-2 border rounded" required></div>
          <div>
            <label for="status" class="block font-medium">Status</label>
            <select id="status" name="status" class="w-full px-3 py-2 border rounded" required><option value="Selesai">Selesai</option><option value="Tertunda">Tertunda</option></select>
          </div>
        </div>
        <div class="flex justify-end space-x-2 mt-6"><button type="button" class="btn-cancel px-4 py-2 border rounded">Batal</button><button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button></div>
      </form>
    </div>
  </div>

  <!-- Modal Konfirmasi Hapus -->
  <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
      <h3 class="text-xl font-bold mb-4">Konfirmasi Hapus</h3>
      <p class="mb-6">Apakah Anda yakin ingin menghapus transaksi "<span id="deleteItemName" class="font-bold"></span>"?</p>
      <div class="flex justify-end space-x-2"><button type="button" class="btn-cancel px-4 py-2 border rounded">Batal</button><button type="button" id="btnConfirmDelete" class="px-4 py-2 bg-red-600 text-white rounded">Hapus</button></div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const type = '<?= $type ?>';
        const formModal = document.getElementById('formModal');
        const deleteModal = document.getElementById('deleteModal');
        const form = document.getElementById('transactionForm');

        const showFormModal = (isEdit = false, data = {}) => {
            form.reset();
            document.getElementById('formTitle').textContent = isEdit ? 'Edit Data' : '<?= $formTitle ?>';
            document.getElementById('form_action').value = isEdit ? 'update' : 'create';
            if (isEdit) {
                document.getElementById('form_id').value = data.id;
                document.getElementById('tanggal').value = data.tanggal;
                document.getElementById('deskripsi').value = data.deskripsi;
                document.getElementById('kategori').value = data.kategori;
                document.getElementById('jumlah').value = data.jumlah;
                document.getElementById('status').value = data.status;
            }
            formModal.classList.remove('hidden');
        };

        const hideFormModal = () => formModal.classList.add('hidden');
        const showDeleteModal = (id, deskripsi) => {
            document.getElementById('deleteItemName').textContent = deskripsi;
            deleteModal.dataset.id = id;
            deleteModal.classList.remove('hidden');
        };
        const hideDeleteModal = () => deleteModal.classList.add('hidden');

        document.getElementById('btnTambah').addEventListener('click', () => showFormModal());
        document.querySelectorAll('.edit-btn').forEach(btn => btn.addEventListener('click', () => showFormModal(true, JSON.parse(btn.dataset.item))));
        document.querySelectorAll('.delete-btn').forEach(btn => btn.addEventListener('click', () => showDeleteModal(btn.dataset.id, btn.dataset.deskripsi)));
        
        const btnCloseForm = document.getElementById('btnCloseForm');
        btnCloseForm.addEventListener('click', hideFormModal);
        formModal.querySelector('.btn-cancel').addEventListener('click', hideFormModal);

        deleteModal.querySelector('.btn-cancel').addEventListener('click', hideDeleteModal);
        
        formModal.addEventListener('click', e => { if (e.target === formModal) hideFormModal(); });
        deleteModal.addEventListener('click', e => { if (e.target === deleteModal) hideDeleteModal(); });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch('finance.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        });

        document.getElementById('btnConfirmDelete').addEventListener('click', function() {
            const id = deleteModal.dataset.id;
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('type', type);
            formData.append('id', id);
            fetch('finance.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        });
    });
  </script>
</body>
</html>
