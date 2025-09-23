@extends('layouts.admin')

@section('content')
  <div class="card">
    <h3 style="margin-top:0;color:#A31D1D;">Test Management</h3>
    @if(session('success'))
      <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="card" style="margin-bottom:16px; padding:12px;">
      <form method="POST" action="{{ route('admin.tests.header.store') }}" class="form-inline">
        @csrf
        <label>Pilih Booking:</label>
        <select name="booking_id" class="select" required>
          <option value="">- pilih -</option>
          @foreach($bookings as $b)
            <option value="{{ $b->booking_id }}">#{{ $b->booking_id }} - {{ $b->pasien->nama ?? 'Tanpa Nama' }}</option>
          @endforeach
        </select>
        <button class="btn" type="submit">Buat Hasil Tes</button>
      </form>
    </div>

    <div class="headers">
      @forelse($headers as $header)
        <div class="test-card">
          <h4>Hasil Tes #{{ $header->hasil_id }} - {{ $header->booking->pasien->nama ?? '-' }}</h4>
          <div class="row actions">
            <form method="POST" action="{{ route('admin.tests.header.update', $header) }}" style="display:flex; gap:8px; align-items:center;">
              @csrf
              @method('PUT')
              <select name="booking_id" class="select">
                @foreach($bookings as $b)
                  <option value="{{ $b->booking_id }}" {{ $header->booking_id===$b->booking_id ? 'selected' : '' }}>#{{ $b->booking_id }} - {{ $b->pasien->nama ?? 'Tanpa Nama' }}</option>
                @endforeach
              </select>
              <button class="btn" type="submit">Simpan</button>
            </form>
            <form method="POST" action="{{ route('admin.tests.header.destroy', $header) }}">
              @csrf
              @method('DELETE')
              <button class="btn cancel" type="submit" onclick="return confirm('Hapus header ini?')">Hapus</button>
            </form>
          </div>
          <div class="values">
            <h5>Nilai Parameter</h5>
            <ul>
              @foreach($header->detailHasil as $val)
                <li>
                  <form method="POST" action="{{ route('admin.tests.value.update', [$header, $val]) }}" style="display:inline-flex; gap:8px; align-items:center;">
                    @csrf
                    @method('PUT')
                    <select name="param_id" class="select">
                      @foreach($parameters as $p)
                        <option value="{{ $p->param_id }}" {{ $val->param_id===$p->param_id ? 'selected' : '' }}>{{ $p->nama_parameter ?? ('Param '.$p->param_id) }}</option>
                      @endforeach
                    </select>
                    <input type="text" name="nilai_hasil" value="{{ $val->nilai_hasil }}" class="input small" />
                    <button class="btn" type="submit">Simpan</button>
                  </form>
                  <form method="POST" action="{{ route('admin.tests.value.destroy', [$header, $val]) }}" style="display:inline; margin-left:6px;">
                    @csrf
                    @method('DELETE')
                    <button class="btn cancel" type="submit">Hapus</button>
                  </form>
                </li>
              @endforeach
            </ul>

            <form method="POST" action="{{ route('admin.tests.value.store', $header) }}" style="margin-top:8px; display:flex; gap:8px; align-items:center;">
              @csrf
              <select name="param_id" class="select" required>
                <option value="">- pilih parameter -</option>
                @foreach($parameters as $p)
                  <option value="{{ $p->param_id }}">{{ $p->nama_parameter ?? ('Param '.$p->param_id) }}</option>
                @endforeach
              </select>
              <input type="text" name="nilai_hasil" placeholder="Nilai" class="input small" required />
              <button class="btn" type="submit">Tambah Nilai</button>
            </form>
          </div>
        </div>
      @empty
        <p>Belum ada hasil tes.</p>
      @endforelse
    </div>

    <div class="pagination">{{ $headers->links() }}</div>
  </div>

<style>
  .container { 
    padding:30px; 
    animation: fadeIn 0.6s ease; 
  }
  h3 {
    font-size: 1.8rem; color: #A31D1D; margin-bottom: 20px;
    position: relative; display:inline-block;
  }
  h3::after {
    content:""; position:absolute; bottom:-6px; left:0;
    width:50%; height:3px; background:#FFD3B6; border-radius:3px;
    animation: growLine 1s ease forwards;
  }
  p { margin-bottom:20px; }
  .test-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap:20px;
  }
  .test-card {
    background:#fff;
    border-radius:15px;
    padding:20px;
    box-shadow:0 6px 14px rgba(0,0,0,0.08);
    transition:transform 0.3s, box-shadow 0.3s;
  }
  .test-card:hover {
    transform:translateY(-6px);
    box-shadow:0 10px 18px rgba(0,0,0,0.15);
  }
  .test-card h4 {
    margin:0 0 10px;
    color:#A31D1D;
  }
  .btn {
    padding:8px 16px;
    border:none;
    background:#A31D1D;
    color:#FEF9E1;
    font-weight:bold;
    border-radius:8px;
    cursor:pointer;
    transition:background 0.3s, transform 0.2s;
  }
  .btn:hover {
    background:#8c1717;
    transform:scale(1.05);
  }
  .alert.success { background:#e8f7ee; color:#2e7d32; padding:10px 12px; border-radius:8px; margin-bottom:12px; }
  .form-inline .select, .form-inline .input { padding:8px 10px; border:1px solid #ddd; border-radius:8px; margin-right:8px; }
  .select { padding:8px 10px; border:1px solid #ddd; border-radius:8px; }
  .input.small { width:160px; }
  @keyframes fadeIn { 
    from{opacity:0; transform:translateY(15px);} 
    to{opacity:1; transform:translateY(0);} 
  }
  @keyframes growLine { 
    from{width:0;} to{width:50%;} 
  }
  </style>
@endsection
