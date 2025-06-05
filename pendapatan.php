<?php
// Database Class
class Database {
    private $host = "localhost";
    private $db_name = "database_gottawork";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

// Transaction Class
class Transaction {
    private $conn;
    private $table_name = "transactions";

    public $id;
    public $tanggal;
    public $deskripsi;
    public $kategori;
    public $jumlah;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all transactions with pagination
    public function getAll($page = 1, $limit = 10) {
        $offset = ($page - 1) * $limit;
        
        $query = "SELECT * FROM " . $this->table_name . " 
                  ORDER BY tanggal DESC 
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    // Get total count for pagination
    public function getTotalCount() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get statistics
    public function getStats() {
        $query = "SELECT 
                    SUM(CASE WHEN status = 'Selesai' THEN jumlah ELSE 0 END) as total_pendapatan,
                    COUNT(*) as total_transaksi,
                    AVG(jumlah) as rata_rata
                  FROM " . $this->table_name;
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create new transaction
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET tanggal=:tanggal, deskripsi=:deskripsi, kategori=:kategori, 
                      jumlah=:jumlah, status=:status";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->tanggal = htmlspecialchars(strip_tags($this->tanggal));
        $this->deskripsi = htmlspecialchars(strip_tags($this->deskripsi));
        $this->kategori = htmlspecialchars(strip_tags($this->kategori));
        $this->jumlah = htmlspecialchars(strip_tags($this->jumlah));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // Bind values
        $stmt->bindParam(":tanggal", $this->tanggal);
        $stmt->bindParam(":deskripsi", $this->deskripsi);
        $stmt->bindParam(":kategori", $this->kategori);
        $stmt->bindParam(":jumlah", $this->jumlah);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update transaction
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET tanggal=:tanggal, deskripsi=:deskripsi, kategori=:kategori,
                      jumlah=:jumlah, status=:status
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->tanggal = htmlspecialchars(strip_tags($this->tanggal));
        $this->deskripsi = htmlspecialchars(strip_tags($this->deskripsi));
        $this->kategori = htmlspecialchars(strip_tags($this->kategori));
        $this->jumlah = htmlspecialchars(strip_tags($this->jumlah));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind values
        $stmt->bindParam(":tanggal", $this->tanggal);
        $stmt->bindParam(":deskripsi", $this->deskripsi);
        $stmt->bindParam(":kategori", $this->kategori);
        $stmt->bindParam(":jumlah", $this->jumlah);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete transaction
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get single transaction
    public function getById() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->tanggal = $row['tanggal'];
            $this->deskripsi = $row['deskripsi'];
            $this->kategori = $row['kategori'];
            $this->jumlah = $row['jumlah'];
            $this->status = $row['status'];
            return true;
        }
        return false;
    }
}

// Function to get combined financial statistics
function getFinancialStats($db) {
    try {
        // Get income stats
        $income_query = "SELECT 
                            SUM(CASE WHEN status = 'Selesai' THEN jumlah ELSE 0 END) as total_pendapatan,
                            COUNT(*) as total_transaksi_pendapatan
                         FROM transactions";
        $income_stmt = $db->prepare($income_query);
        $income_stmt->execute();
        $income_stats = $income_stmt->fetch(PDO::FETCH_ASSOC);

        // Get expense stats
        $expense_query = "SELECT 
                             SUM(CASE WHEN status = 'Selesai' THEN jumlah ELSE 0 END) as total_pengeluaran,
                             COUNT(*) as total_transaksi_pengeluaran
                          FROM expenses";
        $expense_stmt = $db->prepare($expense_query);
        $expense_stmt->execute();
        $expense_stats = $expense_stmt->fetch(PDO::FETCH_ASSOC);

        // Calculate combined stats
        $total_pendapatan = $income_stats['total_pendapatan'] ?? 0;
        $total_pengeluaran = $expense_stats['total_pengeluaran'] ?? 0;
        $keuntungan_bersih = $total_pendapatan - $total_pengeluaran;
        $total_transaksi = ($income_stats['total_transaksi_pendapatan'] ?? 0) + ($expense_stats['total_transaksi_pengeluaran'] ?? 0);

        return [
            'total_pendapatan' => $total_pendapatan,
            'total_pengeluaran' => $total_pengeluaran,
            'keuntungan_bersih' => $keuntungan_bersih,
            'total_transaksi' => $total_transaksi,
            'total_transaksi_pendapatan' => $income_stats['total_transaksi_pendapatan'] ?? 0
        ];
    } catch (Exception $e) {
        // Return default values if there's an error
        return [
            'total_pendapatan' => 0,
            'total_pengeluaran' => 0,
            'keuntungan_bersih' => 0,
            'total_transaksi' => 0,
            'total_transaksi_pendapatan' => 0
        ];
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            throw new Exception("Database connection failed");
        }
        
        $transaction = new Transaction($db);
        $action = $_POST['action'];

        switch($action) {
            case 'create':
                // Validate required fields
                if (empty($_POST['tanggal']) || empty($_POST['deskripsi']) || empty($_POST['kategori']) || empty($_POST['jumlah']) || empty($_POST['status'])) {
                    throw new Exception("Semua field harus diisi");
                }
                
                $transaction->tanggal = $_POST['tanggal'];
                $transaction->deskripsi = $_POST['deskripsi'];
                $transaction->kategori = $_POST['kategori'];
                $transaction->jumlah = str_replace(',', '', $_POST['jumlah']); // Remove commas if any
                $transaction->status = $_POST['status'];

                if($transaction->create()) {
                    echo json_encode(['success' => true, 'message' => 'Transaksi berhasil ditambahkan!']);
                } else {
                    throw new Exception("Gagal menambahkan transaksi ke database");
                }
                break;

            case 'update':
                // Validate required fields
                if (empty($_POST['id']) || empty($_POST['tanggal']) || empty($_POST['deskripsi']) || empty($_POST['kategori']) || empty($_POST['jumlah']) || empty($_POST['status'])) {
                    throw new Exception("Semua field harus diisi");
                }
                
                $transaction->id = $_POST['id'];
                $transaction->tanggal = $_POST['tanggal'];
                $transaction->deskripsi = $_POST['deskripsi'];
                $transaction->kategori = $_POST['kategori'];
                $transaction->jumlah = str_replace(',', '', $_POST['jumlah']); // Remove commas if any
                $transaction->status = $_POST['status'];

                if($transaction->update()) {
                    echo json_encode(['success' => true, 'message' => 'Transaksi berhasil diperbarui!']);
                } else {
                    throw new Exception("Gagal memperbarui transaksi di database");
                }
                break;

            case 'delete':
                if (empty($_POST['id'])) {
                    throw new Exception("ID transaksi tidak valid");
                }
                
                $transaction->id = $_POST['id'];

                if($transaction->delete()) {
                    echo json_encode(['success' => true, 'message' => 'Transaksi berhasil dihapus!']);
                } else {
                    throw new Exception("Gagal menghapus transaksi dari database");
                }
                break;

            default:
                throw new Exception("Action tidak valid: " . $action);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage(), 'debug' => $_POST]);
    }
    exit;
}

// Initialize database and get data for display
$database = new Database();
$db = $database->getConnection();
$transaction = new Transaction($db);

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$total_records = $transaction->getTotalCount();
$total_pages = ceil($total_records / $limit);

// Get transactions
$stmt = $transaction->getAll($page, $limit);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get combined financial statistics
$financial_stats = getFinancialStats($db);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>GottaWork - Dashboard Finance</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    body { font-family: 'Lora', serif; }
    .headerr { background-color: rgb(19 78 74); }
    
    /* Mobile dropdown menu styles */
    .mobile-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      background-color: rgb(19 78 74);
      border-radius: 8px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      border: 1px solid rgba(255, 255, 255, 0.1);
      min-width: 200px;
      opacity: 0;
      visibility: hidden;
      transform: translateY(-10px);
      transition: all 0.3s ease;
      z-index: 100;
    }
    
    .mobile-dropdown.show {
      opacity: 1;
      visibility: visible;
      transform: translateY(0);
    }
    
    .mobile-dropdown a {
      display: block;
      padding: 12px 16px;
      color: white;
      text-decoration: none;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
      transition: background-color 0.2s ease;
    }
    
    .mobile-dropdown a:last-child {
      border-bottom: none;
      border-radius: 0 0 8px 8px;
    }
    
    .mobile-dropdown a:first-child {
      border-radius: 8px 8px 0 0;
    }
    
    .mobile-dropdown a:hover {
      background-color: rgba(255, 255, 255, 0.1);
    }
    
    /* Hamburger animation */
    .hamburger-icon {
      transition: transform 0.3s ease;
    }
    
    .hamburger-icon.active {
      transform: rotate(90deg);
    }
    
    /* Responsive styles */
    @media (min-width: 769px) {
      .mobile-dropdown {
        display: none !important;
      }
    }
    
    /* Table responsive styles */
    @media (max-width: 640px) {
      .responsive-table {
        display: block;
        width: 100%;
        overflow-x: auto;
      }
      .card-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body class="flex flex-col min-h-screen">
  <!-- Header -->
  <header class="headerr text-white py-8">
    <div class="hero-section relative">
    <!-- Navigation -->
    <nav class="absolute w-full py-6 px-8 flex justify-between items-center z-10">
      <a href="#" class="text-white text-3xl font-bold">GottaWork</a>
      
      <!-- Mobile Menu Container -->
      <div class="relative md:hidden">
        <button class="text-white focus:outline-none" id="mobileMenuToggle">
          <i class="fas fa-bars text-2xl hamburger-icon" id="hamburgerIcon"></i>
        </button>
        
        <!-- Mobile Dropdown Menu -->
        <div class="mobile-dropdown" id="mobileDropdown">
          <a href="manager_homepage.php">
            <i class="fas fa-home mr-2"></i> Home
          </a>
          <a href="pendapatan.php" class="text-yellow-400">
            <i class="fas fa-chart-line mr-2"></i> Finance
          </a>
          <a href="manager_skematarif.php">
            <i class="fas fa-tags mr-2"></i> Price Scheme
          </a>
          <a href="mengelolah_profil.php">
            <i class="fas fa-tags mr-2"></i> Profil
          </a>
          <a href="login.php">
            <i class="fas fa-sign-out-alt mr-2"></i> Log Out
          </a>
        </div>
      </div>
      
      <!-- Desktop Navigation -->
      <div class="hidden md:flex items-center space-x-8">
        <a href="manager_homepage.php" class="text-white hover:text-yellow-400 transition-colors">
          Home
        </a>
        <a href="pendapatan.php" class="text-yellow-400 hover:text-yellow-500 transition-colors">
          Finance
        </a>
        <a href="manager_skematarif.php" class="text-white hover:text-yellow-400 transition-colors">
          Price Scheme
        </a>
        <a href="mengelolah_profil.php" class="text-white hover:text-yellow-400 transition-colors">
          Profil
        </a>
        <a href="login.php" class="border border-white text-white px-6 py-2 rounded-md flex items-center justify-center hover:bg-white hover:bg-opacity-10 transition-colors">
          Log Out
        </a>
      </div>
    </nav>
    
    <!-- Page Title -->
    <div class="container mx-auto px-8 pt-32">
      <div class="flex items-center text-yellow-400 mb-2">
        <i class="fas fa-chevron-down mr-1 text-xs"></i>
        <span class="ml-1 text-sm">Finance</span>
      </div>
      <h1 class="text-3xl font-bold mb-6 text-white">Pendapatan Perusahaan</h1>
      
      <div class="flex justify-between items-center">
        <div class="flex space-x-4">
          <!-- Tombol Pendapatan dengan style yang sama seperti tombol Finance -->
          <a href="#" class="bg-yellow-400 text-black px-6 py-3 rounded font-medium flex items-center">
            Pendapatan
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a>
          
          <!-- Tombol Pengeluaran dengan style yang sama seperti tombol Price Scheme -->
          <a href="pengeluaran.php" class="border border-yellow-400 text-yellow-400 px-6 py-3 rounded font-medium flex items-center">
            Pengeluaran
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </a>
        </div>
        
        <div class="flex items-center text-sm text-white">
          <span>Home</span>
          <i class="fas fa-chevron-right mx-1 text-xs"></i>
          <span>Finance</span>
        </div>
      </div>
    </div>
  </div>
  </header>
  
    
    <!-- Dashboard Stats -->
    <div class="container mx-auto px-6 py-8">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 card-grid">
        <!-- Card 1 -->
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-gray-600 text-sm mb-2">Total Pendapatan</h3>
          <p class="text-xl font-bold mb-1">Rp <?php echo number_format($financial_stats['total_pendapatan'], 0, ',', '.'); ?></p>
          <p class="text-xs text-green-500">+13,5% dari bulan lalu</p>
        </div>
        
        <!-- Card 2 -->
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-gray-600 text-sm mb-2">Total Pengeluaran</h3>
          <p class="text-xl font-bold mb-1">Rp <?php echo number_format($financial_stats['total_pengeluaran'], 0, ',', '.'); ?></p>
          <p class="text-xs text-red-500">+9,7% dari bulan lalu</p>
        </div>
        
        <!-- Card 3 -->
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-gray-600 text-sm mb-2">Keuntungan Bersih</h3>
          <p class="text-xl font-bold mb-1">Rp <?php echo number_format($financial_stats['keuntungan_bersih'], 0, ',', '.'); ?></p>
          <p class="text-xs <?php echo $financial_stats['keuntungan_bersih'] >= 0 ? 'text-green-500' : 'text-red-500'; ?>">
            <?php echo $financial_stats['keuntungan_bersih'] >= 0 ? '+18,3%' : '-5,2%'; ?> dari bulan lalu
          </p>
        </div>
        
        <!-- Card 4 -->
        <div class="bg-white p-4 rounded shadow">
          <h3 class="text-gray-600 text-sm mb-2">Total Transaksi</h3>
          <p class="text-xl font-bold mb-1"><?php echo $financial_stats['total_transaksi']; ?></p>
          <p class="text-xs text-green-500">+5% dari bulan lalu</p>
        </div>
      </div>
      
      <!-- Pendapatan Table -->
      <div class="mt-8">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Rincian Pendapatan</h2>
          <button id="btnTambahTransaksi" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Transaksi
          </button>
        </div>
        
        <!-- Tabs -->
        <div class="flex mb-4 border-b">
          <button class="px-4 py-2 border-b-2 border-yellow-500 font-medium">
            Data Pendapatan
          </button>
        </div>
        
        <!-- Table -->
        <div class="overflow-x-auto responsive-table">
          <table class="w-full">
            <thead>
              <tr class="text-left text-gray-600">
                <th class="pb-3">Tanggal</th>
                <th class="pb-3">Deskripsi</th>
                <th class="pb-3">Kategori</th>
                <th class="pb-3">Jumlah (Rp)</th>
                <th class="pb-3">Status</th>
                <th class="pb-3">Aksi</th>
              </tr>
            </thead>
            <tbody id="transactionTableBody">
              <?php foreach($transactions as $row): ?>
              <tr class="border-t" data-id="<?php echo $row['id']; ?>">
                <td class="py-3"><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                <td class="py-3"><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                <td class="py-3"><?php echo htmlspecialchars($row['kategori']); ?></td>
                <td class="py-3"><?php echo number_format($row['jumlah'], 0, ',', '.'); ?></td>
                <td class="py-3">
                  <span class="px-2 py-1 rounded text-xs <?php echo $row['status'] == 'Selesai' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                    <?php echo $row['status']; ?>
                  </span>
                </td>
                <td class="py-3">
                  <button class="edit-btn text-blue-600 hover:text-blue-800 mr-2" 
                          data-id="<?php echo $row['id']; ?>"
                          data-tanggal="<?php echo $row['tanggal']; ?>"
                          data-deskripsi="<?php echo htmlspecialchars($row['deskripsi']); ?>"
                          data-kategori="<?php echo $row['kategori']; ?>"
                          data-jumlah="<?php echo $row['jumlah']; ?>"
                          data-status="<?php echo $row['status']; ?>">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button class="delete-btn text-red-600 hover:text-red-800" 
                          data-id="<?php echo $row['id']; ?>"
                          data-deskripsi="<?php echo htmlspecialchars($row['deskripsi']); ?>">
                    <i class="fas fa-trash"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        
        <!-- Pagination -->
        <div class="flex justify-center mt-4">
          <nav class="flex items-center space-x-1">
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
              <a href="?page=<?php echo $i; ?>" 
                 class="w-8 h-8 flex items-center justify-center <?php echo $i == $page ? 'bg-gray-900 text-white' : 'text-gray-700 hover:bg-gray-200'; ?> rounded">
                <?php echo $i; ?>
              </a>
            <?php endfor; ?>
          </nav>
        </div>
      </div>
    </div>
  </main>
  
  <!-- Footer -->
  <footer class="bg-gray-900 text-white py-16 mt-16">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8">
        <!-- Company Info -->
        <div class="col-span-1">
          <h2 class="text-2xl font-bold mb-6">GottaWork</h2>
          <p class="text-gray-400 mb-4">7101 Market Street Bandung, Indonesia</p>
          <p class="text-gray-400 mb-2"><i class="fas fa-phone mr-2"></i> (+62) 123 456 789</p>
          <p class="text-gray-400 mb-6"><i class="fas fa-envelope mr-2"></i> customer@gottawork.com</p>
          <div class="flex space-x-4">
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
        
        <!-- Company Links -->
        <div class="col-span-1">
          <h3 class="text-lg font-semibold mb-4">Company</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-400 hover:text-white">Meeting Room</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white">Individual Desk</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white">Group Desk</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white">Private Office</a></li>
          </ul>
        </div>
        
        <!-- Locations Links -->
        <div class="col-span-1">
          <h3 class="text-lg font-semibold mb-4">Locations</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-400 hover:text-white">Mall Bintaro Xchange</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white">Lampung City Mall</a></li>
          </ul>
        </div>
        
        <!-- Partnerships Links -->
        <div class="col-span-1">
          <h3 class="text-lg font-semibold mb-4">Partnerships</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-400 hover:text-white">Event Venues</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white">Brokers</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white">Community Events</a></li>
          </ul>
        </div>
        
        <!-- Support/Spaces Links -->
        <div class="col-span-1">
          <h3 class="text-lg font-semibold mb-4">Spaces</h3>
          <ul class="space-y-2">
            <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white">About</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white">Jobs</a></li>
            <li><a href="#" class="text-gray-400 hover:text-white">Franchise</a></li>
          </ul>
        </div>
      </div>
      
      <!-- Copyright -->
      <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-500 text-sm">
        © 2025 GottaWork. Powered by GW
      </div>
    </div>
  </footer>

  <!-- Pop-up Form untuk Tambah Transaksi -->
  <div id="formPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold">Tambah Transaksi Baru</h3>
        <button id="btnCloseForm" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <form id="formTambahTransaksi">
        <div class="mb-4">
          <label for="tanggal" class="block text-gray-700 font-medium mb-2">Tanggal</label>
          <input type="date" id="tanggal" name="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
        </div>
        
        <div class="mb-4">
          <label for="deskripsi" class="block text-gray-700 font-medium mb-2">Deskripsi</label>
          <input type="text" id="deskripsi" name="deskripsi" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Masukkan deskripsi transaksi" required>
        </div>
        
        <div class="mb-4">
          <label for="kategori" class="block text-gray-700 font-medium mb-2">Kategori</label>
          <select id="kategori" name="kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            <option value="">Pilih Kategori</option>
            <option value="Membership">Membership</option>
            <option value="Ruang Meeting">Ruang Meeting</option>
            <option value="Event Space">Event Space</option>
            <option value="Layanan Tambahan">Layanan Tambahan</option>
          </select>
        </div>
        
        <div class="mb-4">
          <label for="jumlah" class="block text-gray-700 font-medium mb-2">Jumlah (Rp)</label>
          <input type="number" id="jumlah" name="jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Masukkan jumlah pendapatan" required>
        </div>
        
        <div class="mb-4">
          <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
          <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            <option value="">Pilih Status</option>
            <option value="Selesai">Selesai</option>
            <option value="Tertunda">Tertunda</option>
          </select>
        </div>
        
        <div class="flex justify-end space-x-2">
          <button type="button" id="btnBatalForm" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">Batal</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Pop-up Form untuk Edit Transaksi -->
  <div id="editFormPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold">Edit Transaksi</h3>
        <button id="btnCloseEditForm" class="text-gray-500 hover:text-gray-700">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <form id="formEditTransaksi">
        <input type="hidden" id="edit_id" name="edit_id">
        
        <div class="mb-4">
          <label for="edit_tanggal" class="block text-gray-700 font-medium mb-2">Tanggal</label>
          <input type="date" id="edit_tanggal" name="edit_tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
        </div>
        
        <div class="mb-4">
          <label for="edit_deskripsi" class="block text-gray-700 font-medium mb-2">Deskripsi</label>
          <input type="text" id="edit_deskripsi" name="edit_deskripsi" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Masukkan deskripsi transaksi" required>
        </div>
        
        <div class="mb-4">
          <label for="edit_kategori" class="block text-gray-700 font-medium mb-2">Kategori</label>
          <select id="edit_kategori" name="edit_kategori" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            <option value="">Pilih Kategori</option>
            <option value="Membership">Membership</option>
            <option value="Ruang Meeting">Ruang Meeting</option>
            <option value="Event Space">Event Space</option>
            <option value="Layanan Tambahan">Layanan Tambahan</option>
          </select>
        </div>
        
        <div class="mb-4">
          <label for="edit_jumlah" class="block text-gray-700 font-medium mb-2">Jumlah (Rp)</label>
          <input type="number" id="edit_jumlah" name="edit_jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-md" placeholder="Masukkan jumlah pendapatan" required>
        </div>
        
        <div class="mb-4">
          <label for="edit_status" class="block text-gray-700 font-medium mb-2">Status</label>
          <select id="edit_status" name="edit_status" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
            <option value="">Pilih Status</option>
            <option value="Selesai">Selesai</option>
            <option value="Tertunda">Tertunda</option>
          </select>
        </div>
        
        <div class="flex justify-end space-x-2">
          <button type="button" id="btnBatalEditForm" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">Batal</button>
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

  <!-- Delete Confirmation Modal -->
  <div id="deleteConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
      <div class="flex items-center mb-4">
        <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
        <h3 class="text-xl font-bold">Konfirmasi Hapus</h3>
      </div>
      
      <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus transaksi "<span id="deleteItemName"></span>"? Tindakan ini tidak dapat dibatalkan.</p>
      
      <div class="flex justify-end space-x-2">
        <button type="button" id="btnCancelDelete" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-100">Batal</button>
        <button type="button" id="btnConfirmDelete" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Hapus</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Mobile dropdown menu toggle
      const mobileMenuToggle = document.getElementById('mobileMenuToggle');
      const mobileDropdown = document.getElementById('mobileDropdown');
      const hamburgerIcon = document.getElementById('hamburgerIcon');

      if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function(e) {
          e.stopPropagation();
          mobileDropdown.classList.toggle('show');
          hamburgerIcon.classList.toggle('active');
        });
      }

      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (!mobileMenuToggle.contains(e.target) && !mobileDropdown.contains(e.target)) {
          mobileDropdown.classList.remove('show');
          hamburgerIcon.classList.remove('active');
        }
      });

      // Close dropdown when clicking on a link
      const dropdownLinks = mobileDropdown.querySelectorAll('a');
      dropdownLinks.forEach(link => {
        link.addEventListener('click', function() {
          mobileDropdown.classList.remove('show');
          hamburgerIcon.classList.remove('active');
        });
      });
      
      // Popup form functionality for Tambah Transaksi
      const btnTambahTransaksi = document.getElementById('btnTambahTransaksi');
      const formPopup = document.getElementById('formPopup');
      const btnCloseForm = document.getElementById('btnCloseForm');
      const btnBatalForm = document.getElementById('btnBatalForm');
      const formTambahTransaksi = document.getElementById('formTambahTransaksi');

      // Open popup
      btnTambahTransaksi.addEventListener('click', function() {
        formPopup.classList.remove('hidden');
      });

      // Close popup
      btnCloseForm.addEventListener('click', function() {
        formPopup.classList.add('hidden');
      });

      btnBatalForm.addEventListener('click', function() {
        formPopup.classList.add('hidden');
      });

      // Handle form submission for adding transaction
      formTambahTransaksi.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        formData.append('action', 'create');
        
        fetch('pendapatan.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if(data.success) {
            alert(data.message);
            formPopup.classList.add('hidden');
            formTambahTransaksi.reset();
            location.reload(); // Reload to show new data
          } else {
            alert(data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Terjadi kesalahan saat menambahkan transaksi.');
        });
      });

      // Close popup when clicking outside
      formPopup.addEventListener('click', function(e) {
        if (e.target === formPopup) {
          formPopup.classList.add('hidden');
        }
      });

      // Edit Transaksi functionality
      const editBtns = document.querySelectorAll('.edit-btn');
      const editFormPopup = document.getElementById('editFormPopup');
      const btnCloseEditForm = document.getElementById('btnCloseEditForm');
      const btnBatalEditForm = document.getElementById('btnBatalEditForm');
      const formEditTransaksi = document.getElementById('formEditTransaksi');
      
      // Open edit popup and fill with data
      editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          const id = this.getAttribute('data-id');
          const tanggal = this.getAttribute('data-tanggal');
          const deskripsi = this.getAttribute('data-deskripsi');
          const kategori = this.getAttribute('data-kategori');
          const jumlah = this.getAttribute('data-jumlah');
          const status = this.getAttribute('data-status');
          
          // Fill the form with data
          document.getElementById('edit_id').value = id;
          document.getElementById('edit_tanggal').value = tanggal;
          document.getElementById('edit_deskripsi').value = deskripsi;
          document.getElementById('edit_kategori').value = kategori;
          document.getElementById('edit_jumlah').value = jumlah;
          document.getElementById('edit_status').value = status;
          
          // Show the popup
          editFormPopup.classList.remove('hidden');
        });
      });
      
      // Close edit popup
      btnCloseEditForm.addEventListener('click', function() {
        editFormPopup.classList.add('hidden');
      });
      
      btnBatalEditForm.addEventListener('click', function() {
        editFormPopup.classList.add('hidden');
      });
      
      // Handle edit form submission
      formEditTransaksi.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData();
        formData.append('action', 'update');
        formData.append('id', document.getElementById('edit_id').value);
        formData.append('tanggal', document.getElementById('edit_tanggal').value);
        formData.append('deskripsi', document.getElementById('edit_deskripsi').value);
        formData.append('kategori', document.getElementById('edit_kategori').value);
        formData.append('jumlah', document.getElementById('edit_jumlah').value);
        formData.append('status', document.getElementById('edit_status').value);
        
        // Debug: log form data
        console.log('Sending data:', {
          action: 'update',
          id: document.getElementById('edit_id').value,
          tanggal: document.getElementById('edit_tanggal').value,
          deskripsi: document.getElementById('edit_deskripsi').value,
          kategori: document.getElementById('edit_kategori').value,
          jumlah: document.getElementById('edit_jumlah').value,
          status: document.getElementById('edit_status').value
        });
        
        fetch('pendapatan.php', {
          method: 'POST',
          body: formData
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Network response was not ok');
          }
          return response.json();
        })
        .then(data => {
          console.log('Response:', data); // Debug response
          if(data.success) {
            alert(data.message);
            editFormPopup.classList.add('hidden');
            location.reload();
          } else {
            alert('Error: ' + data.message);
            if (data.debug) {
              console.error('Debug info:', data.debug);
            }
          }
        })
        .catch(error => {
          console.error('Fetch error:', error);
          alert('Terjadi kesalahan jaringan: ' + error.message);
        });
      });
      
      // Close edit popup when clicking outside
      editFormPopup.addEventListener('click', function(e) {
        if (e.target === editFormPopup) {
          editFormPopup.classList.add('hidden');
        }
      });

      // Delete functionality
      const deleteBtns = document.querySelectorAll('.delete-btn');
      const deleteConfirmModal = document.getElementById('deleteConfirmModal');
      const btnCancelDelete = document.getElementById('btnCancelDelete');
      const btnConfirmDelete = document.getElementById('btnConfirmDelete');
      const deleteItemName = document.getElementById('deleteItemName');
      let deleteId = null;

      // Open delete confirmation modal
      deleteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          deleteId = this.getAttribute('data-id');
          const deskripsi = this.getAttribute('data-deskripsi');
          
          deleteItemName.textContent = deskripsi;
          deleteConfirmModal.classList.remove('hidden');
        });
      });

      // Cancel delete
      btnCancelDelete.addEventListener('click', function() {
        deleteConfirmModal.classList.add('hidden');
        deleteId = null;
      });

      // Confirm delete
      btnConfirmDelete.addEventListener('click', function() {
        if(deleteId) {
          const formData = new FormData();
          formData.append('action', 'delete');
          formData.append('id', deleteId);
          
          fetch('pendapatan.php', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if(data.success) {
              alert(data.message);
              deleteConfirmModal.classList.add('hidden');
              location.reload(); // Reload to show updated data
            } else {
              alert(data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menghapus transaksi.');
          });
        }
      });

      // Close delete modal when clicking outside
      deleteConfirmModal.addEventListener('click', function(e) {
        if (e.target === deleteConfirmModal) {
          deleteConfirmModal.classList.add('hidden');
        }
      });
    });
  </script>
</body>
</html>
