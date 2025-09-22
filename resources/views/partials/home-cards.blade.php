<div class="grid">
    <div class="card">
        <h3>Hasil Tes</h3>
        <p class="muted">Lihat dan unduh hasil tes kamu.</p>
        <p style="margin-top:10px;"><a href="{{ route('hasil.index') }}" style="color:#6D2323; font-weight:700;">Buka</a></p>
    </div>
    <div class="card">
        <h3>Dashboard</h3>
        <p class="muted">Ringkasan booking dan hasil terbaru.</p>
        <p style="margin-top:10px;"><a href="{{ route('dashboard') }}" style="color:#6D2323; font-weight:700;">Buka</a></p>
    </div>
    @if (session('role') === 'admin')
    <div class="card">
        <h3>Admin: Hasil</h3>
        <p class="muted">Kelola data hasil tes laboratorium.</p>
        <p style="margin-top:10px;"><a href="{{ route('admin.hasil.index') }}" style="color:#6D2323; font-weight:700;">Buka</a></p>
    </div>
    @endif
</div>


