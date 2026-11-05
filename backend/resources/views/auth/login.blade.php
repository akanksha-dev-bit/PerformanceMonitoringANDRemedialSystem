<x-guest-layout>
  <x-slot name="title">Sign in</x-slot>

  <div class="login-shell">
    <header class="login-header">
      <div class="login-brand" aria-label="PMRS">
        <img src="{{ asset('logo.png') }}" alt="PMRS" width="132" height="33">
        <span>pmrsteam.official@gmail.com</span>
      </div>
      <div class="login-header-action">
        <span>New to PMRS?</span>
        <a href="{{ route('register') }}">Create account <span aria-hidden="true">↗</span></a>
      </div>
    </header>

    <main class="login-main">
      <section class="login-card" aria-labelledby="login-title">
        <div class="login-intro">
          <h1 id="login-title">Sign in to PMRS</h1>
          <p>Enter your account details to continue managing your school.</p>
        </div>

        @if (session('status'))
          <div class="login-status" role="status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="login-field">
            <label for="login-email">Email address</label>
            <input id="login-email" type="email" name="email" value="{{ old('email') }}" placeholder="name@school.edu" autocomplete="username" inputmode="email" required aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
            @error('email')<span class="login-error" role="alert">{{ $message }}</span>@enderror
          </div>

          <div class="login-field">
            <div class="login-label-row">
              <label for="login-password">Password</label>
              @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
              @endif
            </div>
            <div class="login-password-wrap">
              <input id="login-password" type="password" name="password" placeholder="Enter your password" autocomplete="current-password" required aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
              <button type="button" class="login-password-toggle" id="login-password-toggle" aria-label="Show password" aria-pressed="false">Show</button>
            </div>
            @error('password')<span class="login-error" role="alert">{{ $message }}</span>@enderror
          </div>

          <label class="login-remember">
            <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
            <span>Remember me on this device</span>
          </label>

          <button type="submit" class="login-submit">Sign in to dashboard <span aria-hidden="true">→</span></button>
        </form>

        <div class="login-card-footer">
          <span>Don’t have an account?</span>
          <a href="{{ route('register') }}">Create your school account</a>
        </div>
      </section>

      <aside class="login-visual" aria-label="Illustrative school dashboard preview">
        <div class="login-visual-copy">
          <span class="login-visual-kicker">Your school, all in one place</span>
          <h2>Start your day with clarity.</h2>
          <p>See what needs attention, check attendance, and keep school operations moving.</p>
        </div>
        <div class="login-visual-stage">
          <div class="login-visual-ring" aria-hidden="true"></div>
          <img class="login-administrator" src="{{ asset('images/login-administrator.webp') }}" alt="Illustration of a school administrator at a desk using a tablet and stylus" width="1350" height="1165" loading="lazy" decoding="async">

          <div class="login-metric login-metric-attendance">
            <span class="login-metric-label">Today’s attendance</span>
            <div class="login-attendance-body">
              <span class="login-attendance-ring" aria-hidden="true"></span>
              <div><strong>98.2%</strong><span>Present</span></div>
            </div>
          </div>

          <div class="login-metric login-metric-approvals">
            <span class="login-metric-icon" aria-hidden="true">
              <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4h8a2 2 0 0 1 2 2v14H6V6a2 2 0 0 1 2-2Z"/><path d="M9 9h6M9 13h6M9 17h3"/></svg>
            </span>
            <span class="login-metric-label">Pending approvals</span>
            <strong>12 <small>requests</small></strong>
            <span class="login-metric-hint">Ready for review</span>
          </div>
        </div>
        <p class="login-visual-caption">Illustrative dashboard preview</p>
      </aside>
    </main>

    <footer class="login-footer">
      <span>© {{ date('Y') }} PMRS</span>
      <a href="mailto:pmrsteam.official@gmail.com">Need help? Contact us</a>
    </footer>
  </div>

  <style>
    .auth-page { display: block; padding: 0; overflow: visible; background: #fbf9f2; }
    .auth-page::before { display: none; }
    .login-shell {
      --login-ink: #151515;
      --login-muted: #68645f;
      --login-accent: #ffcc99;
      min-height: 100vh;
      min-height: 100dvh;
      overflow: hidden;
      background: #fbf9f2;
      color: var(--login-ink);
      font-family: 'Poppins', 'Inter', sans-serif;
    }
    .login-header, .login-footer {
      width: min(calc(100% - 72px), 1400px);
      margin-inline: auto;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
    }
    .login-header { min-height: 84px; }
    .login-brand { display: flex; align-items: center; gap: 18px; }
    .login-brand img { width: 118px; height: auto; }
    .login-brand span { padding-left: 18px; border-left: 1px solid #e4ddd3; color: var(--login-muted); font-size: 11px; font-weight: 500; }
    .login-header-action { display: flex; align-items: center; gap: 13px; color: var(--login-muted); font-size: 12px; }
    .login-header-action a {
      display: inline-flex; align-items: center; gap: 8px; min-height: 40px; padding: 8px 15px;
      border: 1px solid #e9dbc9; border-radius: 10px; background: #fff; color: var(--login-ink);
      font-weight: 700; transition: background .2s, transform .2s;
    }
    .login-header-action a:hover { background: #fff2e4; transform: translateY(-1px); }
    .login-main {
      width: min(calc(100% - 72px), 1400px);
      min-height: calc(100dvh - 140px);
      margin: 0 auto;
      padding: 24px 0 36px;
      display: grid;
      grid-template-columns: minmax(0, 1.02fr) minmax(0, .98fr);
      align-items: center;
      gap: clamp(30px, 4vw, 68px);
    }
    .login-card {
      width: min(100%, 570px);
      padding: clamp(30px, 3.5vw, 48px);
      border: 1px solid #eee7df;
      border-radius: 22px;
      background: #fff;
      box-shadow: 0 18px 48px rgba(74, 57, 35, .09);
    }
    .login-intro { margin-bottom: 30px; }
    .login-eyebrow { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 12px; color: #9b622f; font-size: 10px; font-weight: 700; letter-spacing: .11em; text-transform: uppercase; }
    .login-eyebrow-dot { width: 7px; height: 7px; border-radius: 50%; background: #eca767; box-shadow: 0 0 0 4px #fff2e6; }
    .login-intro h1 { margin: 0 0 8px; color: #111; font-size: clamp(27px, 2.7vw, 34px); font-weight: 700; letter-spacing: -.04em; line-height: 1.25; }
    .login-intro p { color: var(--login-muted); font-size: 12px; line-height: 1.65; }
    .login-status { margin-bottom: 20px; padding: 12px 14px; border: 1px solid #bfdfcf; border-radius: 10px; background: #f0faf4; color: #286a45; font-size: 12px; }
    .login-field { margin-bottom: 20px; }
    .login-field label { display: block; margin-bottom: 7px; color: #292723; font-size: 12px; font-weight: 700; }
    .login-label-row { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; }
    .login-label-row a { color: #895526; font-size: 11px; font-weight: 700; }
    .login-label-row a:hover { text-decoration: underline; }
    .login-field input {
      width: 100%; height: 48px; padding: 0 13px; border: 1px solid #e5e1dc; border-radius: 9px;
      outline: none; background: #fff; color: var(--login-ink);
      font: 500 13px 'Poppins', 'Inter', sans-serif; transition: border-color .2s, box-shadow .2s;
    }
    .login-field input::placeholder { color: #a7a19a; font-weight: 400; }
    .login-field input:hover { border-color: #c8b9a8; }
    .login-field input:focus { border-color: #e8a76c; box-shadow: 0 0 0 3px rgba(255, 204, 153, .35); }
    .login-field input[aria-invalid="true"] { border-color: #d95151; }
    .login-error { display: block; margin-top: 7px; color: #b93939; font-size: 11px; line-height: 1.5; }
    .login-password-wrap { position: relative; }
    .login-password-wrap input { padding-right: 64px; }
    .login-password-toggle {
      position: absolute; top: 50%; right: 7px; transform: translateY(-50%);
      min-width: 48px; min-height: 32px; border: 0; border-radius: 6px; background: transparent;
      color: #895526; font: 700 11px 'Poppins', 'Inter', sans-serif; cursor: pointer;
    }
    .login-password-toggle:hover { background: #fff1e3; }
    .login-remember { display: inline-flex; align-items: center; gap: 9px; color: #514d47; font-size: 11px; cursor: pointer; }
    .login-remember input { width: 15px; height: 15px; margin: 0; accent-color: #d68b4d; }
    .login-submit {
      display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; min-height: 50px;
      margin-top: 26px; border: 0; border-radius: 10px; background: var(--login-accent); color: #111;
      font: 700 14px 'Poppins', 'Inter', sans-serif; cursor: pointer;
      transition: background .2s, transform .2s, box-shadow .2s;
    }
    .login-submit:hover { background: #f6b878; box-shadow: 0 8px 18px rgba(209, 137, 65, .16); transform: translateY(-1px); }
    .login-password-toggle:focus-visible, .login-submit:focus-visible,
    .login-header-action a:focus-visible, .login-card-footer a:focus-visible,
    .login-label-row a:focus-visible, .login-footer a:focus-visible { outline: 3px solid #e9a764; outline-offset: 3px; }
    .login-card-footer { display: flex; justify-content: center; flex-wrap: wrap; gap: 5px; margin-top: 27px; padding-top: 20px; border-top: 1px solid #f0eae4; color: var(--login-muted); font-size: 11px; }
    .login-card-footer a { color: #895526; font-weight: 700; }
    .login-card-footer a:hover { text-decoration: underline; }
    .login-visual { min-width: 0; align-self: center; }
    .login-visual-copy { max-width: 470px; margin: 0 auto; text-align: center; }
    .login-visual-kicker { color: #aa6b35; font-size: 10px; font-weight: 700; letter-spacing: .11em; text-transform: uppercase; }
    .login-visual-copy h2 { margin: 8px 0 7px; font-size: clamp(24px, 2.5vw, 35px); line-height: 1.22; letter-spacing: -.04em; }
    .login-visual-copy p { color: var(--login-muted); font-size: 12px; line-height: 1.6; }
    .login-visual-stage { position: relative; width: 100%; height: clamp(370px, 36vw, 500px); margin-top: 4px; }
    .login-visual-stage::before {
      content: ""; position: absolute; right: 2%; bottom: 7%; width: 80px; height: 100px;
      background-image: radial-gradient(#e4b98c 1.6px, transparent 1.6px); background-size: 17px 17px; opacity: .5;
    }
    .login-visual-ring { position: absolute; z-index: 0; top: 5%; left: 50%; width: 68%; aspect-ratio: 1; border: 1px solid #e9ded2; border-radius: 50%; transform: translateX(-50%); }
    .login-visual-ring::after { content: ""; position: absolute; inset: 12%; border: 1px solid #f0e8df; border-radius: 50%; }
    .login-administrator { position: absolute; z-index: 1; left: 50%; bottom: 0; width: min(95%, 620px); max-width: none; height: auto; transform: translateX(-50%); filter: drop-shadow(0 18px 18px rgba(80, 62, 47, .08)); }
    .login-metric {
      position: absolute; z-index: 2; width: 177px; padding: 14px 15px;
      border: 1px solid #f2eee8; border-radius: 12px; background: #fff;
      box-shadow: 0 15px 38px rgba(68, 57, 44, .12);
    }
    .login-metric-attendance { top: 17%; left: 0; transform: rotate(-3deg); }
    .login-metric-approvals { top: 39%; right: 0; transform: rotate(3deg); }
    .login-metric-label { display: block; color: #69645e; font-size: 10px; font-weight: 600; }
    .login-attendance-body { display: flex; align-items: center; gap: 11px; margin-top: 9px; }
    .login-attendance-ring { display: block; flex: 0 0 50px; width: 50px; height: 50px; border-radius: 50%; background: conic-gradient(#62bd85 0 98%, #e5f1e9 98% 100%); position: relative; }
    .login-attendance-ring::after { content: ""; position: absolute; inset: 8px; border-radius: 50%; background: #fff; }
    .login-attendance-body strong { display: block; color: #23211f; font-size: 20px; line-height: 1.15; }
    .login-attendance-body div span { color: #299364; font-size: 10px; font-weight: 700; }
    .login-metric-icon { display: grid; place-items: center; width: 30px; height: 30px; margin-bottom: 8px; border-radius: 8px; background: #fff0df; color: #b36d32; }
    .login-metric-approvals strong { display: block; margin-top: 4px; color: #23211f; font-size: 22px; line-height: 1.2; }
    .login-metric-approvals small { color: #514d47; font-size: 11px; font-weight: 500; }
    .login-metric-hint { color: #a36a39; font-size: 9px; }
    .login-visual-caption { margin-top: -3px; color: #8a8278; font-size: 10px; text-align: center; }
    .login-footer { padding: 8px 0 24px; color: #706b65; font-size: 10px; }
    .login-footer a { color: #51463c; font-weight: 600; }
    .login-footer a:hover { color: #a9652e; }
    @media (max-width: 980px) {
      .login-main { width: min(calc(100% - 36px), 640px); min-height: auto; display: block; padding: 20px 0 36px; }
      .login-card { width: 100%; max-width: none; margin: 0 auto; }
      .login-visual { display: none; }
      .login-header, .login-footer { width: min(calc(100% - 36px), 640px); }
    }
    @media (max-width: 560px) {
      .login-header { min-height: 72px; }
      .login-brand img { width: 108px; }
      .login-brand span, .login-header-action > span { display: none; }
      .login-main { width: calc(100% - 24px); padding-top: 6px; }
      .login-card { padding: 30px 22px; border-radius: 17px; }
      .login-field input { font-size: 16px; }
      .login-label-row a { font-size: 10px; }
    }
    @media (prefers-reduced-motion: reduce) { .login-shell * { scroll-behavior: auto; transition: none !important; } }
  </style>

  <script>
    (() => {
      const input = document.getElementById('login-password');
      const button = document.getElementById('login-password-toggle');
      button.addEventListener('click', () => {
        const showing = input.type === 'password';
        input.type = showing ? 'text' : 'password';
        button.textContent = showing ? 'Hide' : 'Show';
        button.setAttribute('aria-label', showing ? 'Hide password' : 'Show password');
        button.setAttribute('aria-pressed', String(showing));
      });
    })();
  </script>
</x-guest-layout>
