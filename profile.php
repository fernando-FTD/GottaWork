<?php
session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$id = (int)$_SESSION['id'];

try {
    $sql = "SELECT * FROM users WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "User tidak ditemukan.";
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GottaWork - Menunggu Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet" />
    <style>
      .popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
      }

      .popup-content {
        background-color: white;
        padding: 0;
        border-radius: 8px;
        width: 100%;
        max-width: 420px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
      }

      .popup-close {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        font-size: 24px;
        color: #666;
      }
      .bg-custom-gray {
      background-color: #095151;
    }
    body{
       font-family: 'Lora', serif;
    }
    </style>
  </head>

  <body class>
    <div class="bg-custom-gray">
  <header class="text-white py-6 px-6 flex items-center justify-between max-w-7xl mx-auto">
    <div class="text-2xl font-bold">GottaWork</div>
    <nav class="flex items-center space-x-6">
        <a href="homepage.php" class="text-white hover:text-orange-400 font-medium">Home</a>
        <a href="ketersediaanws.php" class="text-white hover:text-orange-400 font-medium">Locations</a>
        <a href="membatalkanreservasi.html" class="text-white hover:text-orange-400 font-medium">Reservation</a>
        <a href="profile.php" class="text-orange-400 font-medium underline-offset-4">Profile</a>
        <a href="bookingdate.html" class="ml-4 border border-white text-white px-4 py-2 rounded hover:bg-white hover:text-gray-800 transition">
          Book a Space <span class="ml-1">➤</span>
        </a>
    </nav>
  </header>

  <section class="px-6 py-16 max-w-7xl mx-auto">
    <p class="text-yellow-400 uppercase text-sm tracking-widest">— PROFILE</p>
    <h1 class="text-5xl font-bold mt-4 leading-tight text-white">Manage Account Profile</h1>
    <button class="mt-6 bg-yellow-400 text-gray-900 font-semibold px-6 py-2 rounded hover:bg-yellow-300">my profile > </button>
    <button onclick="window.location.href='edit_profile.php';" class="mt-6 bg-yellow-400 text-gray-900 font-semibold px-6 py-2 rounded hover:bg-yellow-300">edit profile &gt;</button>
     </section>
</div>


    <section class="py-12 px-4 bg-white flex justify-center">
  <div class="flex gap-10 flex-col md:flex-row items-start">

    <!-- Dropdown Akun -->
    <div class="relative inline-block text-left">
      <div class="bg-yellow-400 rounded-lg shadow-lg w-64 p-4 space-y-2 border border-black">
        <!-- Email Info -->
        <div>
          <p class="text-xs font-semibold text-gray-900">Your email</p>
          <p class="text-xs text-gray-700"><?= htmlspecialchars($user['email']) ?></p>
        </div>

        <hr class="border-t border-white">

        <!-- My Profile -->
        <button onclick="document.getElementById('profile').scrollIntoView({ behavior: 'smooth' })"
          class="w-full flex items-center justify-between px-3 py-2 bg-white text-sm font-medium rounded-md hover:bg-gray-100 transition">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-800" fill="none" stroke="currentColor" stroke-width="2"
              viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            My Profile
          </div>
          <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
          </svg>
        </button>

       <!-- Log Out -->
    <form action="logout.php" method="POST">
    <button
        type="submit"
        class="w-full flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100 rounded-md transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"     viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round"
          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1" />
        </svg>
        Log Out
      </button>
    </form>
      </div>
    </div>



 <div id="profile" class="bg-yellow-400 rounded-xl w-[600px] p-6 sm:p-10 shadow-md">
      <div class="space-y-6 text-sm sm:text-base text-gray-900">
        <div class="border-b border-white pb-2 flex justify-between">
          <span class="font-medium">Name</span>
          <span class="font-light"><?= htmlspecialchars($user['name']) ?></span>
        </div>
        <div class="border-b border-white pb-2 flex justify-between">
          <span class="font-medium">Email</span>
          <span class="font-light"> <?= htmlspecialchars($user['email']) ?></span>
        </div>
        <div class="border-b border-white pb-2 flex justify-between">
          <span class="font-medium">Role</span>
          <span class="font-light">Customer</span>
        </div>
      </div>
    </div>

  </div>
</section>

    <!-- Footer -->
  <footer class="bg-gray-900 text-white py-16 mt-16">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-8">
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

  
  </body>
</html>
