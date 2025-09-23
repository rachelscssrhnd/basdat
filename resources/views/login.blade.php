@extends('layouts.app')

@section('content')
<style>
  @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Open+Sans:wght@300;400;500;600;700&display=swap');
  .login-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 80vh;
    padding: 20px;
  }
  
  .login-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 12px 28px rgba(0,0,0,0.15);
    padding: 40px;
    width: 100%;
    max-width: 450px;
    border: 1px solid #E5D0AC;
  }
  
  .login-title {
    font-family: 'Poppins', sans-serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: #A31D1D;
    text-align: center;
    margin-bottom: 30px;
    letter-spacing: -0.02em;
  }
  
  .login-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  
  .form-group {
    display: flex;
    flex-direction: column;
  }
  
  .form-label {
    font-family: 'Open Sans', sans-serif;
    font-weight: 600;
    color: #6D2323;
    margin-bottom: 8px;
    font-size: 14px;
  }
  
  .form-input, .form-select {
    font-family: 'Open Sans', sans-serif;
    padding: 12px 16px;
    border: 2px solid #E5D0AC;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
    background: #FEF9E1;
  }
  
  .form-input:focus, .form-select:focus {
    outline: none;
    border-color: #A31D1D;
    box-shadow: 0 0 0 3px rgba(163, 29, 29, 0.1);
  }
  
  .login-button {
    font-family: 'Open Sans', sans-serif;
    background: #A31D1D;
    color: white;
    padding: 14px 24px;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 10px;
  }
  
  .login-button:hover {
    background: #8C1717;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(163, 29, 29, 0.3);
  }
  
  .error-message {
    background: #FEF2F2;
    border: 1px solid #FECACA;
    color: #B91C1C;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 20px;
  }
  
  .login-description {
    font-family: 'Open Sans', sans-serif;
    text-align: center;
    color: #6B7280;
    font-size: 14px;
    margin-top: 20px;
  }
</style>

<div class="login-container">
  <div class="login-card">
    <h1 class="login-title">Masuk ke E-Clinic Lab</h1>
    
    @if ($errors->any())
      <div class="error-message">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}" class="login-form">
      @csrf
      
      <div class="form-group">
        <label for="username" class="form-label">Nama Pengguna</label>
        <input 
          id="username" 
          name="username" 
          type="text" 
          class="form-input"
          placeholder="Masukkan nama pengguna" 
          required
        >
      </div>
      
      <div class="form-group">
        <label for="role" class="form-label">Masuk sebagai</label>
        <select id="role" name="role" class="form-select" required>
          <option value="">-- Pilih Role --</option>
          <option value="user">User (Pasien)</option>
          <option value="admin">Admin (Staf Lab)</option>
        </select>
      </div>
      
      <button type="submit" class="login-button">
        Masuk
      </button>
    </form>
    
    <p class="login-description">
      Pilih role sesuai dengan kebutuhan Anda untuk mengakses fitur yang sesuai.
    </p>
  </div>
</div>
@endsection


