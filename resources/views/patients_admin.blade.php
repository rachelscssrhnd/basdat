@extends('layouts.admin')

@section('content')
  <style>
    .container { padding:0; animation: fadeIn 0.6s ease; }
    h3 {
      font-size: 1.8rem; color: #A31D1D; margin-bottom: 20px;
      position: relative; display:inline-block;
    }
    h3::after {
      content:""; position:absolute; bottom:-6px; left:0;
      width:50%; height:3px; background:#FFD3B6; border-radius:3px;
      animation: growLine 1s ease forwards;
    }
    .search-box { margin-bottom:15px; }
    .search-box input {
      width: 100%; padding: 10px 15px; font-size: 1rem;
      border: 2px solid #ddd; border-radius: 10px;
      transition: all 0.3s ease;
    }
    .search-box input:focus {
      border-color: #A31D1D; outline:none;
      box-shadow: 0 0 8px rgba(163,29,29,0.3);
    }
    table {
      width:100%; border-collapse:collapse; background:#fff;
      border-radius:10px; overflow:hidden; box-shadow:0 6px 14px rgba(0,0,0,0.08);
    }
    thead { background:#A31D1D; color:#FEF9E1; cursor:pointer; }
    th, td { padding:12px 15px; text-align:left; }
    tbody tr { transition: background 0.3s ease; }
    tbody tr:hover { background:#fff4c7; }
    th.sortable:hover { background:#8c1717; }
    @keyframes fadeIn { from{opacity:0; transform:translateY(15px);} to{opacity:1; transform:translateY(0);} }
    @keyframes growLine { from{width:0;} to{width:50%;} }
  </style>
  <div class="container">
    <h3>Patient Management</h3>
    <div class="search-box">
      <input type="text" id="searchInput" placeholder="🔍 Cari pasien...">
    </div>
    <table id="patientTable">
      <thead>
        <tr>
          <th class="sortable">Nama Depan ⬍</th>
          <th class="sortable">Nama Belakang ⬍</th>
          <th class="sortable">Riwayat Tes ⬍</th>
          <th class="sortable">Status ⬍</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Budi</td>
          <td>Setiawan</td>
          <td>Covid-19, Kolesterol</td>
          <td>Sudah Tes</td>
        </tr>
        <tr>
          <td>Siti</td>
          <td>Aisyah</td>
          <td>Diabetes</td>
          <td>Belum Tes</td>
        </tr>
        <tr>
          <td>Andi</td>
          <td>Wijaya</td>
          <td>Kolesterol</td>
          <td>Sudah Tes</td>
        </tr>
      </tbody>
    </table>
  </div>

  <script>
    // === SEARCH ===
    document.getElementById("searchInput").addEventListener("keyup", function() {
      let filter = this.value.toLowerCase();
      let rows = document.querySelectorAll("#patientTable tbody tr");
      rows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(filter) ? "" : "none";
      });
    });

    // === SORTING ===
    document.querySelectorAll("th.sortable").forEach((th, index) => {
      th.addEventListener("click", () => {
        let table = th.closest("table");
        let tbody = table.querySelector("tbody");
        let rows = Array.from(tbody.querySelectorAll("tr"));
        let asc = th.classList.toggle("asc");

        rows.sort((a, b) => {
          let valA = a.children[index].innerText.toLowerCase();
          let valB = b.children[index].innerText.toLowerCase();
          return asc ? valA.localeCompare(valB) : valB.localeCompare(valA);
        });

        rows.forEach(row => tbody.appendChild(row));
      });
    });
  </script>
@endsection
