<x-guest-layout>
  <x-slot name="title">Create Account</x-slot>

  <div class="auth-page-wrapper">
    <!-- Header -->
    <header class="auth-header-nav">
      <div class="brand">
        <div class="brand-name"><img src="{{ asset('logo.png') }}" alt="Logo" style="width: 150px; height: 50px;"></div>
        <div class="brand-contact">pmrsteam.official@gmail.com &nbsp;&nbsp;&rarr;</div>
      </div>
      <div class="nav-links">
        <a href="{{ route('login') }}" class="btn-demo" style="font-size: 15px; padding: 12px 28px;">Sign in</a>
      </div>
    </header>

    <!-- BG decorations -->
    <div class="bg-decor bg-decor-left bg-dot-box"></div>
    <div class="bg-decor bg-decor-arrow">
      <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17l9.2-9.2M17 17V7H7" /></svg>
    </div>
    <div class="bg-decor chat-bubble-1"><div class="line1"></div><div class="line2"></div></div>
    <div class="bg-decor chat-bubble-2"><div class="line1"></div><div class="line2"></div></div>
    <svg class="bg-decor bg-wire-1" width="120" height="60" viewBox="0 0 120 60"><path d="M0 30 Q 30 0, 60 30 T 120 30" fill="none" stroke="#666" stroke-width="1" /></svg>
    <svg class="bg-decor bg-wire-2" width="100" height="50" viewBox="0 0 100 50"><path d="M0 25 Q 25 50, 50 25 T 100 25" fill="none" stroke="#666" stroke-width="1" /></svg>
    <div class="bg-decor bg-decor-right bg-dot-box"></div>
    <div class="bg-character"></div>

    <!-- Center Card -->
    <main class="auth-main">
      <div class="auth-card-modern">
        <h1>Create your account</h1>
        <p class="subtitle">Set up your institution administrator<br/>account to get started</p>

        <form method="POST" action="{{ route('register') }}" id="register-form">
          @csrf

          <div class="form-group-modern">
            <input type="text" name="school_name" value="{{ old('school_name') }}" placeholder="School / Institution Name" required autofocus>
            <span class="icon-circle"></span>
          </div>
          @error('school_name')<span class="form-error">{{ $message }}</span>@enderror

          <div class="form-group-modern">
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Admin Full Name" required>
            <span class="icon-circle"></span>
          </div>
          @error('name')<span class="form-error">{{ $message }}</span>@enderror

          <div class="form-group-modern">
            <input type="email" name="email" value="{{ old('email', request('email')) }}" placeholder="Email Address" required>
            <span class="icon-circle"></span>
          </div>
          @error('email')<span class="form-error">{{ $message }}</span>@enderror

          <div class="form-group-modern">
            <input type="password" id="password-input" name="password" placeholder="Password (min 8 chars)" required>
            <span class="icon-text" id="toggle-pwd" onclick="togglePassword()">Hide</span>
          </div>
          @error('password')<span class="form-error">{{ $message }}</span>@enderror

          <div class="form-group-modern">
            <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
            <span class="icon-circle"></span>
          </div>

          <button type="submit" class="btn-submit">Create Account →</button>

          <p class="register-link">Already have an account? <a href="{{ route('login') }}">Sign in</a></p>
        </form>
      </div>
    </main>

    <footer class="auth-footer-nav">
      Copyright @PMRS 2026 | Privacy Policy
    </footer>
  </div>

  <script>
    function togglePassword() {
      const pwd = document.getElementById('password-input');
      const btn = document.getElementById('toggle-pwd');
      if (pwd.type === 'password') { pwd.type = 'text'; btn.innerText = 'Show'; }
      else { pwd.type = 'password'; btn.innerText = 'Hide'; }
    }
  </script>
</x-guest-layout>

<style>
  .auth-page-wrapper {
    background-color: #fbf9f2;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
    font-family: 'Poppins', 'Inter', sans-serif;
    margin: -24px;
  }
  .auth-header-nav {
    display: flex;
    justify-content: space-between;
    padding: 30px 60px;
    align-items: flex-start;
    position: relative;
    z-index: 10;
  }
  .brand { display: flex; flex-direction: column; gap: 8px; }
  .brand-name { font-size: 26px; font-weight: 800; color: #111; letter-spacing: -0.5px; }
  .brand-contact { font-size: 11px; color: #666; border-top: 1px solid #ddd; padding-top: 8px; width: 120px; }
  .nav-links { display: flex; align-items: center; gap: 24px; }
  .nav-links a { color: #111; text-decoration: none; font-size: 14px; font-weight: 600; }

  .bg-decor { position: absolute; z-index: 1; }
  .bg-dot-box { width: 80px; height: 180px; background-color: #ffcc99; background-image: radial-gradient(#111 15%, transparent 15%); background-size: 20px 20px; }
  .bg-decor-left { bottom: 12%; left: 10%; }
  .bg-decor-right { bottom: 14%; right: 12%; }
  .bg-decor-arrow { width: 70px; height: 90px; border: 1px solid #111; background: #fff; bottom: 12%; left: 17%; display: flex; align-items: center; justify-content: center; z-index: 2; }
  .bg-wire-1 { top: 30%; left: 12%; }
  .bg-wire-2 { top: 40%; right: 4%; }
  .chat-bubble-1 { top: 42%; left: 14%; width: 60px; height: 50px; border: 1px solid #111; background: #fff; }
  .chat-bubble-1::after { content: ""; position: absolute; bottom: -10px; left: 10px; border-width: 10px 10px 0 0; border-style: solid; border-color: #ffcc99 transparent transparent transparent; }
  .chat-bubble-1 .line1 { width: 30px; height: 2px; background: #111; margin: 15px auto 8px; }
  .chat-bubble-1 .line2 { width: 20px; height: 2px; background: #111; margin: 0 auto; }
  .chat-bubble-2 { top: 32%; right: 26%; width: 50px; height: 40px; border: 1px solid #111; background: #fff; }
  .chat-bubble-2::after { content: ""; position: absolute; bottom: -8px; right: 8px; border-width: 0 8px 8px 0; border-style: solid; border-color: transparent #ffcc99 transparent transparent; }
  .chat-bubble-2 .line1 { width: 25px; height: 2px; background: #111; margin: 12px auto 6px; }
  .chat-bubble-2 .line2 { width: 15px; height: 2px; background: #111; margin: 0 auto; }
  .bg-character { position: absolute; bottom: 0%; right: 17%; width: 180px; height: 250px; z-index: 2; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 250'%3E%3Cpath d='M100 80c10 0 20 10 20 25s-10 20-20 20-20-10-20-20 10-25 20-25z' fill='%23111'/%3E%3Cpath d='M100 120l30 50v60M100 120l-20 70 30 10' fill='none' stroke='%23111' stroke-width='3'/%3E%3Crect x='110' y='180' width='50' height='70' fill='%23fff' stroke='%23111' stroke-width='2'/%3E%3Cpath d='M70 140l-20-15v-25' fill='none' stroke='%23cddc39' stroke-width='10' stroke-linecap='round'/%3E%3Cpath d='M100 85c5-10 20-15 30-5' fill='%23111'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-size: contain; }

  .auth-main { flex: 1; display: flex; justify-content: center; align-items: center; z-index: 10; padding: 20px; }
  .auth-card-modern { background: #ffffff; width: 100%; max-width: 480px; padding: 44px 48px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.61); text-align: center; position: relative; }
  .auth-card-modern h1 { font-size: 26px; font-weight: 700; margin-bottom: 8px; color: #111; }
  .auth-card-modern .subtitle { font-size: 15px; color: #555; margin-bottom: 28px; line-height: 1.5; }
  .form-group-modern { position: relative; margin-bottom: 14px; }
  .form-group-modern input { width: 100%; padding: 16px 20px; border-radius: 12px; border: 1px solid #eaeaea; font-size: 14px; font-family: inherit; outline: none; box-sizing: border-box; color: #111; background: #fff; transition: border-color 0.2s; }
  .form-group-modern input::placeholder { color: #999; }
  .form-group-modern input:focus { border-color: #ffcc99; }
  .form-group-modern .icon-circle { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; border: 1.5px solid #ccc; border-radius: 50%; }
  .form-group-modern .icon-text { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); font-size: 11px; font-weight: 600; color: #111; cursor: pointer; }
  .form-error { display: block; font-size: 12px; color: #ff4d4d; margin-top: -8px; margin-bottom: 10px; text-align: left; }
  .btn-submit { width: 100%; background-color: #ffcc99; color: #111; font-weight: 700; font-size: 15px; padding: 16px; border: none; border-radius: 12px; cursor: pointer; font-family: inherit; margin-bottom: 20px; transition: opacity 0.2s, transform 0.2s; }
  .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
  .register-link { font-size: 12px; color: #666; margin-bottom: 0; }
  .register-link a { color: #111; font-weight: 700; text-decoration: none; }
  .auth-footer-nav { text-align: center; padding: 20px; font-size: 11px; color: #333; font-weight: 600; z-index: 10; }

  @media (max-width: 900px) {
    .bg-decor, .bg-character { display: none; }
    .auth-header-nav { padding: 20px; flex-wrap: wrap; gap: 15px; justify-content: center; }
    .auth-card-modern { padding: 36px 28px; }
  }
</style>
