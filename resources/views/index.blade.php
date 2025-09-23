@extends('layouts.app')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap');
  /* Hero Section */
  .hero-section {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #FEF9E1, #fff4c7);
    min-height: 90vh;
    padding: 20px;
  }
  
  .hero-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    max-width: 1200px;
    width: 100%;
  }
  
  .hero-left {
    flex: 1;
    padding: 20px;
  }
  
  .hero-right {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .hero-title {
    font-family: 'Poppins', sans-serif;
    font-size: 3.2rem;
    font-weight: 700;
    color: #6D2323;
    margin-bottom: 1.5rem;
    line-height: 1.2;
    letter-spacing: -0.02em;
  }
  
  .hero-description {
    font-family: 'Open Sans', sans-serif;
    font-size: 1.125rem;
    color: #A31D1D;
    line-height: 1.6;
    margin-bottom: 2rem;
    max-width: 600px;
    font-weight: 400;
  }
  
  .hero-buttons {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
  }
  
  .btn-primary {
    font-family: 'Open Sans', sans-serif;
    background: #A31D1D;
    color: white;
    padding: 12px 24px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
  }
  
  .btn-primary:hover {
    background: #6D2323;
    transform: translateY(-2px);
  }
  
  .btn-secondary {
    font-family: 'Open Sans', sans-serif;
    background: transparent;
    border: 2px solid #A31D1D;
    color: #A31D1D;
    padding: 10px 24px;
    border-radius: 50px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
  }
  
  .btn-secondary:hover {
    background: #6D2323;
    color: white;
    transform: translateY(-2px);
  }
  
  .hero-image {
    width: 100%;
    max-width: 500px;
    height: 400px;
    object-fit: cover;
    border-radius: 0 0 0 999px;
  }
  
  /* Promo Section */
  .promo-section {
    background: #E5D0AC;
    padding: 3rem 1.5rem;
    margin-top: 3rem;
  }
  
  .promo-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.8rem;
    font-weight: 700;
    color: #6D2323;
    text-align: center;
    margin-bottom: 2rem;
    letter-spacing: -0.02em;
  }
  
  .promo-container {
    display: flex;
    justify-content: center;
    max-width: 500px;
    margin: 0 auto;
  }
  
  .promo-slider {
    width: 100%;
    background: white;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    border-radius: 1rem;
    overflow: hidden;
    position: relative;
    aspect-ratio: 3/4;
  }
  
  .promo-slide {
    width: 100%;
    height: 100%;
    object-fit: contain;
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
    transition: opacity 0.7s ease;
  }
  
  .promo-slide.active {
    opacity: 1;
  }
  
  /* Booking Section */
  .booking-section {
    text-align: center;
    padding: 3rem 1.5rem;
  }
  
  .booking-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: #6D2323;
    margin-bottom: 1rem;
    letter-spacing: -0.02em;
  }
  
  .booking-description {
    font-family: 'Open Sans', sans-serif;
    font-size: 1.125rem;
    color: #A31D1D;
    margin-bottom: 2rem;
    font-weight: 400;
  }
  
  /* Test Types Section */
  .test-types-section {
    padding: 3rem 1.5rem;
  }
  
  .test-types-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.2rem;
    font-weight: 700;
    text-align: center;
    color: #6D2323;
    margin-bottom: 2.5rem;
    letter-spacing: -0.02em;
  }
  
  .test-types-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    max-width: 800px;
    margin: 0 auto;
  }
  
  .test-type-card {
    text-align: center;
    padding: 1.5rem;
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  
  .test-type-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.15);
  }
  
  .test-type-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1rem;
    display: block;
  }
  
  .test-type-name {
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    color: #A31D1D;
    font-size: 1rem;
  }
  
  /* Responsive */
  @media (min-width: 768px) {
    .hero-content {
      flex-direction: row;
      text-align: left;
    }
    
    .hero-left {
      padding-right: 2rem;
    }
    
    .hero-buttons {
      justify-content: flex-start;
    }
    
    .hero-title {
      font-size: 4rem;
    }
    
    .test-types-grid {
      grid-template-columns: repeat(4, 1fr);
    }
  }
</style>

<!-- Hero Section -->
<section class="hero-section">
  <div class="hero-content">
    <div class="hero-left">
      <h1 class="hero-title">Tes Laboratorium</h1>
      <p class="hero-description">
        Pemeriksaan kesehatan menggunakan sampel darah, urine, atau cairan tubuh lainnya 
        yang dapat dimanfaatkan antara lain untuk tujuan skrining, membantu diagnosis penyakit, 
        memantau perjalanan penyakit, dan menentukan prognosis.
      </p>
      <div class="hero-buttons">
        <a href="{{ route('booking.index') }}" class="btn-primary">Booking Now</a>
        <a href="#jenis-tes" class="btn-secondary">Jenis Tes</a>
      </div>
    </div>
    <div class="hero-right">
      <img src="{{ asset('images/lab_project/Gambar 3_Jenis Tes.jpg') }}" alt="Tes Laboratorium" class="hero-image">
    </div>
  </div>
</section>

<!-- Promo Section -->
<section class="promo-section">
  <h2 class="promo-title">Promo Menarik</h2>
  <div class="promo-container">
    <div class="promo-slider">
      <img src="{{ asset('images/lab_project/Promo 1.png') }}" alt="Promo 1" class="promo-slide active">
      <img src="{{ asset('images/lab_project/Promo 2.png') }}" alt="Promo 2" class="promo-slide">
    </div>
  </div>
</section>

<!-- Booking Section -->
<section class="booking-section">
  <h2 class="booking-title">Pesan Tes Lab Sekarang</h2>
  <p class="booking-description">Klik tombol di bawah untuk booking pemeriksaan laboratorium.</p>
  <a href="{{ route('booking.index') }}" class="btn-primary">Booking Tes Lab</a>
</section>

<!-- Test Types Section -->
<section id="jenis-tes" class="test-types-section">
  <h2 class="test-types-title">Jenis Tes</h2>
  <div class="test-types-grid">
    <div class="test-type-card">
      <a href="{{ route('booking.index') }}?type=darah">
        <img src="{{ asset('images/lab_project/Icon 1_Darah.png') }}" alt="Tes Darah" class="test-type-icon">
        <p class="test-type-name">Tes Darah</p>
      </a>
    </div>
    <div class="test-type-card">
      <a href="{{ route('booking.index') }}?type=urine">
        <img src="{{ asset('images/lab_project/Icon 2_Urine.png') }}" alt="Tes Urine" class="test-type-icon">
        <p class="test-type-name">Tes Urine</p>
      </a>
    </div>
    <div class="test-type-card">
      <a href="{{ route('booking.index') }}?type=kehamilan">
        <img src="{{ asset('images/lab_project/Icon 3_Kehamilan.png') }}" alt="Tes Kehamilan" class="test-type-icon">
        <p class="test-type-name">Tes Kehamilan</p>
      </a>
    </div>
    <div class="test-type-card">
      <a href="{{ route('booking.index') }}?type=gigi">
        <img src="{{ asset('images/lab_project/Icon 4_Gigi.png') }}" alt="Tes Gigi" class="test-type-icon">
        <p class="test-type-name">Tes Gigi</p>
      </a>
    </div>
  </div>
</section>

<script>
  // Promo slider functionality
  const slides = document.querySelectorAll('.promo-slide');
  let currentSlide = 0;
  
  setInterval(() => {
    slides[currentSlide].classList.remove('active');
    currentSlide = (currentSlide + 1) % slides.length;
    slides[currentSlide].classList.add('active');
  }, 3000);
  
  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
</script>
@endsection