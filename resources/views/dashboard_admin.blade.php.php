<?php include 'home_admin.php'; ?>
<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background: #f7f7f7;
    margin: 0;
  }
  .container {
    padding: 40px;
    animation: fadeIn 0.8s ease;
  }
  .container h3 {
    font-size: 1.8rem;
    color: #A31D1D;
    margin-bottom: 15px;
    position: relative;
    display: inline-block;
  }
  .container h3::after {
    content: "";
    position: absolute;
    bottom: -5px; left: 0;
    width: 50%; height: 3px;
    background: #FFD3B6;
    border-radius: 2px;
    animation: growLine 1s ease forwards;
  }
  .container p {
    font-size: 1.1rem;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 6px 14px rgba(0,0,0,0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }
  .container p:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
  }

  @keyframes fadeIn {
    from {opacity:0; transform: translateY(15px);}
    to {opacity:1; transform: translateY(0);}
  }
  @keyframes growLine {
    from {width: 0;}
    to {width: 50%;}
  }
</style>

<div class="container">
  <h3>Dashboard</h3>
  <p>📊 Dashboard ini masih kosong, akan diisi setelah UTS.</p>
</div>
