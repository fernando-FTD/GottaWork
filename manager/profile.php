<?php
require_once '../db.php';

// Validasi Sesi dan Peran Pengguna
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'Manager') {
    header("Location: ../login.php");
    exit;
}

// Mengambil data pengguna yang sedang login untuk ditampilkan
$current_user_id = (int)$_SESSION['id'];

try {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $current_user_id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: ../logout.php");
        exit();
    }
} catch (PDOException $e) {
    die("Error saat mengambil data pengguna: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Lora', serif; }
        .bg-custom-gray { background-color: #095151; }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-50">

    <div class="flex-grow">
        <?php
          $active_page = 'profile';
          require_once '../includes/header_manager.php';
        ?>

        <!-- Hero Section -->
        <div class="bg-custom-gray text-white">
            <section class="container mx-auto px-6 py-12">
                <h4 class="text-yellow-400 font-semibold uppercase text-sm mb-2">— PROFILE</h4>
                <h2 class="text-3xl md:text-4xl font-bold">Manage Account Profile</h2>
                <p class="text-gray-300 mt-2 max-w-2xl">Lihat atau perbarui informasi akun Anda.</p>
                <div class="mt-6">
                    <a href="edit_profile.php" class="bg-yellow-400 text-gray-900 font-semibold px-6 py-2 rounded hover:bg-yellow-300">
                        Edit Profile &gt;
                    </a>
                </div>
            </section>
        </div>

        <!-- Main Content -->
        <main class="container mx-auto py-12 px-6">
            <div class="flex flex-col md:flex-row gap-8 lg:gap-10">

                <!-- Kolom Kiri (Sidebar) -->
                <div class="w-full md:w-64 flex-shrink-0 bg-yellow-400 rounded-lg shadow-lg p-4 space-y-2 self-start">
                    <div>
                      <p class="text-xs font-semibold text-gray-900">Your email</p>
                      <p class="text-xs text-gray-700 break-words"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                    <hr class="border-t border-white/50">
                    <div class="w-full flex items-center justify-between px-3 py-2 bg-white text-sm font-medium rounded-md shadow-inner">
                      <div class="flex items-center gap-2 text-black">
                        <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        My Profile
                      </div>
                    </div>
                    <form action="../logout.php" method="POST">
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100 rounded-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" /></svg>
                            Log Out
                        </button>
                    </form>
                </div>

                <!-- Kolom Kanan (Konten Utama) -->
                <div id="profile" class="bg-yellow-400 rounded-xl w-full p-6 sm:p-8 shadow-md">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="bg-green-100 border-green-500 text-green-700 p-3 mb-4 rounded text-sm" role="alert">
                            Profil Anda telah berhasil diperbarui.
                        </div>
                    <?php endif; ?>

                    <div class="space-y-6 text-sm sm:text-base text-gray-900">
                        <div class="border-b border-white/50 pb-3 flex flex-col sm:flex-row justify-between">
                          <span class="font-medium">Name</span>
                          <span class="font-light text-gray-800"><?= htmlspecialchars($user['name']) ?></span>
                        </div>
                        <div class="border-b border-white/50 pb-3 flex flex-col sm:flex-row justify-between">
                          <span class="font-medium">Email</span>
                          <span class="font-light text-gray-800 break-all"><?= htmlspecialchars($user['email']) ?></span>
                        </div>
                        <div class="border-b border-white/50 pb-3 flex flex-col sm:flex-row justify-between">
                          <span class="font-medium">Role</span>
                          <span class="font-light text-gray-800"><?= htmlspecialchars($user['role']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <?php
        require_once '../includes/footer.php';
    ?>

</body>
</html>
