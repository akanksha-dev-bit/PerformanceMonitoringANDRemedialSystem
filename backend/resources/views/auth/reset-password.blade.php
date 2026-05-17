<x-guest-layout>
  <x-slot name="title">Reset Password - PMRS</x-slot>

  <div class="auth-page-wrapper">
    <!-- Header -->
    <header class="auth-header-nav">
      <div class="brand">
        <div class="brand-name"><img src="{{ asset('logo.png') }}" alt="Logo" style="width: 150px; height: 50px;"></div>
        <div class="brand-contact">Support@pmrs.edu &nbsp;&nbsp;&rarr;</div>
      </div>
    </header>

    <!-- Center Card -->
    <main class="auth-main">
      <div class="auth-card-modern" style="max-width: 480px;">
        <div style="font-size: 56px; margin-bottom: 16px;">🔑</div>
        <h1>Reset Password</h1>
        <p class="subtitle" style="margin-bottom: 32px; font-size: 15px; color: #555; line-height: 1.6;">
          {{ __('Please enter your new password below.') }}
        </p>

        <form method="POST" action="{{ route('password.store') }}" style="margin-bottom: 24px;">
          @csrf

          <!-- Password Reset Token -->
          <input type="hidden" name="token" value="{{ $request->route('token') }}">

          <!-- Email Address -->
          <div style="margin-bottom: 20px; text-align: left;">
            <input type="email" name="email" value="{{ old('email', $request->email) }}" placeholder="Email Address" required autofocus autocomplete="username"
                   style="width: 100%; padding: 16px 20px; border-radius: 12px; border: 2px solid #eaeaea; font-size: 15px; color: #111; outline: none; transition: border-color 0.2s; box-sizing: border-box; background: #f9fafb;" readonly>
            @error('email')
              <div style="color: #ef4444; font-size: 13px; font-weight: 600; margin-top: 8px;">{{ $message }}</div>
            @enderror
          </div>

          <!-- Password -->
          <div style="margin-bottom: 20px; text-align: left; position: relative;">
            <input type="password" id="password" name="password" placeholder="New Password" required autocomplete="new-password"
                   style="width: 100%; padding: 16px 20px; border-radius: 12px; border: 2px solid #eaeaea; font-size: 15px; color: #111; outline: none; transition: border-color 0.2s; box-sizing: border-box;">
            <span class="icon-text" id="toggle-pwd" onclick="togglePassword('password', 'toggle-pwd')" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); font-size: 11px; font-weight: 600; color: #111; cursor: pointer;">Show</span>
            @error('password')
              <div style="color: #ef4444; font-size: 13px; font-weight: 600; margin-top: 8px;">{{ $message }}</div>
            @enderror
          </div>

          <!-- Confirm Password -->
          <div style="margin-bottom: 24px; text-align: left; position: relative;">
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm New Password" required autocomplete="new-password"
                   style="width: 100%; padding: 16px 20px; border-radius: 12px; border: 2px solid #eaeaea; font-size: 15px; color: #111; outline: none; transition: border-color 0.2s; box-sizing: border-box;">
            <span class="icon-text" id="toggle-pwd-conf" onclick="togglePassword('password_confirmation', 'toggle-pwd-conf')" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); font-size: 11px; font-weight: 600; color: #111; cursor: pointer;">Show</span>
            @error('password_confirmation')
              <div style="color: #ef4444; font-size: 13px; font-weight: 600; margin-top: 8px;">{{ $message }}</div>
            @enderror
          </div>

          <button type="submit" class="btn-submit" style="margin-bottom: 16px;">
            {{ __('Reset Password') }} →
          </button>
        </form>

      </div>
    </main>

    <footer class="auth-footer-nav">
      Copyright @PMRS 2026 | Privacy Policy
    </footer>
  </div>

  <script>
    function togglePassword(inputId, toggleId) {
      const pwd = document.getElementById(inputId);
      const btn = document.getElementById(toggleId);
      if (pwd.type === 'password') { pwd.type = 'text'; btn.innerText = 'Hide'; }
      else { pwd.type = 'password'; btn.innerText = 'Show'; }
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
  
  .auth-main { flex: 1; display: flex; justify-content: center; align-items: center; z-index: 10; padding: 20px; }
  .auth-card-modern { background: #ffffff; width: 100%; padding: 56px 48px; border-radius: 24px; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08); text-align: center; position: relative; }
  .auth-card-modern h1 { font-size: 28px; font-weight: 800; margin-bottom: 12px; color: #111; letter-spacing: -0.02em; }
  
  .btn-submit { width: 100%; background-color: #111; color: #fff; font-weight: 700; font-size: 15px; padding: 18px; border: none; border-radius: 12px; cursor: pointer; font-family: inherit; transition: opacity 0.2s, transform 0.2s; box-shadow: 0 4px 14px rgba(0,0,0,0.1); }
  .btn-submit:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
  input:focus { border-color: #111 !important; }
  
  .auth-footer-nav { text-align: center; padding: 20px; font-size: 12px; color: #666; font-weight: 600; z-index: 10; }

  @media (max-width: 900px) {
    .auth-header-nav { padding: 20px; flex-wrap: wrap; justify-content: center; }
    .auth-card-modern { padding: 40px 24px; }
  }
</style>
