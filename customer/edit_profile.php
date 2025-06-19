<?php
require_once '../db.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil semua data dari form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Validasi dasar, pastikan user yang login hanya mengubah profilnya sendiri
    if ($id != $_SESSION['id']) {
        die("Error: Anda tidak berhak mengubah profil ini.");
    }
    
    try {
        // Siapkan query dasar
        $sql = "UPDATE users SET name = :name, email = :email, role = :role";
        
        // Jika kolom password diisi, tambahkan ke query dan hash password baru
        $params = [
            ':name' => $name,
            ':email' => $email,
            ':role' => $role,
            ':id' => $id
        ];
        
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
            $params[':password'] = $hashed_password;
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $conn->prepare($sql);
        
        if ($stmt->execute($params)) {
            // Perbarui data nama di dalam sesi agar langsung berubah
            $_SESSION['name'] = $name;
            
            // Jika berhasil, arahkan kembali ke halaman profile dengan status sukses
            header("Location: profile.php?success=1");
            exit();
        } else {
            echo "Gagal memperbarui profil.";
        }

    } catch (PDOException $e) {
        echo "Error saat memperbarui profil: " . $e->getMessage();
        exit();
    }
}


$current_user_id = (int)$_SESSION['id'];

try {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $current_user_id, PDO::PARAM_INT);
    $stmt->execute();

    
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $user = $stmt->fetch();

    if (!$user) {
        echo "User tidak ditemukan.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error saat mengambil data user: " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
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
          $active_page = 'Profile';
          require_once '../includes/header_customer.php';
        ?>

        <!-- Hero Section -->
        <div class="bg-custom-gray text-white">
            <section class="max-w-7xl mx-auto px-6 py-12">
                <h4 class="text-yellow-400 font-semibold uppercase text-sm mb-2">— My Account</h4>
                <h2 class="text-4xl font-bold">Edit Profile</h2>
                <p class="text-gray-300 mt-2 max-w-2xl">Perbarui informasi akun Anda di bawah ini.</p>
            </section>
        </div>

        <!-- Main Content -->
        <main class="max-w-4xl mx-auto px-6 py-12">
            <div class="bg-white p-8 rounded-lg shadow-md">
                <form method="POST" action="edit_profile.php" class="space-y-6">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <input type="text" id="role" name="role" value="<?= htmlspecialchars($user['role']) ?>" readonly class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 sm:text-sm">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-teal-800 hover:bg-teal-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <?php
        require_once '../includes/footer.php';
    ?>

</body>
</html>
