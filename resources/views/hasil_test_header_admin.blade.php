@extends('layouts.app')

@section('content')
  <style>
    .card { background: #FFFFFF; border: 1px solid #FED7AA; border-radius: 12px; padding: 18px; margin: 16px auto; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 6px rgba(0,0,0,0.06); max-width: 1100px; }
    .left { flex: 1; font-weight: bold; color: #6D2323; font-size: 16px; }
    .middle { flex: 2; display: flex; justify-content: center; text-align: center; gap: 40px; }
    .field { background-color: #6D2323; color: #FFF7ED; padding: 8px 24px; border-radius: 20px; font-weight: bold; margin-bottom: 8px; display: inline-block; min-width: 160px; }
    .right { flex: 1; text-align: center; }
    .btn { display:inline-block; background-color: #6D2323; color: #fff; padding: 8px 16px; border-radius: 20px; text-decoration: none; font-weight: bold; }
  </style>
  <h1 style="text-align:center;color:#6D2323;">Kelola Hasil Tes Laboratorium</h1>

  @forelse($items as $item)
  <div class="card">
    <div class="left">
      Booking ID <br> {{ $item->booking_id ?? ('HS-'.$item->id) }}
    </div>
    <div class="middle">
      <div>
        <div class="field">Tanggal Input</div>
        <div>{{ \Illuminate\Support\Carbon::parse($item->tanggal_input ?? now())->format('d-m-Y') }}</div>
      </div>
      <div>
        <div class="field">Dibuat Oleh</div>
        <div>{{ optional($item->pembuat)->username ?? 'System' }}</div>
      </div>
    </div>
    <div class="right">
      <a href="#" class="btn">Detail</a>
    </div>
  </div>
  @empty
    <p style="text-align:center;color:#9A3412;">Belum ada hasil tersedia.</p>
  @endforelse
@endsection


