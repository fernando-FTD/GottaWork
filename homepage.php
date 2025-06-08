<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("login.php");
    exit;
}

// Check if user has customer role
if ($_SESSION['role'] !== 'Customer') {
    header("homepage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GottaWork - Coworking Space</title>
  <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Lora', serif;
    }
    .bg-custom-gray {
      background-color: #095151;
    }
  </style>
</head>
<body class="bg-custom-gray text-white">
  <!-- Navbar -->
  <header class="text-white py-6 px-6 flex items-center justify-between max-w-7xl mx-auto">
    <div class="text-2xl font-bold">GottaWork</div>
    <nav class="flex items-center space-x-6">
        <a href="homepage.php" class="text-orange-400 font-medium  underline-offset-4">Home</a>
        <a href="ketersediaanws.php" class="text-white hover:text-orange-400 font-medium">Locations</a>
        <a href="membatalkanreservasi.html" class="text-white hover:text-orange-400 font-medium">Reservation</a>
        <a href="#" class="text-white hover:text-orange-400 font-medium">Profile</a>
  
        <!-- Book a Space Button -->
        <a href="bookingdate.html" class="ml-4 border border-white text-white px-4 py-2 rounded hover:bg-white hover:text-gray-800 transition">
          Book a Space <span class="ml-1">➤</span>
        </a>
      </nav>
  </header>

  <!-- Hero Section -->
  <section class="px-6 py-16 max-w-7xl mx-auto">
    <p class="text-yellow-400 uppercase text-sm tracking-widest">The Features of Office</p>
    <h1 class="text-5xl font-bold mt-4 leading-tight">We’re the Perfect <br />Working Space for You</h1>
    <p class="mt-4 max-w-2xl text-white/80">Comfortable work, maintained focus, increased productivity? It's all at GottaWork. Find your best space now!</p>
    
  </section>

  <!-- Features Section -->
<section class="bg-white text-gray-800 py-20">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10">
      <!-- Left side: Text and Image -->
      <div>
        <p class="text-orange-500 uppercase text-sm font-semibold mb-2">Features</p>
        <h2 class="text-4xl font-bold mb-4">We Offer Creative Environment</h2>
        <p class="text-gray-600 mb-8">Amet diam lorem dui ornare libero magnis erat blandit massa semper egestas sed ultrices enim lectus magna.</p>
        <img src="assets/background.png" class="rounded-lg shadow-lg">
      </div>
  
      <!-- Right side: Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Card Template -->
        <div class="bg-white border shadow p-6 rounded-lg">
          <div class="w-4 h-4 bg-teal-900 mb-4"></div>
          <h3 class="text-xl font-bold mb-2">Office Space</h3>
          <p class="text-gray-500 mb-4 text-sm">Amet diam lorem ornare libero magnis erat blandit massa lorem ornare libero magnis.</p>
          <ul class="space-y-2 text-sm text-gray-600 mb-4">
            <li>✔ Flexible contracts</li>
            <li>✔ Access your office 24/7/365</li>
            <li>✔ No down payment</li>
            <li>✔ All inclusive</li>
            <li>✔ Cancel anytime</li>
            <li>✔ High-speed internet access</li>
          </ul>
          <a href="ketersediaanws.php" class="bg-yellow-400 text-sm text-gray-900 font-semibold px-4 py-2 rounded hover:bg-yellow-300">Learn more ➤</a>
        </div>
  
        <!-- Duplicate Card with different content -->
        <div class="bg-white border shadow p-6 rounded-lg">
          <div class="w-4 h-4 bg-teal-900 mb-4"></div>
          <h3 class="text-xl font-bold mb-2">Dedicated Desk</h3>
          <p class="text-gray-500 mb-4 text-sm">Amet diam lorem ornare libero magnis erat blandit massa lorem ornare libero magnis.</p>
          <ul class="space-y-2 text-sm text-gray-600 mb-4">
            <li>✔ Flexible contracts</li>
            <li>✔ Access your office 24/7/365</li>
            <li>✔ No down payment</li>
            <li>✔ All inclusive</li>
            <li>✔ Cancel anytime</li>
            <li>✔ High-speed internet access</li>
          </ul>
           <a href="ketersediaanws.php" class="bg-yellow-400 text-sm text-gray-900 font-semibold px-4 py-2 rounded hover:bg-yellow-300">Learn more ➤</a>
        </div>
  
        <div class="bg-white border shadow p-6 rounded-lg">
          <div class="w-4 h-4 bg-teal-900 mb-4"></div>
          <h3 class="text-xl font-bold mb-2">Virtual Office</h3>
          <p class="text-gray-500 mb-4 text-sm">Amet diam lorem ornare libero magnis erat blandit massa lorem ornare libero magnis.</p>
          <ul class="space-y-2 text-sm text-gray-600 mb-4">
            <li>✔ Flexible contracts</li>
            <li>✔ Access your office 24/7/365</li>
            <li>✔ No down payment</li>
            <li>✔ All inclusive</li>
            <li>✔ Cancel anytime</li>
            <li>✔ High-speed internet access</li>
          </ul>
          <a href="ketersediaanws.php" class="bg-yellow-400 text-sm text-gray-900 font-semibold px-4 py-2 rounded hover:bg-yellow-300">Learn more ➤</a>
        </div>
  
        <div class="bg-white border shadow p-6 rounded-lg">
          <div class="w-4 h-4 bg-teal-900 mb-4"></div>
          <h3 class="text-xl font-bold mb-2">Meeting Rooms</h3>
          <p class="text-gray-500 mb-4 text-sm">Amet diam lorem ornare libero magnis erat blandit massa lorem ornare libero magnis.</p>
          <ul class="space-y-2 text-sm text-gray-600 mb-4">
            <li>✔ Flexible contracts</li>
            <li>✔ Access your office 24/7/365</li>
            <li>✔ No down payment</li>
            <li>✔ All inclusive</li>
            <li>✔ Cancel anytime</li>
            <li>✔ High-speed internet access</li>
          </ul>
           <a href="ketersediaanws.php" class="bg-yellow-400 text-sm text-gray-900 font-semibold px-4 py-2 rounded hover:bg-yellow-300">Learn more ➤</a>
        </div>
      </div>
    </div>
  </section>
  

  
  <!-- Office Spaces Section -->
<section class="bg-white text-gray-800 py-20">
  <div class="max-w-7xl mx-auto px-6">
    <!-- Header -->
    <div class="mb-12">
      <p class="text-teal-600 uppercase text-sm font-semibold mb-1 tracking-wide">Locations</p>
      <h2 class="text-4xl font-bold">Explore Available Office Spaces</h2>
      <div class="text-gray-600 text-sm max-w-md mt-4">
        Daily/monthly desk, chair and room facilities with fast internet and an atmosphere that motivates productivity.
        <div class="mt-3">
          <a href="ketersediaanws.php" class="inline-block border border-gray-800 px-4 py-2 text-sm font-medium hover:bg-gray-100 rounded">View all →</a>
        </div>
      </div>
    </div>

  
      <!-- Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Card 1 -->
        <div class="bg-white border rounded-lg shadow hover:shadow-md transition">
          <img src="assets/meetingroom.jpg" class="rounded-t-lg h-48 w-full object-cover" alt="Meeting Room">
          <div class="p-5">
            <h3 class="text-lg font-semibold mb-1">Meeting Room</h3>
            <p class="text-sm text-gray-600 mb-2">Private space for meetings, presentations or joint discussions</p>
            <p class="text-sm text-gray-500 mb-2">📍 Mall Boemi Kedaton</p>
            <p class="text-sm text-orange-600 font-semibold mb-4">Rp 35k/hour</p>
            <a href="bookingdate.html" class="inline-block bg-yellow-400 text-gray-900 text-sm font-semibold px-4 py-2 rounded hover:bg-yellow-300">Book now →</a>
          </div>
        </div>
  
        <!-- Card 2 -->
        <div class="bg-white border rounded-lg shadow hover:shadow-md transition">
          <img src="assets/individualdesk.jpeg" class="rounded-t-lg h-48 w-full object-cover" alt="Individual Desk">
          <div class="p-5">
            <h3 class="text-lg font-semibold mb-1">Individual Desk</h3>
            <p class="text-sm text-gray-600 mb-2">Individual desks to provide privacy and improve concentration</p>
            <p class="text-sm text-gray-500 mb-2">📍 Lampung City Mall</p>
            <p class="text-sm text-orange-600 font-semibold mb-4">Rp 50k/hour</p>
            <a href="bookingdate.html" class="inline-block bg-yellow-400 text-gray-900 text-sm font-semibold px-4 py-2 rounded hover:bg-yellow-300">Book now →</a>
          </div>
        </div>
  
        <!-- Card 3 -->
        <div class="bg-white border rounded-lg shadow hover:shadow-md transition">
          <img src="assets/groupdesk.jpeg" class="rounded-t-lg h-48 w-full object-cover" alt="Group Desk">
          <div class="p-5">
            <h3 class="text-lg font-semibold mb-1">Group Desk</h3>
            <p class="text-sm text-gray-600 mb-2">Flexible tables for group work to increase productivity</p>
            <p class="text-sm text-gray-500 mb-2">📍 Mall Boemi Kedaton</p>
            <p class="text-sm text-orange-600 font-semibold mb-4">Rp 20k/hour</p>
            <a href="bookingdate.html" class="inline-block bg-yellow-400 text-gray-900 text-sm font-semibold px-4 py-2 rounded hover:bg-yellow-300">Book now →</a>
          </div>
        </div>
  
        <!-- Card 4 -->
        <div class="bg-white border rounded-lg shadow hover:shadow-md transition">
          <img src="assets/privateoffice.jpeg"class="rounded-t-lg h-48 w-full object-cover" alt="Private Office">
          <div class="p-5">
            <h3 class="text-lg font-semibold mb-1">Private Office</h3>
            <p class="text-sm text-gray-600 mb-2">Private office space for individuals or teams</p>
            <p class="text-sm text-gray-500 mb-2">📍 Mall Boemi Kedaton</p>
            <p class="text-sm text-orange-600 font-semibold mb-4">Rp 300k/week</p>
            <a href="bookingdate.html" class="inline-block bg-yellow-400 text-gray-900 text-sm font-semibold px-4 py-2 rounded hover:bg-yellow-300">Book now →</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
<section class="bg-gray-50 py-20">
    <div class="max-w-6xl mx-auto px-6 text-center">
  
      <p class="uppercase text-orange-400 text-sm font-semibold tracking-wide mb-3">Testimonials</p>
      <h2 class="text-3xl md:text-black font-bold mb-4">What Our Customer Says</h2>
      <p class="text-gray-600 mb-12">
        Comfortable work, maintained focus, increased productivity? It's all at GottaWork. Find your best space now!
      </p>
  
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow p-6">
          <div class="text-orange-500 text-3xl mb-3">“</div>
          <p class="text-gray-600 mb-3 text-sm">
            Comfortable work, maintained focus, increased productivity? It's all at GottaWork. Find your best space now!
          </p>
          <p class="font-semibold text-black mt-4">John William</p>
          <p class="text-gray-500 text-sm">Manager at Furniti</p>
        </div>
  
        <!-- Card 2 (highlighted) -->
        <div class="bg-[#003D36] text-white rounded-xl shadow p-6 scale-105">
          <div class="text-3xl mb-3">“</div>
          <p class="mb-3 text-sm">
            Comfortable work, maintained focus, increased productivity? It's all at GottaWork. Find your best space now!
          </p>
          <p class="font-semibold mt-4">John William</p>
          <p class="text-white/70 text-sm">Manager at Furniti</p>
        </div>
  
        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow p-6">
          <div class="text-orange-500 text-3xl mb-3">“</div>
          <p class="text-gray-600 mb-3 text-sm">
            Comfortable work, maintained focus, increased productivity? It's all at GottaWork. Find your best space now!
          </p>
          <p class="font-semibold text-black mt-4">John William</p>
          <p class="text-gray-500 text-sm">Manager at Furniti</p>
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

</body>
</html>
