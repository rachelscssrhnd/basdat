<?php include 'data.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tes Laboratorium</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FEF9E1] min-h-screen">

<!-- === NAVBAR MULAI === -->
<nav class="bg-[#A31D1D] border-b-4 border-[#A31D1D] fixed top-0 left-0 w-full z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
      <!-- Logo -->
      <div class="flex-shrink-0">
        <a href="#" class="text-[#FEF9E1] font-bold text-xl">E-Clinic Lab</a>
      </div>
      <!-- Menu Desktop -->
      <div class="hidden md:flex space-x-6">
        <a href="#home" class="text-[#FEF9E1] hover:text-white font-medium">Home</a>
        <a href="#jenis-tes" class="text-[#FEF9E1] hover:text-white font-medium">Jenis Tes</a>
        <a href="#booking" class="text-[#FEF9E1] hover:text-white font-medium">Booking</a>
        <a href="#hasil-tes" class="text-[#FEF9E1] hover:text-white font-medium">Hasil Tes</a>
        <a href="#location" class="text-[#FEF9E1] hover:text-white font-medium">Location</a>
        <a href="#faq" class="text-[#FEF9E1] hover:text-white font-medium">FAQ</a>
        <a href="#about" class="text-[#FEF9E1] hover:text-white font-medium">About Us</a>
      </div>
      <!-- Tombol menu mobile -->
      <div class="md:hidden flex items-center">
        <button id="mobile-menu-button" class="text-[#FEF9E1] focus:outline-none">
          <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
  <!-- Menu Mobile -->
  <div id="mobile-menu" class="md:hidden hidden px-4 pb-3 space-y-1 bg-[#A31D1D]">
    <a href="#home" class="block text-[#FEF9E1] hover:text-white">Home</a>
    <a href="#jenis-tes" class="block text-[#FEF9E1] hover:text-white">Jenis Tes</a>
    <a href="#booking" class="block text-[#FEF9E1] hover:text-white">Booking</a>
    <a href="#hasil-tes" class="block text-[#FEF9E1] hover:text-white">Hasil Tes</a>
    <a href="#location" class="block text-[#FEF9E1] hover:text-white">Location</a>
    <a href="#faq" class="block text-[#FEF9E1] hover:text-white">FAQ</a>
    <a href="#about" class="block text-[#FEF9E1] hover:text-white">About Us</a>
  </div>
</nav>

<script>
  // Toggle menu mobile
  const btn = document.getElementById('mobile-menu-button');
  const menu = document.getElementById('mobile-menu');
  btn.addEventListener('click', () => {
    menu.classList.toggle('hidden');
  });
</script>

<!-- Spacer supaya konten tidak ketutup navbar -->
<div class="h-16"></div>
<!-- === NAVBAR SELESAI === -->

<!-- Hero Section -->
<section id="home" class="flex flex-col md:flex-row items-center justify-between bg-[#FEF9E1] h-[90vh]">
  <!-- Kiri -->
  <div class="md:w-1/2 px-6 md:px-20 flex flex-col justify-center h-[90vh]">
    <h1 class="text-5xl font-extrabold text-[#6D2323] mb-6">Tes Laboratorium</h1>
    <p class="text-lg text-[#A31D1D] leading-relaxed mb-6">
      Pemeriksaan kesehatan menggunakan sampel darah, urine, atau cairan tubuh lainnya 
      yang dapat dimanfaatkan antara lain untuk tujuan skrining, membantu diagnosis penyakit, 
      memantau perjalanan penyakit, dan menentukan prognosis.
    </p>
    <div class="flex gap-4">
      <a href="#booking" class="bg-[#A31D1D] text-white px-6 py-3 rounded-full hover:bg-[#6D2323]">Booking Now</a>
      <a href="#jenis-tes" class="bg-transparent border-2 border-[#A31D1D] text-[#A31D1D] px-6 py-3 rounded-full hover:bg-[#6D2323] hover:text-white">Jenis Tes</a>
    </div>
  </div>

  <!-- Kanan (Gambar) -->
  <div class="md:w-1/2 h-[90vh] flex items-center justify-center">
    <img src="Gambar 3_Jenis Tes.jpg" 
         alt="Tes Laboratorium" 
         class="w-full h-[80%] object-cover object-center rounded-l-[999px]">
  </div>
</section>

  <!-- Promo -->
  <section class="bg-[#E5D0AC] py-10 px-6 mt-12">
    <h2 class="text-3xl md:text-4xl font-extrabold text-[#6D2323] text-center mb-6">Promo Menarik</h2>
    <div class="flex justify-center">
      <div class="w-full max-w-[500px] bg-white shadow-md rounded-2xl flex items-center justify-center relative overflow-hidden">
        <div id="slider" class="w-full flex justify-center items-center aspect-[3/4]">
          <img src="Promo 1.png" class="w-full h-full object-contain absolute inset-0 opacity-100 transition-opacity duration-700">
          <img src="Promo 2.png" class="w-full h-full object-contain absolute inset-0 opacity-0 transition-opacity duration-700">
        </div>
      </div>
    </div>
  </section>

  <!-- Booking -->
  <section id="booking" class="text-center py-12">
    <h2 class="text-3xl font-bold text-[#6D2323] mb-4">Pesan Tes Lab Sekarang</h2>
    <p class="text-lg text-[#A31D1D] mb-6">Klik tombol di bawah untuk booking pemeriksaan laboratorium.</p>
    <button class="bg-[#A31D1D] text-white px-6 py-3 rounded-full hover:bg-[#6D2323]">Booking Tes Lab</button>
  </section>

  <!-- Jenis Tes -->
  <section id="jenis-tes" class="py-12 px-6">
    <h2 class="text-3xl font-bold text-center text-[#6D2323] mb-10">Jenis Tes</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-5xl mx-auto">
      <?php foreach ($tes as $i => $item): ?>
        <div class="text-center">
          <a href="subtes.php?jenis=<?= $i ?>">
            <img src="<?= $item['icon'] ?>" alt="<?= $item['name'] ?>" class="w-20 h-20 mx-auto">
            <p class="mt-2 font-semibold text-[#A31D1D]"><?= $item['name'] ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <script>
    // Slider otomatis
    const slides = document.querySelectorAll('#slider img');
    let current = 0;
    setInterval(() => {
      slides[current].classList.remove('opacity-100');
      slides[current].classList.add('opacity-0');
      current = (current + 1) % slides.length;
      slides[current].classList.remove('opacity-0');
      slides[current].classList.add('opacity-100');
    }, 3000);
  </script>
</body>
</html>
