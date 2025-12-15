<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tentang Kami - E-Clinic Lab</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #FEF9E1;
      color: #333;
    }

    /* HERO */
    .hero {
      background: url('about 1.jpeg') center/cover no-repeat;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      position: relative;
    }
    .hero::after {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(229,208,172,0.85);
    }
    .hero h1, .hero p {
      position: relative;
      z-index: 1;
      color: #A31D1D;
    }
    .hero h1 {
      font-size: 60px;
      margin: 0;
    }
    .hero p {
      font-size: 22px;
      margin-top: 15px;
    }

    /* ABOUT */
    .section {
      max-width: 1200px;
      margin: 80px auto;
      padding: 20px;
      display: flex;
      align-items: flex-start;
      gap: 40px;
    }
    .section img {
      width: 30%;
      border-radius: 10px;
    }
    .section-text {
      flex: 1;
      text-align: justify;
    }
    .section-text h2 {
      color: #A31D1D;
      font-size: 32px;
      margin-bottom: 15px;
    }
    .section-text p {
      line-height: 1.7;
    }
    .mission-list {
      margin: 20px 0;
      padding-left: 20px;
    }
    .mission-list li {
      margin-bottom: 10px;
      text-align: justify;
    }

    /* VISI MISI */
    .vision-mission {
      background: #E5D0AC;
      padding: 60px 20px;
      text-align: center;
      width: 100%;
      margin: 0;
    }
    .vision-mission h2 {
      color: #A31D1D;
      font-size: 28px;
      margin-bottom: 15px;
    }
    .vision-mission p {
      font-style: italic;
      font-size: 18px;
      margin: 10px auto 40px;
      max-width: 800px;
      text-align: center; /* agar paragraf benar-benar center */
    }

    /* TEAM */
    .team {
      padding: 50px 20px;
      text-align: center;
    }
    .team h2 {
      color: #A31D1D;
      margin-bottom: 40px;
    }
    .team-container {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 20px;
      justify-items: center;
    }
    .team-card {
      background: white;
      border: 2px solid #E5D0AC;
      border-radius: 10px;
      width: 180px;
      padding: 15px;
      text-align: center;
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .team-card img {
      width: 100%;
      border-radius: 10px;
    }
    .team-card h3 {
      margin: 10px 0 5px;
      color: #A31D1D;
    }
    .team-card p {
      font-size: 14px;
      color: #555;
    }

    /* BRANCHES */
    .branches {
      padding: 60px 20px;
      max-width: 1200px;
      margin: auto;
    }
    .branches h2 {
      text-align: center;
      color: #A31D1D;
      margin-bottom: 30px;
    }
    .branches-container {
      display: flex;
      gap: 30px;
      align-items: flex-start;
    }
    .branch-list {
      flex: 1;
    }
    .branch {
      background: #fff;
      border: 2px solid #E5D0AC;
      border-radius: 10px;
      padding: 15px;
      margin-bottom: 15px;
      cursor: pointer;
      transition: 0.3s;
    }
    .branch:hover {
      background: #E5D0AC;
    }
    .branch h3 {
      margin: 0 0 5px;
      color: #6D2323;
    }
    .branch p {
      font-size: 14px;
      margin: 0;
    }
    .branch-map {
      flex: 2;
    }
    .branch-map iframe {
      width: 100%;
      height: 400px;
      border: 0;
      border-radius: 10px;
    }

    /* Responsive */
    @media(max-width: 1000px) {
      .team-container {
        grid-template-columns: repeat(2, 1fr);
      }
      .section {
        flex-direction: column;
      }
      .section img {
        width: 100%;
      }
      .branches-container {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>

  <!-- HERO -->
  <div class="hero">
    <h1>Tentang Kami</h1>
    <p>Berpengalaman dalam melayani kebutuhan medis Anda</p>
  </div>

  <!-- ABOUT -->
  <div class="section">
    <img src="about 2.jpeg" alt="Tentang Kami">
    <div class="section-text">
      <h2>Tentang E-Clinic Lab</h2>
      <p><b>Berdiri pada 23 September</b>, E-Clinic Lab hadir sebagai sistem informasi laboratorium klinik berbasis website dengan misi menghadirkan layanan kesehatan digital yang terintegrasi, efisien, transparan, dan aman.</p>

      <p>Sistem ini dirancang untuk menjawab kebutuhan pasien yang menginginkan proses pemeriksaan medis tanpa prosedur administrasi yang rumit. Fokus utama kami meliputi:</p>

      <ul class="mission-list">
        <li><b>Aksesibilitas Layanan Kesehatan:</b> Pasien dapat melakukan pendaftaran, memilih cabang, memesan jadwal, memilih tes, hingga mengakses hasil secara online.</li>
        <li><b>Transparansi Informasi:</b> Sistem memberikan informasi jelas tentang tes, harga, status booking, pembayaran, hingga hasil tes.</li>
        <li><b>Efisiensi Proses:</b> Alur digital mengurangi interaksi administratif manual, mempercepat validasi, input, hingga distribusi hasil tes.</li>
        <li><b>Keamanan & Privasi:</b> Dengan password hashing, enkripsi, dan kontrol akses untuk menjaga data pribadi dan medis.</li>
        <li><b>Pengelolaan Data Terstruktur:</b> Data pasien, booking, dan hasil tes tersimpan rapi untuk analisis, evaluasi mutu, dan keputusan strategis.</li>
      </ul>

      <p>Dengan demikian, sistem ini menjadi solusi digital menyeluruh bagi pasien dan laboratorium dalam meningkatkan kualitas pelayanan, profesionalisme, dan transformasi digital di bidang kesehatan.</p>
    </div>
  </div>

  <!-- VISI MISI -->
  <div class="vision-mission">
    <h2>Visi</h2>
    <p>Menjadi laboratorium klinik digital yang terpercaya, inovatif, dan berkomitmen menghadirkan layanan pemeriksaan terbaik.</p>

    <h2>Misi</h2>
    <p>Menyediakan layanan laboratorium yang efisien, transparan, aman, serta mendukung transformasi digital kesehatan di Indonesia.</p>
  </div>

  <!-- TEAM -->
  <div class="team">
    <h2>Our Team</h2>
    <div class="team-container">
      <div class="team-card">
        <img src="foto 1.jpeg" alt="Team Member 1">
        <h3>Miska Chirzia</h3>
        <p>164231008</p>
      </div>
      <div class="team-card">
        <img src="foto 1.jpeg" alt="Team Member 2">
        <h3>Rachel Sunarko</h3>
        <p>164231025</p>
      </div>
      <div class="team-card">
        <img src="foto 2.jpeg" alt="Team Member 3">
        <h3>I Made Adi Karunia P.</h3>
        <p>164231057</p>
      </div>
      <div class="team-card">
        <img src="foto 1.jpeg" alt="Team Member 4">
        <h3>Sarah Alya Azizah</h3>
        <p>164231105</p>
      </div>
      <div class="team-card">
        <img src="foto 1.jpeg" alt="Team Member 5">
        <h3>Za’ima Rafifa Salsabila</h3>
        <p>164231116</p>
      </div>
    </div>
  </div>

  <!-- CABANG KLINIK -->
  <div class="branches">
    <h2>Cabang Klinik</h2>
    <div class="branches-container">
      <div class="branch-list">
        <div class="branch" onclick="changeMap('cabangA')">
          <h3>Cabang Klinik A</h3>
          <p>Jl. Prof. DR. Moestopo No.47, Pacar Kembang, Kec. Tambaksari, Surabaya, Jawa Timur 60132</p>
        </div>
        <div class="branch" onclick="changeMap('cabangB')">
          <h3>Cabang Klinik B</h3>
          <p>Jl. Airlangga No.4 - 6, Airlangga, Kec. Gubeng, Surabaya, Jawa Timur 60115</p>
        </div>
        <div class="branch" onclick="changeMap('cabangC')">
          <h3>Cabang Klinik C</h3>
          <p>Jl. Dr. Ir. H. Soekarno, Mulyorejo, Kec. Mulyorejo, Surabaya, Jawa Timur 60115</p>
        </div>
      </div>
      <div class="branch-map">
        <iframe id="mapFrame"
          src="https://www.google.com/maps?q=Cabang+A,+Jl.+Prof.+DR.+Moestopo+No.47,+Surabaya&output=embed"
          allowfullscreen="" loading="lazy">
        </iframe>
      </div>
    </div>
  </div>

  <script>
    function changeMap(branch) {
      let mapFrame = document.getElementById("mapFrame");
      if (branch === "cabangA") {
        mapFrame.src = "https://www.google.com/maps?q=Cabang+A,+Jl.+Prof.+DR.+Moestopo+No.47,+Surabaya&output=embed";
      } else if (branch === "cabangB") {
        mapFrame.src = "https://www.google.com/maps?q=Jl.+Airlangga+No.4-6,+Surabaya&output=embed";
      } else if (branch === "cabangC") {
        mapFrame.src = "https://www.google.com/maps?q=Jl.+Dr.+Ir.+H.+Soekarno,+Mulyorejo,+Surabaya&output=embed";
      }
    }
  </script>

</body>
</html>
