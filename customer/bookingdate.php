<?php
require_once '../db.php';

// Validasi Sesi dan Peran Pengguna.
//untuk memastikan hanya customer yang login yang bisa mengakses halaman ini.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'Customer') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Booking Date</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#024545] min-h-screen flex items-center justify-center text-white">
  
  <div class="bg-white text-black rounded-lg shadow-lg p-8 w-full max-w-lg">
    <h2 class="text-2xl font-bold mb-6 text-center text-[#024545]">Pilih Tanggal Booking</h2>
    
    <form id="bookingForm" class="space-y-4">
      <div>
        <label for="name" class="block mb-1 font-semibold">Nama Lengkap Pemesan</label>
        <!-- Kolom nama sekarang bisa diisi manual -->
        <input type="text" id="name" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]" placeholder="Masukkan nama pemesan">
      </div>

      <div>
        <label for="email" class="block mb-1 font-semibold">Email Pemesan</label>
        <!-- Kolom email sekarang bisa diisi manual -->
        <input type="email" id="email" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]" placeholder="Masukkan email pemesan">
      </div>

      <div>
        <label for="workspace" class="block mb-1 font-semibold">Jenis Workspace</label>
        <select id="workspace" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]">
          <option value="">-- Pilih Workspace --</option>
          <option value="Individual">Individual Desk</option>
          <option value="Group">Group Desk</option>
          <option value="Private Office">Private Office</option>
          <option value="Meeting Room">Meeting Room</option>
        </select>
      </div>

      <div>
        <label for="date" class="block mb-1 font-semibold">Tanggal Booking</label>
        <input type="date" id="date" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]">
      </div>

      <div>
        <label for="startTime" class="block mb-1 font-semibold">Jam Mulai</label>
        <input type="time" id="startTime" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]">
      </div>

      <div>
        <label for="endTime" class="block mb-1 font-semibold">Jam Berakhir</label>
        <input type="time" id="endTime" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#024545]">
      </div>

      <div class="pt-4 text-right">
        <button type="submit" class="bg-[#024545] text-white px-6 py-2 rounded hover:bg-[#036b6b]">
          Lanjut ke Denah
        </button>
      </div>
    </form>
  </div>

  <script>
    document.getElementById("bookingForm").addEventListener("submit", function(event) {
      event.preventDefault();

      // Ambil semua data dari form, termasuk nama dan email yang diinput manual
      const bookingData = {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        workspace: document.getElementById("workspace").value,
        startDate: document.getElementById("date").value,
        endDate: document.getElementById("date").value, // Diasumsikan tanggal akhir sama
        startTime: document.getElementById("startTime").value,
        endTime: document.getElementById("endTime").value
      };

      // Simpan semua data ke localStorage untuk digunakan di halaman selanjutnya
      localStorage.setItem("bookingData", JSON.stringify(bookingData));
      window.location.href = "denah.html";
    });
  </script>
</body>
</html>
