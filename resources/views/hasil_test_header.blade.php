@extends('layouts.app')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap');
  
  .test-results-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
  }

  .test-results-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.8rem;
    font-weight: 700;
    color: #A31D1D;
    text-align: center;
    margin-bottom: 3rem;
    letter-spacing: -0.02em;
  }

  .result-card {
    background: white;
    border-radius: 20px;
    padding: 32px;
    margin: 24px auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 8px 32px rgba(163, 29, 29, 0.12);
    border: 1px solid rgba(163, 29, 29, 0.08);
    max-width: 1100px;
    opacity: 0;
    transform: translateY(30px);
    animation: slideUp 0.8s ease forwards;
    transition: all 0.3s ease;
  }

  .result-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 40px rgba(163, 29, 29, 0.18);
  }

  .result-card:nth-child(2) { animation-delay: 0.1s; }
  .result-card:nth-child(3) { animation-delay: 0.2s; }
  .result-card:nth-child(4) { animation-delay: 0.3s; }

  @keyframes slideUp {
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .result-booking-id {
    flex: 1;
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    color: #A31D1D;
    font-size: 1.1rem;
    line-height: 1.4;
  }

  .result-booking-id .label {
    font-size: 0.9rem;
    color: #6B7280;
    font-weight: 500;
    display: block;
    margin-bottom: 4px;
  }

  .result-booking-id .value {
    font-size: 1.2rem;
    color: #A31D1D;
    font-weight: 800;
  }

  .result-middle {
    flex: 2;
    display: flex;
    justify-content: center;
    text-align: center;
    gap: 60px;
  }

  .result-field {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .result-field-label {
    font-family: 'Open Sans', sans-serif;
    background: linear-gradient(135deg, #A31D1D, #8C1717);
    color: white;
    padding: 12px 24px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 12px;
    letter-spacing: 0.02em;
    box-shadow: 0 4px 12px rgba(163, 29, 29, 0.3);
  }

  .result-field-value {
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    color: #374151;
    font-size: 1rem;
  }

  .result-download {
    flex: 1;
    text-align: center;
  }

  .download-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
    padding: 14px 28px;
    border-radius: 50px;
    text-decoration: none;
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(16, 185, 129, 0.3);
  }

  .download-btn:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
  }

  .download-btn::before {
    content: "📄";
    font-size: 1.1rem;
  }

  .no-results {
    text-align: center;
    color: #6B7280;
    font-family: 'Open Sans', sans-serif;
    font-size: 1.1rem;
    font-weight: 500;
    margin-top: 3rem;
    padding: 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(163, 29, 29, 0.08);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .result-card {
      flex-direction: column;
      gap: 24px;
      text-align: center;
    }
    
    .result-middle {
      flex-direction: column;
      gap: 24px;
    }
    
    .test-results-title {
      font-size: 2rem;
    }
  }
</style>

<div class="test-results-container">
  <h1 class="test-results-title">Silakan Unduh Hasil Tes Laboratorium Kamu</h1>

  @forelse($items as $item)
  <div class="result-card">
    <div class="result-booking-id">
      <span class="label">Booking ID</span>
      <span class="value">{{ $item->booking_id ?? ('HS-'.$item->id) }}</span>
    </div>
    <div class="result-middle">
      <div class="result-field">
        <div class="result-field-label">Tanggal Input</div>
        <div class="result-field-value">{{ \Illuminate\Support\Carbon::parse($item->tanggal_input ?? now())->format('d-m-Y') }}</div>
      </div>
      <div class="result-field">
        <div class="result-field-label">Dibuat Oleh</div>
        <div class="result-field-value">{{ optional($item->pembuat)->username ?? 'System' }}</div>
      </div>
    </div>
    <div class="result-download">
      <a href="#" class="download-btn">Unduh Hasil</a>
    </div>
  </div>
  @empty
    <div class="no-results">
      Belum ada hasil tersedia.
    </div>
  @endforelse
</div>
@endsection
